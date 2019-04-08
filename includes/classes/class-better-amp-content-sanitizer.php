<?php


/**
 * Strips blacklisted tags and attributes from content.
 *
 * Note: Base codes was copied from Automattic/AMP plugin: http://github.com/Automattic/amp-wp
 *
 * @since     1.0.0
 */
class Better_AMP_Content_Sanitizer {

	/**
	 * @var bool
	 *
	 * @since 1.0.0
	 */
	public static $enable_url_transform = true;


	/**
	 * Store Better_AMP_HTML_Util dom object
	 *
	 * @var Better_AMP_HTML_Util
	 *
	 * @since 1.1
	 */
	public $dom;


	/**
	 * Store list of attributes which is allow for any tag
	 *
	 * @var array
	 *
	 * @since 1.1
	 */
	public $general_attrs = array(
		'class'  => true,
		'on'     => true,
		'id'     => true,
		'layout' => true,
		'width'  => true,
		'height' => true,
		'sizes'  => true,
	);

	/**
	 * List of none-amp urls
	 *
	 * @since 1.3.4
	 * @var array
	 */
	private static $none_amp_urls = array();

	/**
	 * Store tabindex number
	 *
	 * @var int
	 *
	 * @since 1.1
	 */
	public $tabindex = 10;


	/**
	 * Store html tags list
	 *
	 * @var array
	 *
	 * @since 1.1
	 */
	public $tags = array();


	/**
	 * @since 1.0.0
	 */
	const PATTERN_REL_WP_ATTACHMENT = '#wp-att-([\d]+)#';


	public function __construct( Better_AMP_HTML_Util $dom ) {

		$this->dom = $dom;
	}


	/**
	 * Prepare html content for amp version it removes:
	 * 1) invalid tags
	 * 2) invalid attributes
	 * 3) invalid url protocols
	 *
	 * @since 1.0.0
	 */
	public function sanitize() {

		$blacklisted_attributes = $this->get_blacklisted_attributes();

		$this->sanitize_document();

		$tags = array();
		include BETTER_AMP_INC . 'tags-list.php';
		$this->tags = $tags;

		$this->strip_attributes_recursive( $this->dom->get_body_node(), $blacklisted_attributes );
		$this->tags = array();
	}


	/**
	 * List of blacklisted attributes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_blacklisted_attributes() {

		return array(
			'style',
			'size',
		);
	}


	/**
	 * Stripes attributes on nodes and childs
	 *
	 * @param DOMElement $node
	 * @param array      $bad_attributes
	 *
	 * @since 1.0.0
	 */
	private function strip_attributes_recursive( $node, $bad_attributes ) {

		if ( ! isset( $node->nodeType ) || $node->nodeType !== XML_ELEMENT_NODE ) {
			return;
		}

		if ( ! isset( $this->tags[ $node->tagName ] ) ) { // remove invalid tag
			self::remove_element( $node );

			return;
		}

		$node_name = $node->nodeName;

		// Some nodes may contain valid content but are themselves invalid.
		// Remove the node but preserve the children.

		if ( $node->hasAttributes() ) {

			$length = $node->attributes->length;

			for ( $i = $length - 1; $i >= 0; $i -- ) {
				$attribute = $node->attributes->item( $i );

				$attribute_name = strtolower( $attribute->name );

				if ( $attribute_name === 'style' ) {
					$this->save_element_style( $node, $attribute );
				}

				if ( in_array( $attribute_name, $bad_attributes ) ) {
					$node->removeAttribute( $attribute_name );

					continue;
				}

				// on* attributes (like onclick) are a special case
				if ( 0 === stripos( $attribute_name, 'on' ) && $attribute_name != 'on' ) {

					$node->removeAttribute( $attribute_name );

					continue;
				}
			}
		}

		$length = $node->childNodes->length;

		for ( $i = $length - 1; $i >= 0; $i -- ) {
			$child_node = $node->childNodes->item( $i );

			$this->strip_attributes_recursive( $child_node, $bad_attributes );
		}

		if ( 'font' === $node_name ) {
			$this->replace_node_with_children( $node );
		}
	}


	/**
	 * Remove the wrapper of node
	 *
	 * @param $node
	 *
	 * @since 1.0.0
	 */
	private function replace_node_with_children( $node ) {

		// If the node has children and also has a parent node,
		// clone and re-add all the children just before current node.
		if ( $node->hasChildNodes() && $node->parentNode ) {
			foreach ( $node->childNodes as $child_node ) {
				$new_child = $child_node->cloneNode( true );
				$node->parentNode->insertBefore( $new_child, $node );
			}
		}

		// Remove the node from the parent, if defined.
		if ( $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		}
	}


