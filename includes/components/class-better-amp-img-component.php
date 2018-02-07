<?php

/**
 * amp-img Component
 *
 * @since 1.0.0
 */
class Better_AMP_IMG_Component extends Better_AMP_Component_Base implements Better_AMP_Component_Interface {

	/**
	 * Better_AMP_IMG_Component constructor.
	 *
	 * @since 1.0.0
	 */
	public function head() {
		if ( ! better_amp_is_customize_preview() ) {
			add_filter( 'post_thumbnail_html', array( $this, 'transform_image_tag_to_amp' ) );
			add_filter( 'get_avatar', array( $this, 'transform_image_tag_to_amp' ) );
		}
	}


	/**
	 * Contract implementation
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function config() {

		return array(
			'scripts' => array(
				'amp-anim' => 'https://cdn.ampproject.org/v0/amp-anim-0.1.js'
			)
		);

	} // config


	/**
	 * Transform <img> tags to the <amp-img> or <img-anim> tags
	 *
	 * @param Better_AMP_HTML_Util $instance
	 *
	 * @return Better_AMP_HTML_Util
	 * @since 1.0.0
	 *
	 */
	public function transform( Better_AMP_HTML_Util $instance ) {

		$elements = $instance->getElementsByTagName( 'img' ); // get all img tags

		/**
		 * @var DOMElement $element
		 */
		if ( $nodes_count = $elements->length ) {

			for ( $i = $nodes_count - 1; $i >= 0; $i -- ) {
				$element = $elements->item( $i );

				if ( $this->is_animated_image_element( $element ) ) {

					$this->enable_enqueue_scripts = TRUE;

					$tag_name = 'amp-anim';
				} else {

					$tag_name = 'amp-img';
				}

				$attributes = $instance->filter_attributes( $instance->get_node_attributes( $element ) );
				$attributes = $this->modify_attributes( $attributes );
				$attributes = $this->filter_attributes( $attributes, $tag_name );

				$instance->replace_node( $element, $tag_name, $attributes );
			}
		}


		return $instance;
	}

	/**
	 * Append or modify amp-img|amp-anim attributes
	 *
	 * @param array $attributes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function modify_attributes( $attributes ) {

		if ( ! isset( $attributes['class'] ) ) {
			$attributes['class'] = '';
		}
		$attributes['class'] .= ' amp-image-tag';

		if ( ! isset( $attributes['width'] ) || ! isset( $attributes['height'] ) ) {

			if ( isset( $attributes['src'] ) ) {

				if ( $dim = $this->get_image_dimension( $attributes['src'] ) ) {
					$attributes['width']  = $dim[0];
					$attributes['height'] = $dim[1];
				}
			}
		}

		return $this->enforce_sizes_attribute( $attributes );
	}


	/**
	 * Filter amp-img | amp-anim attributes list
	 *
	 * todo list all valid amp attributes for amp-img & amp-anim
	 *
	 * @param array  $attributes
	 * @param string $tag_name
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function filter_attributes( $attributes, $tag_name ) {

		$valid_atts = array(
			'src',
			'srcset',
			'height',
			'width',
			'class',
			'alt',
			'sizes',
			'on'
		);

		return better_amp_filter_attributes( $attributes, $valid_atts, $tag_name );
	}


	/**
	 * Detect is given <img> element is animation
	 *
	 * @param DOMElement $element <img> element object
	 *
	 * @since 1.0.0
	 *
	 * @return bool true if image was animated or false otherwise
	 */
	protected function is_animated_image_element( $element ) {

		$src = $element->attributes->getNamedItem( 'src' ); // get src attribute

		if ( $src && isset( $src->value ) ) {
			return $this->is_animated_image_url( $src->value );
		}

		$class = $element->attributes->getNamedItem( 'class' );

		if ( $class && isset( $class->value ) ) {
			return preg_match( '/\b animated-img \b/ix', $class->value ); // the image is animated if it has a animated class
		}

		return FALSE;
	}


