<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: http://themeforest.net/user/Better-Studio/portfolio
 *
 *  \--> BetterStudio, 2017 <--/
 */


/***
 * Library for json-ld support
 *
 * @since 2.10.0
 */
class BF_Json_LD_Generator {


	/**
	 * Configurations
	 *
	 * @var array
	 */
	protected static $config = array(
		'active'         => TRUE,
		'media_field_id' => '_featured_embed_code', // BS Media Meta ID
		'logo'           => '',                     // Logo for organization
	);


	/**
	 * Store json-LD Generator Callback
	 *
	 * @var array
	 */
	protected static $generators = array();


	/**
	 * Global json-ld properties that is need in every data types
	 *
	 * @var array
	 *
	 * @since 2.10.0
	 */
	protected static $global_params = array(
		'@context' => 'http://schema.org',
	);


	/**
	 * Initialize library
	 *
	 * @since 2.10.0
	 */
	public static function init() {

		// Prepare data
		add_action( 'template_redirect', 'BF_Json_LD_Generator::prepare_data' );
	}


	/**
	 * callback: Print json-ld output
	 *
	 * action: wp_head
	 *
	 * @since 2.10.0
	 */
	public static function print_output() {

		foreach ( self::$generators as $generator ) {

			if ( empty( $generator['type'] ) || empty( $generator['callback'] ) || ! is_callable( $generator['callback'] ) ) {
				continue;
			}

			$filter = sprintf( 'better-framework/json-ld/%s/', $generator['type'] );

			if ( ! $data = apply_filters( $filter, call_user_func( $generator['callback'] ) ) ) {
				continue;
			}

			echo '<script type="application/ld+json">', wp_json_encode( $data, JSON_PRETTY_PRINT ), '</script>', PHP_EOL;
		}
	}


	/**
	 * Generate JSON-LD Information
	 *
	 * @since 2.10.0
	 */
	public static function prepare_data() {

		self::$config = apply_filters( 'better-framework/json-ld/config', self::$config );

		if ( empty( self::$config['active'] ) ) {
			return;
		}


		//
		// Organization
		//
		if ( ! empty( self::$config['logo'] ) ) {
			self::$generators[] = array(
				'type'     => 'organization',
				'callback' => array( 'BF_Json_LD_Generator', 'generate_organization_schema' ),
			);
		}


		//
		// Homepage
		//
		if ( is_home() || is_front_page() ) {
			self::$generators[] = array(
				'type'     => 'website',
				'callback' => array( 'BF_Json_LD_Generator', 'generate_website_schema' ),
			);
		}


		//
		// Single Items
		//
		if ( is_singular() && ! is_front_page() ) {

			$type = 'single';

			if ( function_exists( 'is_product' ) && is_product() ) {
				$type = 'product';
			} else if ( is_page() ) {
				$type = 'page';
			}

			$callback = array( 'BF_Json_LD_Generator', sprintf( 'generate_%s_schema', $type ) );

			if ( $type != 'single' && ! is_callable( $callback ) ) {
				$callback = array( 'BF_Json_LD_Generator', sprintf( 'generate_single_schema', $type ) );
			}

			self::$generators[] = array(
				'type'     => 'single',
				'callback' => $callback,
			);
		}


		// Print data
		if ( ! empty( self::$generators ) ) {
			add_action( 'wp_head', 'BF_Json_LD_Generator::print_output' );
			add_action( 'better-amp/template/head', 'BF_Json_LD_Generator::print_output' );
		}

	}


	/**
	 *  Check current single post have review ?
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public static function is_review_active() {

		if ( ! class_exists( 'Better_Reviews' ) ||
		     ! function_exists( 'better_reviews_is_review_active' ) ||
		     ! function_exists( 'better_reviews_get_total_rate' )
		) {
			return FALSE;
		}

		return better_reviews_is_review_active();
	}


	/**
	 * Get the Post Author
	 *
	 * @since 2.10.0
	 * @return string
	 */
	public static function get_the_author() {
		global $post;

		$display_name = get_the_author_meta( 'display_name', $post->post_author );

		if ( $display_name && $display_name !== get_the_author_meta( 'login', $post->post_author ) ) {
			return $display_name;
		}

		return '';
	}


	/**
	 * Escape shortcodes and tags of text
	 *
	 * @param string $text
	 * @param int    $limit
	 *
	 * @return string $text
	 */
	private static function esc_text( $text, $limit = 0 ) {

		$text = strip_tags( $text );

		$text = strip_shortcodes( $text );

		$text = str_replace( array( "\r", "\n" ), '', $text );

		if ( $limit ) {
			return self::substr_text( $text, $limit );
		} else {
			return $text;
		}
	}