	/**
	 * Check string to end with
	 *
	 * @param $haystack
	 * @param $needle
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function endswith( $haystack, $needle ) {

		return '' !== $haystack
		       && '' !== $needle
		       && $needle === substr( $haystack, - strlen( $needle ) );
	}


	/**
	 * Sanitize the dimensions to be AMP valid
	 *
	 * @param $value
	 * @param $dimension
	 *
	 * @since 1.0.0
	 *
	 * @return float|int|string
	 */
	public static function sanitize_dimension( $value, $dimension ) {

		if ( empty( $value ) ) {
			return $value;
		}

		if ( false !== filter_var( $value, FILTER_VALIDATE_INT ) ) {
			return absint( $value );
		}

		if ( self::endswith( $value, 'px' ) ) {
			return absint( $value );
		}

		if ( self::endswith( $value, '%' ) ) {
			if ( 'width' === $dimension ) {
				$percentage = absint( $value ) / 100;

				return round( $percentage * better_amp_get_container_width() );
			}
		}

		return '';
	}


	/**
	 * Convert $url to amp version if:
	 * 1) $url was internal
	 * 2) disable flag is not true  {@see turn_url_transform_off_on}
	 *
	 * @param string $url
	 *
	 * @since 1.0.0
	 *
	 * @return string transformed amp url on success or passed $url otherwise.
	 */
	public static function transform_to_amp_url( $url ) {

		if ( ! self::$enable_url_transform ) {
			return $url;
		}

		//
		// Don't transform none-amp urls to amp url
		//
		{
			if ( isset( self::$none_amp_urls['general'][ rtrim( $url, '/' ) ] ) ) {
				return $url;
			}

			if ( ! empty( self::$none_amp_urls['start_with'] ) ) {

				if ( preg_match( '#^' . self::$none_amp_urls['start_with'] . '#i', $url ) ) {
					return $url;
				}
			}
		}


		if ( ! better_amp_using_permalink_structure() ) {

			return add_query_arg( Better_AMP::SLUG, true, $url );
		}

		if ( better_amp_url_format() === 'end-point' ) {

			if ( $transformed = self::transform_to_end_point_amp( $url ) ) {

				return $transformed;
			}

		} else {

			if ( $transformed = self::transform_to_start_point_amp( $url ) ) {

				return $transformed;
			}
		}

		return $url;
	}

	/**
	 * @param string $url
	 *
	 * @return bool
	 */
	public static function transform_to_end_point_amp( $url ) {

		if ( ! preg_match( '#^https?://w*\.?' . self::regex_url() . '/?([^/]*)#', $url, $matched ) ) {

			return false;
		}


		if ( $matched[1] === 'wp-content' ) { // Do not convert link  when started with wp-content

			return false;
		}

		$parsed = parse_url( $url );
		$path   = isset( $parsed['path'] ) ? $parsed['path'] : '/';
		$query  = isset( $parsed['query'] ) ? $parsed['query'] : '';

		if ( basename( $path ) === Better_AMP::SLUG ) {

			return false;
		}

		if ( ! better_amp_using_permalink_structure() ) {

			return add_query_arg( Better_AMP::SLUG, true, $url );
		}

		$trailing_slash = substr( $url, - 1 ) === '/';

		$url = sprintf( '%s://%s/%s', $parsed['scheme'], $parsed['host'], ltrim( $path, '/' ) );

		if ( preg_match( '#(.*?)/(page/(\d+)|comment-page-([0-9]{1,}))/*$#', $url, $matched ) ) {

			$url = trailingslashit( $matched[1] ) . Better_AMP::SLUG . '/' . $matched[2];
		} else {

			$url = trailingslashit( $url ) . Better_AMP::SLUG;
		}

		$url .= $trailing_slash ? '/' : '';

		if ( $query ) {
			$url .= '?';
			$url .= $parsed['query'];
		}

		return $url;
	}