	/**
	 * Generate amp-image tag of attachment post
	 *
	 * @param WP_Post $attachment attachment post
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function print_attachment_image( $attachment ) {
		return $this->get_attachment_image( $attachment->ID );
	}


	/**
	 * Get an HTML img element representing an image attachment
	 *
	 * todo fix default size
	 *
	 * @see   wp_get_attachment_image for more documentation
	 *
	 * @param int          $attachment_id Image attachment ID.
	 * @param string|array $size          Optional. Image size. Accepts any valid image size, or an array of width
	 *                                    and height values in pixels (in that order). Default 'full'.
	 * @param bool         $icon          Optional. Whether the image should be treated as an icon. Default false.
	 * @param string|array $attr          Optional. Attributes for the image markup. Default empty.
	 *
	 * @since 1.0.0
	 *
	 * @return string HTML img element or empty string on failure.
	 */
	function get_attachment_image( $attachment_id, $size = 'large', $icon = FALSE, $attr = '' ) {

		$html = '';

		$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );

		if ( $image ) {

			list( $src, $width, $height ) = $image;

			$hwstring = image_hwstring( $width, $height );

			$size_class = $size;

			if ( is_array( $size_class ) ) {
				$size_class = join( 'x', $size_class );
			}

			$attachment = get_post( $attachment_id );

			$default_attr = array(
				'src'   => $src,
				'class' => "attachment-$size_class size-$size_class",
				'alt'   => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', TRUE ) ) ),
				// Use Alt field first
			);

			if ( empty( $default_attr['alt'] ) ) {
				$default_attr['alt'] = trim( strip_tags( $attachment->post_excerpt ) );
			} // If not, Use the Caption

			if ( empty( $default_attr['alt'] ) ) {
				$default_attr['alt'] = trim( strip_tags( $attachment->post_title ) );
			} // Finally, use the title

			$attr = wp_parse_args( $attr, $default_attr );

			// Generate 'srcset' and 'sizes' if not already present.
			if ( empty( $attr['srcset'] ) ) {

				$image_meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', TRUE );

				if ( is_array( $image_meta ) ) {
					$size_array = array( absint( $width ), absint( $height ) );
					$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );
					$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );

					if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
						$attr['srcset'] = $srcset;

						if ( empty( $attr['sizes'] ) ) {
							$attr['sizes'] = $sizes;
						}
					}
				}
			}

			/**
			 * Filters the list of attachment image attributes.
			 *
			 * @since 2.8.0
			 *
			 * @param array        $attr       Attributes for the image markup.
			 * @param WP_Post      $attachment Image attachment post.
			 * @param string|array $size       Requested size. Image size or array of width and height values
			 *                                 (in that order). Default 'thumbnail'.
			 */
			$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
			$attr = $this->filter_attributes( $attr, 'amp-img' );
			$attr = array_map( 'esc_attr', $attr );

			$html = rtrim( "<amp-img $hwstring" );

			foreach ( $attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}

			$html .= '></amp-img>';
		}

		return $html;
	} // get_attachment_image


	/**
	 * This is our workaround to enforce max sizing with layout=responsive.
	 *
	 * We want elements to not grow beyond their width and shrink to fill the screen on viewports smaller than their
	 * width.
	 *
	 * See https://github.com/ampproject/amphtml/issues/1280#issuecomment-171533526
	 * See https://github.com/Automattic/amp-wp/issues/101
	 *
	 * @since     1.0.0
	 *
	 * @copyright credit goes to automattic amp - github.com/Automattic/amp-wp
	 */
	public function enforce_sizes_attribute( $attributes ) {

		if ( ! isset( $attributes['width'], $attributes['height'] ) ) {
			return $attributes;
		}

		$max_width = $attributes['width'];

		if ( ( $_max_width = better_amp_get_container_width() ) && $max_width > $_max_width ) {
			$max_width = $_max_width;
		}

		$attributes['sizes'] = sprintf( '(min-width: %1$dpx) %1$dpx, 100vw', absint( $max_width ) );

		return $attributes;
	}


	/**
	 * Fetch remote image dimension
	 *
	 * @param string $url image url
	 *
	 * @see   github.com/tommoor/fastimage
	 * @since 1.0.0
	 * @return bool|array  array of width&height on success or false on error. array {
	 *     0 => image width
	 *     1 => image height
	 *
	 * }
	 *
	 * @since 1.0.0
	 */
	public function fetch_image_dimension( $url ) {

		if ( ! class_exists( 'FastImage' ) ) {
			require BETTER_AMP_INC . '/classes/Fastimage.php';
		}

		$fast_image = new FastImage( $url );

		return $fast_image->getSize();
	}


	/**
	 * Get remote image dimension
	 *
	 * @param string $url
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array array on success or false on error. @see fetch_image_dimension  for more doc
	 */
	public function get_image_dimension( $url ) {

		if ( ! ( $url = $this->normalize_url( $url ) ) ) {
			return FALSE;
		}

		$url_hash = md5( $url );

		if ( ! ( $dimension = get_transient( 'better_amp_dimension_' . $url_hash ) ) ) {
			if ( $dimension = $this->fetch_image_dimension( $url ) ) {
				set_transient( 'better_amp_dimension_' . $url_hash, $dimension, HOUR_IN_SECONDS );
			} else {
				$dimension = array(
					650, // fallback for width
					400, // fallback for height
				);
			}
		}

		return $dimension;
	}


	/**
	 * @param string $url
	 *
	 * @since     1.0.0
	 * @copyright credit goes to automattic amp - github.com/Automattic/amp-wp
	 *
	 * @since     1.0.0
	 *
	 * @return bool|string url string on success
	 */
	public static function normalize_url( $url ) {

		if ( empty( $url ) ) {
			return FALSE;
		}

		if ( 0 === strpos( $url, 'data:' ) ) {
			return FALSE;
		}

		if ( 0 === strpos( $url, '//' ) ) {
			return set_url_scheme( $url, 'http' );
		}

		$parsed = parse_url( $url );

		if ( ! isset( $parsed['host'] ) ) {

			$path = '';

			if ( isset( $parsed['path'] ) ) {
				$path .= $parsed['path'];
			}

			if ( isset( $parsed['query'] ) ) {
				$path .= '?' . $parsed['query'];
			}

			$url = site_url( $path );
		}

		return $url;
	}

	/**
	 * Change <img> tag to <amp-img>
	 *
	 * @since 1.0.0
	 */
	public function transform_image_tag_to_amp( $html ) {
		return preg_replace( '/<\s*img\s+/i', '<amp-img ', $html );
	}


	/**
	 * @param array $image
	 * @param bool  $echo
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function create_image( $image = array(), $echo = FALSE ) {

		if ( empty( $image['src'] ) ) {
			return '';
		}

		$image   = $this->modify_attributes( $image );
		$is_anim = $this->is_animated_image_url( $image['src'] );

		if ( $is_anim ) {

			$this->enable_enqueue_scripts = TRUE;

			$tag_name = 'amp-anim';
		} else {

			$tag_name = 'amp-img';
		}

		$instance = new Better_AMP_HTML_Util();

		$node = $instance->create_node( $tag_name, $image );

		$output = $instance->saveXML( $node, LIBXML_NOEMPTYTAG );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}

	}


	/**
	 * Handy function to check image url is animated image or not
	 *
	 * @param string $url
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_animated_image_url( $url = '' ) {

		$url = better_amp_remove_query_string( $url );

		return strtolower( substr( $url, - 4 ) ) === '.gif';
	}
}

// Register component class
better_amp_register_component( 'Better_AMP_IMG_Component' );

if ( ! function_exists( 'better_amp_create_image' ) ) {
	/**
	 * Print AMP image from url
	 *
	 *
	 * @param      $image
	 * @param bool $echo
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_create_image( $image, $echo = TRUE ) {

		/**
		 * @var Better_AMP_IMG_Component $img_component
		 */
		$img_component = Better_AMP_Component::instance( 'Better_AMP_IMG_Component' );

		if ( $echo ) {
			echo $img_component->create_image( $image );
		} else {
			return $img_component->create_image( $image );
		}

	}
}
