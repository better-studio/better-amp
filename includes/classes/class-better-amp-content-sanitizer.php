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
	public static $enable_url_transform = TRUE;

	/**
	 * @since 1.0.0
	 */
	const PATTERN_REL_WP_ATTACHMENT = '#wp-att-([\d]+)#';

	/**
	 * Prepare html content for amp version it removes:
	 * 1) invalid tags
	 * 2) invalid attributes
	 * 3) invalid url protocols
	 *
	 *
	 * @param string $content html content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function sanitize( $content ) {

		$blacklisted_tags       = $this->get_blacklisted_tags();
		$blacklisted_attributes = $this->get_blacklisted_attributes();
		$blacklisted_protocols  = $this->get_blacklisted_protocols();

		$html = new Better_AMP_HTML_Util( $content );
		$node = $html->get_body_node();

		$this->strip_tags( $node, $blacklisted_tags );
		$this->strip_attributes_recursive( $node, $blacklisted_attributes, $blacklisted_protocols );

		return $html->get_content();
	}


	/**
	 * Strips tags from node
	 *
	 * @param $node
	 * @param $tag_names
	 *
	 * @since 1.0.0
	 */
	private function strip_tags( $node, $tag_names ) {

		foreach ( $tag_names as $tag_name ) {

			$elements = $node->getElementsByTagName( $tag_name );
			$length   = $elements->length;

			if ( 0 === $length ) {
				continue;
			}

			for ( $i = $length - 1; $i >= 0; $i -- ) {
				$element     = $elements->item( $i );
				$parent_node = $element->parentNode;
				$parent_node->removeChild( $element );

				if ( 'body' !== $parent_node->nodeName && Better_AMP_HTML_Util::is_node_empty( $parent_node ) ) {
					$parent_node->parentNode->removeChild( $parent_node );
				}
			}
		}

	}


	/**
	 * List of AMP blacklisted tags
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_blacklisted_tags() {
		return array(
			'script',
			'noscript',
			'style',
			'frame',
			'frameset',
			'object',
			'param',
			'applet',
			'form',
			'label',
			'input',
			'textarea',
			'select',
			'option',
			'link',
			'picture',

			'embed',
			'embedvideo',
		);
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
	 * List of blacklisted protocols
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_blacklisted_protocols() {
		return array(
			'javascript',
		);
	}


	/**
	 * Stripes attributes on nodes and childs
	 *
	 * @param DOMElement $node
	 * @param array      $bad_attributes
	 * @param array      $bad_protocols
	 *
	 * @since 1.0.0
	 */
	private function strip_attributes_recursive( $node, $bad_attributes, $bad_protocols ) {

		if ( $node->nodeType !== XML_ELEMENT_NODE ) {
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
				if ( in_array( $attribute_name, $bad_attributes ) ) {
					$node->removeAttribute( $attribute_name );

					continue;
				}

				// on* attributes (like onclick) are a special case
				if ( 0 === stripos( $attribute_name, 'on' ) && $attribute_name != 'on' ) {

					$node->removeAttribute( $attribute_name );

					continue;
				} elseif ( 'a' === $node_name ) {
					$this->sanitize_a_attribute( $node, $attribute );
				}
			}
		}

		$length = $node->childNodes->length;

		for ( $i = $length - 1; $i >= 0; $i -- ) {
			$child_node = $node->childNodes->item( $i );

			$this->strip_attributes_recursive( $child_node, $bad_attributes, $bad_protocols );
		}

		if ( 'font' === $node_name ) {
			$this->replace_node_with_children( $node );
		} elseif ( 'a' === $node_name && FALSE === $this->validate_a_node( $node ) ) {
			$this->replace_node_with_children( $node );
		}
	}


	/**
	 * Sanitize a tags attributes
	 *
	 * @param $node
	 * @param $attribute
	 *
	 * @since 1.0.0
	 */
	private function sanitize_a_attribute( $node, $attribute ) {

		$attribute_name = strtolower( $attribute->name );

		if ( 'rel' === $attribute_name ) {

			$old_value = $attribute->value;
			$new_value = trim( preg_replace( self::PATTERN_REL_WP_ATTACHMENT, '', $old_value ) );

			if ( empty( $new_value ) ) {
				$node->removeAttribute( $attribute_name );
			} elseif ( $old_value !== $new_value ) {
				$node->setAttribute( $attribute_name, $new_value );
			}

		} elseif ( 'rev' === $attribute_name ) {

			// rev removed from HTML5 spec, which was used by Jetpack Markdown.
			$node->removeAttribute( $attribute_name );

		} elseif ( 'target' === $attribute_name ) {

			// _blank is the only allowed value and it must be lowercase.
			// replace _new with _blank and others should simply be removed.
			$old_value = strtolower( $attribute->value );

			if ( '_blank' === $old_value || '_new' === $old_value ) {
				// _new is not allowed; swap with _blank
				$node->setAttribute( $attribute_name, '_blank' );
			} else {
				// only _blank is allowed
				$node->removeAttribute( $attribute_name );
			}
		}
	}


	/**
	 * Validates 'a' tag
	 *
	 * @param $node
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function validate_a_node( $node ) {

		// Get the href attribute
		$href = $node->getAttribute( 'href' );

		// If no href is set and this isn't an anchor, it's invalid
		if ( empty( $href ) ) {
			$name_attr = $node->getAttribute( 'name' );
			if ( ! empty( $name_attr ) ) {
				// No further validation is required
				return TRUE;
			} else {
				return FALSE;
			}
		}

		// If this is an anchor link, just return true
		if ( 0 === strpos( $href, '#' ) ) {
			return TRUE;
		}

		// If the href starts with a '/', append the home_url to it for validation purposes.
		if ( 0 === stripos( $href, '/' ) ) {
			$href = untrailingslashit( get_home_url() ) . $href;
		}

		$valid_protocols = array( 'http', 'https', 'mailto', 'sms', 'tel', 'viber', 'whatsapp' );
		$protocol        = strtok( $href, ':' );
		if ( FALSE === filter_var( $href, FILTER_VALIDATE_URL )
		     || ! in_array( $protocol, $valid_protocols )
		) {
			return FALSE;
		}

		return TRUE;
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
				$new_child = $child_node->cloneNode( TRUE );
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

		if ( FALSE !== filter_var( $value, FILTER_VALIDATE_INT ) ) {
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

		// check is url internal?
		// todo support parked domains
		$sitedomain = str_replace(
			array(
				'http://www.',
				'https://www.',
				'http://',
				'https://',
			),
			'',
			site_url()
		);

		$sitedomain = rtrim( $sitedomain, '/' );

		if ( preg_match( '#^https?://w*\.?' . preg_quote( $sitedomain, '#' ) . '/?([^/]*)/?(.*?)$#', $url, $matched ) ) {

			// if url was not amp
			if ( $matched[1] !== Better_AMP::STARTPOINT ) {

				if ( $matched[1] !== 'wp-content' ) { // do not convert link which is started with wp-content
					if ( $matched[1] ) {
						$matched[0] = '';
						$path       = implode( '/', $matched );
					} else {
						$path = '/';
					}

					return better_amp_site_url( $path );
				}
			}

		}

		return $url;
	}


	/**
	 * Convert amp $url to none-amp version if $url was internal
	 *
	 * @param string $url
	 *
	 * @since 1.0.0
	 *
	 * @return string transformed none-amp url on success or passed $url otherwise.
	 */
	public static function transform_to_none_amp_url( $url ) {

		// check is url internal?
		// todo support parked domains
		$sitedomain = str_replace(
			array(
				'http://www.',
				'https://www.',
				'http://',
				'https://',
			),
			'',
			site_url()
		);

		$sitedomain = rtrim( $sitedomain, '/' );

		if ( preg_match( '#^https?://w*\.?' . preg_quote( $sitedomain, '#' ) . '/?([^/]*)/?(.*?)$#', $url, $matched ) ) {

			// if url was not amp
			if ( $matched[1] === Better_AMP::STARTPOINT ) {

				if ( $matched[1] ) {
					$matched[0] = '';
					unset( $matched[1] );
					$path = implode( '/', $matched );
				} else {
					$path = '/';
				}

				return site_url( $path );
			}

		}

		return $url;
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
}