	/**
	 * Transform given url to AMP permalink on starting point.
	 *
	 * @param string $url
	 *
	 * @return bool|string url on success or false on failure.
	 */
	public static function transform_to_start_point_amp( $url ) {

		// check is url internal?
		// todo support parked domains

		if ( ! preg_match( '#^https?://w*\.?' . self::regex_url() . '/?([^/]*)/?([^/]*)/?(.*?)$#', $url, $matched ) ) {

			return false;
		}

		// If url was not amp
		$exclude_sub_dirs = (array) apply_filters( 'better-amp/transformer/exclude-subdir', array() );

		$sub_dir_excluded = in_array( $matched[1], $exclude_sub_dirs );
		$first_valid_dir  = $sub_dir_excluded ? $matched[2] : $matched[1];

		if ( $first_valid_dir === Better_AMP::SLUG ) { // It's Already AMP URL

			return false;
		}

		if ( $matched[1] === 'wp-content' ) { // Do not convert link which is started with wp-content

			return false;
		}

		$before_sp = '';
		$path      = '/';

		if ( $matched[1] ) {

			$matched[0] = '';

			if ( $sub_dir_excluded ) {
				$before_sp = $matched[1];

				$matched[1] = '';
			}

			$path = implode( '/', array_filter( $matched ) );
		}

		$path = rtrim( $path, '/' );
		$path .= substr( $url, - 1 ) === '/' ? '/' : '';

		return better_amp_site_url( $path, $before_sp, true );
	}


	/**
	 * @param string $delimiter
	 *
	 * @return string
	 */
	public static function regex_url( $delimiter = '#' ) {

		$site_domain = str_replace(
			array(
				'http://www.',
				'https://www.',
				'http://',
				'https://',
			),
			'',
			home_url()
		);

		return preg_quote( rtrim( $site_domain, '/' ), $delimiter );
	}

	/**
	 * Convert amp $url to none-amp version if $url was internal
	 *
	 * @param string $url
	 * @param bool   $strict
	 *
	 * @return string transformed none-amp url on success or passed $url otherwise.
	 * @since 1.0.0
	 *
	 */
	public static function transform_to_none_amp_url( $url, $strict = false ) {

		if ( better_amp_using_permalink_structure() ) {

			if ( $strict ) {

				$url = self::remove_end_point_amp( $url, $url );
				$url = self::remove_start_point_amp( $url, $url );

			} elseif ( better_amp_url_format() === 'end-point' ) {

				$url = self::remove_end_point_amp( $url, $url );

			} else {

				$url = self::remove_start_point_amp( $url, $url );
			}
		}

		return remove_query_arg( Better_AMP::SLUG, $url );
	}


	/**
	 * @param string $url
	 * @param mixed  $default
	 *
	 * @return bool|string none amp url on success or false on error.
	 */
	public static function remove_start_point_amp( $url, $default = false ) {

		if ( empty( $url ) ) {
			return $default;
		}

		$prefix = better_amp_permalink_prefix();

		if ( ! preg_match( '#^https?://w*\.?' . self::regex_url() . '/?' . $prefix . '([^/]*)/?(.*?)$#', $url, $matched ) ) {

			return $default;
		}

		// if url was not amp
		if ( $matched[1] !== Better_AMP::SLUG ) {

			return $default;
		}

		if ( $matched[1] ) {

			$matched[0] = '';

			unset( $matched[1] );
			$path = implode( '/', $matched );
		} else {
			$path = '/';
		}

		return home_url( rtrim( $prefix, '/' ) . $path );
	}

	/**
	 * @param string $url
	 * @param mixed  $default
	 *
	 * @return mixed none amp url on success or $default on error.
	 */
	public static function remove_end_point_amp( $url, $default = false ) {

		if ( empty( $url ) ) {
			return $default;
		}

		if ( ! preg_match( '#^https?://w*\.?' . self::regex_url() . '/?#', $url ) ) {

			return $default;
		}

		$parsed = parse_url( $url );

		if ( empty( $parsed['path'] ) ) {
			return $default;
		}

		if ( basename( $parsed['path'] ) !== Better_AMP::SLUG ) {

			if ( $transformed = self::single_post_pagination_none_amp_url( $parsed['path'] ) ) {

				return $transformed;
			}

			if ( $transformed = self::pagination_none_amp_url( $parsed['path'] ) ) {

				return $transformed;
			}

			return $default;
		}

		return trailingslashit( sprintf( '%s://%s%s', $parsed['scheme'], $parsed['host'], dirname( $parsed['path'] ) ) );
	}