	/**
	 * Return a pice of text
	 *
	 * @param string $text
	 * @param int    $length
	 *
	 * @return string $text
	 */
	private static function substr_text( $text = '', $length = 110 ) {

		if ( empty( $text ) ) {
			return $text;
		}

		return mb_substr( $text, 0, $length, 'UTF-8' );
	}


	/**
	 * Generate Organization Schema
	 *
	 * @since 2.10.0
	 * @return array
	 */
	public static function generate_organization_schema() {

		return array(
			"@context"    => "http://schema.org/",
			'@type'       => 'organization',
			'@id'         => '#organization',
			//
			'logo'        => array(
				'@type' => 'ImageObject',
				'url'   => self::$config['logo'],
			),
			'url'         => get_bloginfo( 'url' ),
			'name'        => get_bloginfo( 'name' ),
			'description' => self::esc_text( get_bloginfo( 'description' ) ),
		);
	}


	/**
	 * Generate WebSite Schema
	 *
	 * @since 2.10.0
	 * @return array
	 */
	public static function generate_website_schema() {

		return array(
			"@context"        => "http://schema.org/",
			'@type'           => 'WebSite',
			'@id'             => '#website',
			//
			'url'             => get_bloginfo( 'url' ),
			'name'            => get_bloginfo( 'name' ),
			'description'     => self::esc_text( get_bloginfo( 'description' ) ),
			//
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => get_search_link() . '{search_term}',
				'query-input' => 'required name=search_term'
			),
		);
	}


	/**
	 * Generate WebPage Schema
	 *
	 * @since 2.10.0
	 * @return array
	 */
	public static function generate_page_schema() {
		return self::get_singular_schema( 'WebPage', array( 'add_date' => FALSE ) );
	}


	/**
	 * Generate WooCommerce Schema
	 *
	 * @since 2.10.0
	 * @return array
	 *
	 *
	 * @check http://jsonld.com/product/
	 */
	public static function generate_product_schema() {

		$product = wc_get_product();
		$schema  = self::get_singular_schema( 'Product', FALSE );

		//
		// Change to product to be valid!
		//
		$schema['@type']          = 'Product';
		$schema['name']           = $schema['headline'];
		$schema['brand']          = $schema['publisher'];
		$schema['productionDate'] = $schema['datePublished'];
		unset(
			$schema['headline'],
			$schema['publisher'],
			$schema['dateModified'],
			$schema['datePublished'],
			$schema['author']
		);


		if ( $rating_count = (int) $product->get_rating_count() ) {

			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => wc_format_decimal( $product->get_average_rating(), 2 ),
				'reviewCount' => $rating_count,
			);
		}

		$schema['offers'] = array(
			'@type'         => 'Offer',
			'priceCurrency' => get_woocommerce_currency(),
			'price'         => $product->get_price(),
			'availability'  => 'http://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
		);


		return $schema;
	}


	/**
	 * Generate  Single Post Schema
	 *
	 * @since 2.10.0
	 * @return array
	 */
	public static function generate_single_schema() {
		return self::get_singular_schema( 'BlogPosting' );
	}


	/**
	 * Get Singular Post Schema
	 *
	 * @param string $type
	 * @param array  $args
	 *
	 * @since 2.10.0
	 *
	 * @return array
	 */
	public static function get_singular_schema( $type = 'BlogPosting', $args = array() ) {

		global $post;

		if ( ! isset( $args['add_search'] ) ) {
			$args['add_search'] = TRUE;
		}

		if ( ! isset( $args['add_date'] ) ) {
			$args['add_date'] = TRUE;
		}

		if ( ! isset( $args['add_image'] ) ) {
			$args['add_image'] = TRUE;
		}


		$permalink = get_permalink( $post->ID );

		$schema = array(
			"@context"         => "http://schema.org/",
			'@type'            => $type,
			//
			'url'              => $permalink,
			'headline'         => $post->post_title,
			'publisher'        => array(
				'@id' => '#organization',
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => $permalink,
			),
		);


		//
		// Post excerpt or content
		//
		if ( $post->post_excerpt ) {
			$schema['description'] = $post->post_excerpt;
		} else {
			$schema['description'] = self::esc_text( $post->post_content, 250 );
		}


		//
		// Add date
		//
		if ( $args['add_date'] ) {
			$schema['datePublished'] = get_the_date( 'Y-m-d' );
			$schema['dateModified']  = get_post_modified_time( 'Y-m-d' );

			// No need if it was not modified!
			if ( $schema['datePublished'] == $schema['dateModified'] ) {
				unset( $schema['dateModified'] );
			}
		}


		//
		// Add thumbnail
		//
		if ( $args['add_image'] ) {

			$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

			if ( ! empty( $featured_image[0] ) ) {

				$schema['image'] = array(
					'@type' => 'ImageObject',
					'url'   => $featured_image[0],
				);

				// Add width and height
				if ( ! empty( $featured_image[1] ) && ! empty( $featured_image[2] ) ) {
					$schema['image']['width']  = $featured_image[1];
					$schema['image']['height'] = $featured_image[2];
				}
			}
		}


		//
		// Author
		//
		if ( $author = self::get_the_author() ) {

			$schema['author'] = array(
				'@type' => 'Person',
				'@id'   => '#person-' . $author,
				'name'  => $author,
			);

			$author = sanitize_html_class( $author );

			$schema['author']['@id'] = '#person-' . $author;
		}


		//
		// Change type to advanced format
		//
		if ( 'post' === $post->post_type ) {

			$format = get_post_format();

			switch ( $format ) {

				//
				// Audio type
				//
				case 'audio':
					$schema['@type'] = 'AudioObject';

					// Add media
					if ( $media = get_post_meta( $post->ID, self::$config['media_field_id'], TRUE ) ) {
						$schema['contentUrl'] = $media;
					}

					break;

				//
				// Video type
				//
				case 'video':

					$schema['@type'] = 'VideoObject';

					// Add media
					if ( $media = get_post_meta( $post->ID, self::$config['media_field_id'], TRUE ) ) {
						$schema['contentUrl'] = $media;
					}

					//
					// Change to product to be valid!
					//
					$schema['name']         = $schema['headline'];
					$schema['thumbnailUrl'] = $schema['image'] ? $schema['image']['url'] : '';
					$schema['uploadDate']   = $schema['datePublished'];
					unset(
						$schema['headline'],
						$schema['datePublished'],
						$schema['image']
					);

					break;

				//
				// Image & Gallery type
				//
				case 'image':
				case 'gallery':
					$schema['@type'] = 'ImageObject';
					break;

			}

		} // Image attachment
		elseif ( 'attachment' === $post->post_type && wp_attachment_is_image() ) {
			$schema['@type'] = 'ImageObject';
		} // Audio attachment
		elseif ( 'attachment' === $post->post_type && wp_attachment_is( 'audio' ) ) {
			$schema['@type']      = 'AudioObject';
			$schema['contentUrl'] = wp_get_attachment_url();
		} // Video attachment
		elseif ( 'attachment' === $post->post_type && wp_attachment_is( 'video' ) ) {
			$schema['@type']      = 'VideoObject';
			$schema['contentUrl'] = wp_get_attachment_url();
		}


		//
		// Review
		// todo add more review plugin support
		//
		if ( self::is_review_active() ) {

			$rating_value = better_reviews_get_total_rate();
			$criteria     = get_post_meta( $post->ID, '_bs_review_criteria', TRUE );

			if ( $rating_value && $criteria ) {

				$type = Better_Reviews::get_meta( '_bs_review_rating_type' );

				if ( $type === 'points' ) {
					$worst = 0;
					$best  = 10;
				} else {
					$worst = 0;
					$best  = 100;
				}


				$schema['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => $rating_value,
					'reviewCount' => count( $criteria ),
					'worstRating' => $worst,
					'bestRating'  => $best,
				);


				//
				// Add criteria
				//
				foreach ( (array) $criteria as $_cr ) {

					if ( empty( $_cr['rate'] ) || empty( $_cr['label'] ) ) {
						continue;
					}

					$schema['review'][] = array(
						'@type'        => 'Review',
						'itemReviewed' => array(
							'@id' => $permalink,
						),
						'name'         => $_cr['label'],
						'author'       => array(
							'@id' => '#person-' . $author,
						),
						'reviewRating' => array(
							'@type'       => 'Rating',
							'ratingValue' => $type != 'points' ? $_cr['rate'] * 10 : $_cr['rate'],
							'worstRating' => $worst,
							'bestRating'  => $best,
						),
					);
				}
			}
		}


		//
		// Comments count
		//
		if ( $post->post_type != 'product' && $post->post_type != 'WebPage' && post_type_supports( $post->post_type, 'comments' ) ) {

			$schema['interactionStatistic'][] = array(
				'@type'                => 'InteractionCounter',
				'interactionType'      => 'http://schema.org/CommentAction',
				'userInteractionCount' => get_comments_number( $post ),
			);
		}


		//
		// Add search for pages
		//
		if ( $type === 'WebPage' ) {

			$search_link = get_search_link();
			if ( ! strstr( $search_link, '?' ) ) {
				$search_link = trailingslashit( $search_link );
			}

			$schema['potentialAction']['comments'] = array(
				'@type'       => 'SearchAction',
				'target'      => $search_link . '{search_term}',
				'query-input' => 'required name=search_term'
			);
		}

		return array_filter( $schema );
	}
}

BF_Json_LD_Generator::init();