	/**
	 * Convert the following url.
	 *
	 *  [single post]/[page-number]/amp
	 *
	 * to
	 *
	 *  [single post]/amp/[page-number]
	 *
	 *
	 * @since 1.0.
	 *
	 * @param string $url_path
	 *
	 * @return string|bool.
	 */
	protected static function single_post_pagination_none_amp_url( $url_path ) {

		global $wp_rewrite;

		$single_post_format = str_replace( $wp_rewrite->rewritecode, $wp_rewrite->rewritereplace, get_option( 'permalink_structure' ) );
		//
		$test_pattern = '(' . $single_post_format . ')'; // Capture as the first item $match[1]
		$test_pattern .= Better_AMP::SLUG;
		$test_pattern .= '/+(\d+)/?';                    //  Capture as the last item array_pop( $match )

		if ( preg_match( "#^$test_pattern$#", $url_path, $match ) ) {

			$page_number          = array_pop( $match );
			$none_amp_request_url = $match[1];

			return home_url( $none_amp_request_url . $page_number );
		}

		return false;
	}


	/**
	 * Remove AMP end-point in paginated pages.
	 *
	 * @param string $url_path
	 *
	 * @since 1.9.5
	 * @return bool|string
	 */
	public static function pagination_none_amp_url( $url_path ) {

		if ( preg_match( '#(.+)/' . Better_AMP::SLUG . '(/page/\d+/?)#', $url_path, $match ) ) {

			return home_url( $match[1] . $match[2] );
		}

		return false;
	}

	/**
	 * Replace internal links with amp version just in href attribute
	 *
	 * @param array $attr list of attributes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function replace_href_with_amp( $attr ) {

		if ( isset( $attr['href'] ) ) {
			$attr['href'] = self::transform_to_amp_url( $attr['href'] );
		}

		return $attr;
	}


	/**
	 * Trigger url transform status on/off
	 *
	 * @see   transform_to_amp_url
	 *
	 * @param bool $is_on
	 *
	 * @since 1.0.0
	 *
	 * @return bool previous situation
	 */
	public static function turn_url_transform_off_on( $is_on ) {

		$prev                       = self::$enable_url_transform;
		self::$enable_url_transform = $is_on;

		return $prev;
	}


	/**
	 * Callback function for preg_replace_callback
	 * to replace html href="" links to amp version
	 *
	 * @param  array $match pattern matches
	 *
	 * @access private
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	private static function _preg_replace_link_callback( $match ) {

		$url  = empty( $match[4] ) ? $match[3] : $match[4];
		$url  = self::transform_to_amp_url( $url );
		$atts = &$match[1];
		$q    = &$match[2];

		return sprintf( '<a %1$shref=%2$s%3$s%2$s', $atts, $q, esc_attr( $url ) );
	}


	/**
	 * Convert all links in html content to amp link
	 * Except links which is started with wp-content
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function transform_all_links_to_amp( $content ) {

		/**
		 * @copyright $pattern copied from class snoopy
		 * @see       Snoopy::_striplinks
		 */
		$pattern = "'<\s*a\s(.*?)href\s*=\s*	    # find <a href=
						([\"\'])?					# find single or double quote
						(?(2) (.*?)\\2 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx";

		return preg_replace_callback( $pattern, array( __CLASS__, '_preg_replace_link_callback' ), $content );
	}


	/**
	 * @param $element
	 *
	 * @since 1.1
	 */
	public static function remove_element( $element ) {

		$element->parentNode->removeChild( $element );
	}


	/**
	 * @param array      $element_atts
	 * @param DOMElement $element
	 *
	 * @since 1.1
	 * @return array
	 */
	public function get_invalid_attrs( $element_atts, $element ) {

		$invalid_attrs = array();

		switch ( $element->tagName ) {

			case 'amp-img':

				if ( isset( $element_atts['width'] ) && $element_atts['width'] === 'auto' ) {
					$invalid_attrs[] = 'width';
				}
				break;
		}

		return $invalid_attrs;
	}


	/**
	 * @since 1.1
	 */
	public function sanitize_document() {

		$prev_tag_name = false;

		$rules = array();

		$white_list_atts = array(
			'style', // style attributies will collect and sanitize @see save_element_style
		);

		include BETTER_AMP_INC . 'sanitizer-rules.php';

		foreach ( $rules as $rule ) {

			if ( $prev_tag_name !== $rule['tag_name'] ) {
				$elements      = $this->dom->getElementsByTagName( $rule['tag_name'] );
				$prev_tag_name = $rule['tag_name'];
			}
			if ( $nodes_count = $elements->length ) {

				if ( empty( $atts ) ) { // Remove all elements attributes

					$this->remove_all_attributes( $elements );
				}

				foreach ( $rule['attrs'] as $atts ) {

					if ( empty( $atts['name'] ) ) {
						continue;
					}

					for ( $i = $nodes_count - 1; $i >= 0; $i -- ) {

						$element = $elements->item( $i );

						if ( ! $element ) { // if element was deleted
							break 2;
						}

						$element_atts = self::get_node_attributes( $element );
						$atts2remove  = $this->get_invalid_attrs( $element_atts, $element );
						$new_atts     = array();
						$mandatory    = false;

						foreach ( $atts2remove as $attr ) {
							unset( $element_atts[ $attr ] );
						}

						/**
						 * STEP 1) remove height=auto images
						 */

						if ( $rule['tag_name'] === 'amp-img' && isset( $element_atts['height'] ) && $element_atts['height'] === 'auto' ) {
							self::remove_element( $element ); // Remove invalid element
							continue;
						}


						/**
						 * STEP 2) Sanitize layout attribute
						 */


						if ( ! empty( $rule['layouts']['supported_layouts'] ) ) {

							if ( ! empty( $element_atts['layout'] ) ) {

								$layout = strtoupper( $element_atts['layout'] );


								if ( in_array( $layout, $rule['layouts']['supported_layouts'] ) ) { //

									$this->sanitize_layout_attribute( $layout, $element, $element_atts );
								} else { // invalid layout attribute value


									if ( ! empty( $element_atts['width'] ) && ! empty( $element_atts['height'] ) ) {

										$new_atts['layout'] = 'responsive';
									} else {

										$new_atts['layout'] = 'fill';
									}
								}
							} else {

								if ( isset( $element_atts['width'] ) && $element_atts['width'] === 'auto' && ! empty( $element_atts['height'] ) ) {
									// default layout is FIXED-HEIGHT
									if ( ! in_array( 'FIXED-HEIGHT', $rule['layouts']['supported_layouts'] ) ) {

										$atts2remove[] = 'width';
									}
								}
							}
						}


						/**
						 * STEP 3) search for single required attributes
						 */
						if ( ! empty( $atts['mandatory'] ) ) { // if attribute is required

							if ( ! isset( $element_atts[ $atts['name'] ] ) ) {

								self::remove_element( $element ); // Remove invalid element

								continue;
							}

							$mandatory = true;
						}

						/**
						 * STEP 4) search for alternative required attributes
						 */
						if ( ! empty( $atts['mandatory_oneof'] ) ) {

							if ( ! array_intersect_key( $element_atts, $atts['mandatory_oneof'] ) ) { // no required attribute was found
								if ( empty( $atts['value'] ) ) {

									self::remove_element( $element ); // Remove invalid element

									continue;

								} else { // add required attribute to element if attribute value exists

									$new_atts[ $atts['name'] ] = $atts['value'];
								}
							} else {
								$mandatory = true;
							}
						}

						/**
						 * STEP 5) Sanitize attribute value
						 */
						if ( ! empty( $element_atts[ $atts['name'] ] ) ) {

							$remove_element = false;
							foreach ( array( 'value_regex', 'value_regex_case' ) as $regex_field ) {

								if ( ! empty( $atts[ $regex_field ] ) ) {

									$modifier = 'value_regex_case' === $regex_field ? 'i' : '';

									if ( ! preg_match( '#^' . $atts[ $regex_field ] . '$#' . $modifier, $element_atts[ $atts['name'] ] ) ) {


										if ( $mandatory ) {
											$remove_element = true;
										} else {

											$atts2remove[] = $atts['name'];
											break;
										}
									}
								}
							}

							if ( $remove_element ) {

								self::remove_element( $element ); // Remove invalid element
								continue;
							}

							if ( ! empty( $atts['blacklisted_value_regex'] ) ) { // Check blacklist

								if ( ! preg_match( '/' . $atts['blacklisted_value_regex'] . '/', $element_atts[ $atts['name'] ] ) ) {

									$atts2remove[] = $atts['name'];
								}
							}
						}

						/**
						 * STEP 6) Sanitize url value
						 *
						 */

						if ( ! empty( $atts['value_url'] ) ) {

							$val    = isset( $element_atts[ $atts['name'] ] ) ? htmlentities( $element_atts[ $atts['name'] ] ) : null;
							$parsed = $val ? parse_url( $val ) : array();


							// check empty url value
							if ( isset( $atts['value_url']['allow_empty'] ) && ! $atts['value_url']['allow_empty'] ) {
								// empty url is not allowed

								if ( empty( $element_atts[ $atts['name'] ] ) ) { // is url relative ?
									if ( $mandatory ) {
										$remove_element = true;
									} else {

										$atts2remove[] = $atts['name'];
									}
								}
							}


							// check url protocol
							if ( ! empty( $atts['value_url']['allowed_protocol'] ) ) {

								if ( isset( $parsed['scheme'] ) ) {

									if ( ! in_array( $parsed['scheme'], $atts['value_url']['allowed_protocol'] ) ) { // invalid url protocol

										if ( $mandatory ) {
											$remove_element = true;
										} else {

											$atts2remove[] = $atts['name'];
										}
									}
								}
							}

							if ( isset( $atts['value_url']['allow_relative'] ) && ! $atts['value_url']['allow_relative'] ) {
								// relative url is not allowed

								if ( empty( $parsed['host'] ) ) { // is url relative ?
									if ( $mandatory ) {
										$remove_element = true;
									} else {

										$atts2remove[] = $atts['name'];
									}
								}
							}

							// Force schema for amp-iframe tag
							if ( empty( $parsed['scheme'] ) && $rule['tag_name'] == 'amp-iframe' ) {
								$parsed['scheme'] = 'https';
							}

							if ( ! empty( $remove_element ) ) {
								self::remove_element( $element ); // Remove invalid element
								continue;
							} else {
								$val = better_amp_unparse_url( $parsed );
								if ( ! empty( $val ) ) {
									$element->setAttribute( $atts['name'], $val );
									$element_atts[ $atts['name'] ] = $val;
								}
							}

						}


						/**
						 * STEP 7) Sanitize attribute with fixed value
						 */
						if ( isset( $atts['value'] ) && isset( $element_atts[ $atts['name'] ] ) ) {

							if ( $element_atts[ $atts['name'] ] !== $atts['value'] ) { // is current value invalid?
								$new_atts[ $atts['name'] ] = $atts['value']; // set valid value
							}
						}


						/**
						 * STEP 8) Filter attributes list
						 */

						if ( sizeof( $atts ) === 1 ) { // check is attribute boolean


							if ( $element_atts ) {
								$el_atts = $this->_get_rule_attrs_list( $rule );

								foreach ( $element_atts as $k => $v ) {

									if ( isset( $this->general_attrs[ $k ] ) ) {
										continue;
									}

									if ( substr( $k, 0, 5 ) !== 'data-' ) {
										$atts2remove[ $k ] = $v;
									}
								}
								$atts2remove = array_diff_key( $atts2remove, $el_atts ); // Filter extra attrs
								$atts2remove = array_keys( $atts2remove );
							}
						}

						/**
						 * STEP 9) Sanitize elements with on attribute
						 */

						if ( ! empty( $element_atts['on'] ) ) {

							if ( substr( $element_atts['on'], 0, 4 ) === 'tap:' ) { // now role & tabindex attribute is required

								if ( empty( $element_atts['tabindex'] ) ) {
									$new_atts['tabindex'] = $this->tabindex ++;
								}

								if ( empty( $element_atts['role'] ) ) {
									$new_atts['role'] = $rule['tag_name'];
								}
							}
						}

						/**
						 * STEP 10) Sanitize percentage  with
						 */
						if ( isset( $element_atts['width'] ) && stristr( $element_atts['width'], '%' ) ) {

							$new_atts['width'] = self::sanitize_dimension( $element_atts['width'], 'width' );
						}

						if ( $atts2remove ) {
							$atts2remove = array_diff( $atts2remove, $white_list_atts ); // Skip white list items
							$this->dom->remove_attributes( $element, $atts2remove ); // Remove invalid attributes
						}

						if ( $new_atts ) {
							$this->dom->add_attributes( $element, $new_atts ); // add/ update element attribute
						}
					}
				}
			}
		}

		$body = $this->dom->get_body_node();

		if ( $body ) {


			/**
			 * Remove all extra tags
			 */

			$extra_tags = array(
				'script',
				'svg',
				'canvas',
				'link',
			);

			foreach ( $extra_tags as $tag_name ) {

				$elements = $body->getElementsByTagName( $tag_name );

				if ( $elements->length ) {

					for ( $i = $elements->length - 1; $i >= 0; $i -- ) {
						$element = $elements->item( $i );

						if ( $tag_name === 'script' && $element->parentNode->tagName === 'amp-analytics' ) {

							$atts = self::get_node_attributes( $element );

							if ( isset( $atts['type'] ) && $atts['type'] === 'application/json' ) {
								continue;
							}
						}

						self::remove_element( $element );
					}
				}
			}

			/**
			 * Remove extra style tags and collect their contents
			 */
			$elements = $body->getElementsByTagName( 'style' );

			if ( $elements->length ) {

				for ( $i = $elements->length - 1; $i >= 0; $i -- ) {
					$element = $elements->item( $i );

					$style = preg_replace( '/\s*!\s*important/', '', $element->nodeValue ); // Remove !important
					better_amp_add_inline_style( $style );

					self::remove_element( $element );
				}
			}

			/**
			 * Sanitize Form Tag
			 */

			$elements = $body->getElementsByTagName( 'form' );

			if ( $elements->length ) {

				better_amp_enqueue_script( 'amp-form', 'https://cdn.ampproject.org/v0/amp-form-0.1.js"' );

				$valid_target_values = array(
					'_blank' => true,
					'_top'   => true,
				);

				for ( $i = $elements->length - 1; $i >= 0; $i -- ) {

					$action  = '';
					$element = $elements->item( $i );

					$element_atts = self::get_node_attributes( $element );

					if ( ! empty( $element_atts['action'] ) ) {

						$element->removeAttribute( 'action' );
						$action = $element_atts['action'];
					}

					if ( ! empty( $element_atts['action-xhr'] ) ) {

						$action = $element_atts['action-xhr'];
					}

					$action_xhr = '';

					if ( $action ) {

						$parsed_action = parse_url( $action );
						if ( ! isset( $parsed_action['schema'] ) && ! empty( $parsed_action['path'] ) ) {

							$action_xhr = $parsed_action['path'];
						} elseif ( isset( $parsed_action['schema'] ) && $parsed_action['schema'] === 'https' ) {

							$action_xhr = $action_xhr;
						} elseif ( $_parsed = self::parse_internal_url( $action ) ) {


							$action_xhr = empty( $_parsed['path'] ) ? '/' : $_parsed['path'];
						} else { // invalid element - cannot detect action

							self::remove_element( $element );
							continue;
						}

					} else {

						$action_xhr = add_query_arg( false, false ); // relative path to current page
					}

					$action_attr_name = 'action-xhr';
					if ( ! isset( $element_atts['method'] ) || strtolower( $element_atts['method'] ) === 'get' ) {
						// Swap action-xr with action on get methods

						$action_attr_name = 'action';
					}

					$element->setAttribute( $action_attr_name, $action_xhr );

					/**
					 * Sanitize target attribute
					 */
					if (
						( isset( $element_atts['target'] ) && ! isset( $valid_target_values[ $element_atts['target'] ] ) )
						||
						! isset( $element_atts['target'] )
					) {

						$element->setAttribute( 'target', '_top' );
					}

					//@todo sanitize input elements
				}
			}


			/**
			 * Replace audio/video tag with amp-audio/video
			 */

			$replaceTags = array(

				'audio' => array(
					'amp-audio',
					'https://cdn.ampproject.org/v0/amp-audio-0.1.js'
				),
				'video' => array(
					'amp-video',
					'https://cdn.ampproject.org/v0/amp-video-0.1.js'
				)

			);
			foreach ( $replaceTags as $tag_name => $tag_info ) {
				$elements = $body->getElementsByTagName( $tag_name );

				if ( $elements->length ) {

					$enqueue = true;

					/**
					 * @var DOMElement $element
					 */
					for ( $i = $elements->length - 1; $i >= 0; $i -- ) {

						$element = $elements->item( $i );

						if ( $element->parentNode->tagName !== 'noscript' ) {

							if ( ! $source = $element->getAttribute( 'src' ) ) {

								if ( ! $source = Better_AMP_HTML_Util::get_child_tag_attribute( $element, 'source', 'src' ) ) {

									$source = Better_AMP_HTML_Util::get_child_tag_attribute( $element, 'a', 'href' );
								}
							}

							if ( empty( $source ) || ! preg_match( '#^\s*https://#', $source ) ) {

								self::remove_element( $element );
								continue;
							}

							$element->setAttribute( 'src', $source );
							Better_AMP_HTML_Util::renameElement( $element, $tag_info[0] );

							if ( $enqueue ) {

								better_amp_enqueue_script( $tag_info[0], $tag_info[1] );
								$enqueue = false;
							}
						}
					}
				}
			}
		}
	}


	/**
	 * parse url if given url was an internal url
	 *
	 * @param string $url
	 *
	 * @todo  check subdirectory
	 *
	 * @since 1.1
	 * @return array
	 */
	public static function parse_internal_url( $url ) {

		static $current_url_parsed;

		if ( ! $current_url_parsed ) {
			$current_url_parsed = parse_url( site_url() );
		}

		$parsed_url = parse_url( $url );

		if ( ! isset( $parsed_url['host'] ) || $parsed_url['host'] === $current_url_parsed['host'] ) {

			return $parsed_url;
		}

		return array();
	}


	/**
	 *
	 * Get attributes of the element
	 *
	 * @param DOMElement $node
	 *
	 * @since 1.1
	 *
	 * @return array key-value paired attributes
	 */
	public static function get_node_attributes( $node ) {

		$attributes = array();

		foreach ( $node->attributes as $attribute ) {
			$attributes[ $attribute->nodeName ] = $attribute->nodeValue;
		}

		return $attributes;
	}


	/**
	 * Sanitize element attribute value
	 *
	 * @see   https://github.com/ampproject/amphtml/blob/master/spec/amp-html-layout.md
	 *
	 * @param string     $layout
	 * @param DOMElement $element
	 * @param array      $element_atts
	 *
	 * @since 1.1
	 */
	protected function sanitize_layout_attribute( $layout, $element, $element_atts ) {

		$atts2remove = array();


		$required_atts = array(
			'width'  => false,
			'height' => false,
		);

		switch ( strtoupper( $layout ) ) {

			case 'FIXED-HEIGHT':

				// The height attribute must be present. The width attribute must not be present or must be equal to auto.
				$required_atts['height'] = true;
				break;

			case 'FIXED':
			case 'RESPONSIVE':

				// The width and height attributes must be present
				$required_atts['width']  = true;
				$required_atts['height'] = true;
				break;


			case 'FILL':
			case 'CONTAINER':
			case 'FLEX-ITEM':
			case 'NODISPLAY':
				//  No validation required!
				break;
		}


		if (
			$required_atts['width'] &&
			( empty( $element_atts['width'] ) || $element_atts['width'] === 'auto' )
		) {
			$atts2remove[] = 'layout';
		}

		if (
			$required_atts['height'] &&
			( empty( $element_atts['height'] ) || $element_atts['height'] === 'auto' )
		) {
			$atts2remove[] = 'layout';
		}


		if ( $atts2remove ) {
			$this->dom->remove_attributes( $element, $atts2remove ); // Remove invalid attributes
		}
	}


	/**
	 * Collect inline element style and print it out in <style amp-custom> tag
	 *
	 * @param DOMElement $node
	 *
	 * @since 1.1
	 */
	public function save_element_style( $node ) {

		$attributes = self::get_node_attributes( $node );

		if ( ! empty( $attributes['style'] ) ) {

			if ( ! empty( $attributes['id'] ) ) {

				$selector = '#' . $attributes['id'];
			} else {

				$class = isset( $attributes['class'] ) ? $attributes['class'] . ' ' : '';
				$class .= 'e_' . mt_rand();
				$node->setAttribute( 'class', $class );

				$selector = preg_replace( '/[ ]+/', '.', '.' . $class );
				$selector .= $selector; // twice for higher CSS priority
			}

			better_amp_add_inline_style( sprintf( '%s{%s}', $selector, $attributes['style'] ) );
		}
	}


	/**
	 * @param array $rule
	 *
	 * @since 1.1
	 * @return array
	 */
	protected function _get_rule_attrs_list( $rule ) {

		$results = array();

		foreach ( $rule['attrs'] as $d ) {

			if ( isset( $d['name'] ) ) {
				$results[ $d['name'] ] = true;
			}
		}

		return $results;
	}


	/**
	 * @param DOMNodeList $elements
	 */
	protected function remove_all_attributes( $elements ) {

		$length = count( $elements );

		for ( $i = 0; $i < $length; $i ++ ) {

			$element = $elements->item( $i );

			$attributes = Better_AMP_HTML_Util::get_node_attributes( $element );
			$this->dom->remove_attributes( $element, array_keys( $attributes ) ); // Remove invalid attributes
		}
	}


	/**
	 * Add none-amp urls to prevent transform to amp version
	 *
	 * accept star* at the end of the url
	 *
	 * @since 1.3.4
	 *
	 * @param string|array $urls
	 */
	public static function set_none_amp_url( $urls ) {

		foreach ( (array) $urls as $url ) {

			$url       = rtrim( $url, '/' );
			$last_char = substr( $url, - 1 );

			if ( $last_char === '*' ) {

				if ( empty( self::$none_amp_urls['start_with'] ) ) {
					self::$none_amp_urls['start_with'] = '';
				} else {
					self::$none_amp_urls['start_with'] .= '|';
				}

				self::$none_amp_urls['start_with'] .= rtrim( $url, '*' );

			} else {

				self::$none_amp_urls['general'][ $url ] = true;
			}
		}
	}
}
