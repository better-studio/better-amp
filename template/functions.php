<?php

// Add Ads into BetterAds panel if that was available
if ( better_amp_is_ad_plugin_active() ) {
	better_amp_template_part( 'includes/ads' );
}

add_image_size( 'better-amp-small', 100, 100, array( 'center', 'center' ) );  // Main Post Image In Full Width
add_image_size( 'better-amp-normal', 260, 200, array( 'center', 'center' ) );  // Main Post Image In Full Width
add_image_size( 'better-amp-large', 450, 300, array( 'center', 'center' ) );  // Main Post Image In Full Width

add_theme_support( 'title-tag' );

register_nav_menu( 'amp-sidebar-nav', __( 'AMP Sidebar', 'better-amp' ) );

register_nav_menu( 'better-amp-footer', __( 'AMP Footer Navigation', 'better-amp' ) );


add_action( 'better-amp/template/head', 'better_amp_enqueue_general_styles', 0 );

/**
 * Enqueue static file for amp version
 */
function better_amp_enqueue_general_styles() {

	better_amp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	better_amp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Lato:400,600|Roboto:300,400,500,700' );

	better_amp_enqueue_block_style( 'normalize', 'css/normalize', false ); // Normalize without RTL
	better_amp_enqueue_block_style( 'style', 'style' );

}


add_action( 'better-amp/template/enqueue-scripts', 'better_amp_enqueue_static' );

/**
 * Enqueue static file for amp version
 */
function better_amp_enqueue_static() {

	better_amp_enqueue_script( 'amp-sidebar', 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js' );
	better_amp_enqueue_script( 'amp-sidebar', 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js' );

	if ( better_amp_get_theme_mod( 'better-amp-footer-analytics' ) ) {
		better_amp_enqueue_script( 'amp-analytics', 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js' );
	}
}


add_action( 'better-amp/template/enqueue-scripts', 'better_amp_custom_styles', 100 );

/**
 * Prints custom codes of AMP theme after all styles
 */
function better_amp_custom_styles() {

	$theme_color = better_amp_get_theme_mod( 'better-amp-color-theme', false );

	$text_color = better_amp_get_theme_mod( 'better-amp-color-text', false );

	ob_start();

	?>

	/*
	* => Theme Color
	*/
	.listing-item a.post-read-more:hover,
	.post-terms.cats .term-type,
	.post-terms a:hover,
	.search-form .search-submit,
	.better-amp-main-link a,
	.sidebar-brand,
	.site-header{
	background:<?php echo $theme_color ?>;
	}
	.single-post .post-meta a,
	.entry-content ul.bs-shortcode-list li:before,
	a{
	color: <?php echo $theme_color ?>;
	}


	/*
	* => Other Colors
	*/
	body.body {
	background:<?php echo better_amp_get_theme_mod( 'better-amp-color-bg', false ) ?>;
	}
	.better-amp-wrapper {
	background:<?php echo better_amp_get_theme_mod( 'better-amp-color-content-bg', false ) ?>;
	color: <?php echo $text_color ?>;
	}
	.better-amp-footer {
	background:<?php echo better_amp_get_theme_mod( 'better-amp-color-footer-bg', false ) ?>;
	}
	.better-amp-footer-nav {
	background:<?php echo better_amp_get_theme_mod( 'better-amp-color-footer-nav-bg', false ) ?>;
	}

	<?php

	better_amp_add_inline_style( ob_get_clean(), 'theme_panel_color_fields' );

	better_amp_add_inline_style( better_amp_get_theme_mod( 'better-amp-additional-css', false ), 'custom_codes_from_panel' );

}


function better_amp_get_default_theme_setting( $setting_id, $setting_index = '' ) {

	$settings = array(
		'logo'                                     => array(
			'height'      => 40,
			'width'       => 230,
			'flex-height' => false,
			'flex-width'  => true,
		),
		'sidebar-logo'                             => array(
			'height'      => 150,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		),
		//
		'better-amp-header-logo-img'               => '',
		'better-amp-header-logo-text'              => '',
		'better-amp-header-show-search'            => true,
		'better-amp-header-sticky'                 => true,
		//
		'better-amp-sidebar-show'                  => true,
		'better-amp-sidebar-logo-text'             => '',
		'better-amp-sidebar-logo-img'              => '',
		'better-amp-facebook'                      => '#',
		'better-amp-twitter'                       => '#',
		'better-amp-google_plus'                   => '#',
		'better-amp-email'                         => '#',
		'better-amp-sidebar-footer-text'           => '',
		//
		'better-amp-footer-copyright-show'         => false,
		'better-amp-footer-copyright-text'         => 'Powered by <a href="https://betterstudio.com/wp-plugins/better-amp/" target="_blank">BetterAMP</a>',
		'better-amp-footer-main-link'              => true,
		//
		'better-amp-archive-listing'               => 'listing-1',
		//
		'better-amp-post-show-thumbnail'           => true,
		'better-amp-post-show-comment'             => true,
		'better-amp-post-show-related'             => true,
		'better-amp-post-related-algorithm'        => 'cat',
		'better-amp-post-related-count'            => 7,
		'better-amp-post-social-share-show'        => 'show',
		'better-amp-page-social-share-show'        => 'show',
		'better-amp-post-social-share-count'       => 'total',
		'better-amp-post-social-share-link-format' => 'standard',
		'better-amp-post-social-share'             => array(
			'facebook'    => 1,
			'twitter'     => 1,
			'reddit'      => 1,
			'google_plus' => 1,
			'email'       => 1,
			'pinterest'   => 0,
			'linkedin'    => 0,
			'tumblr'      => 0,
			'telegram'    => 0,
			'vk'          => 0,
			'whatsapp'    => 0,
			'stumbleupon' => 0,
			'digg'        => 0,
		),
		//
		'better-amp-home-show-slide'               => '1',
		'better-amp-home-listing'                  => 'default',
		//
		'better-amp-color-theme'                   => '#0379c4',
		'better-amp-color-bg'                      => '#e8e8e8',
		'better-amp-color-content-bg'              => '#ffffff',
		'better-amp-color-footer-bg'               => '#f3f3f3',
		'better-amp-color-footer-nav-bg'           => '#ffffff',
		'better-amp-color-text'                    => '#363636',
		//
		'better-amp-footer-analytics'              => '',
		'better-amp-additional-css'                => '',
		'better-amp-featured-va-key'               => '_featured_embed_code',
		//
		'better-amp-show-on-front'                 => 'posts',
		'better-amp-page-on-front'                 => 0,
		//
		'better-amp-exclude-urls'                  => '',
		//
		'better-amp-code-head'                     => '',
		'better-amp-code-body-start'               => '',
		'better-amp-code-body-stop'                => '',
		//
		'better-amp-mobile-auto-redirect'          => 0,
		//
		'better-amp-on-home'                       => true,
		'better-amp-on-search'                     => true,
		'better-amp-url-struct'                    => 'start-point',
		'better-amp-excluded-url-struct'           => '',
	);

	if ( $setting_index ) {
		if ( isset( $settings[ $setting_id ][ $setting_index ] ) ) {
			return $settings[ $setting_id ][ $setting_index ];
		}
	} else {
		if ( isset( $settings[ $setting_id ] ) ) {
			return $settings[ $setting_id ];
		}
	}
}


include BETTER_AMP_PATH . 'template/customizer/customizer.php';

function better_amp_default_theme_logo() {

	ob_start();
	$site_branding = better_amp_get_branding_info();
	?>
	<a href="<?php echo esc_attr( better_amp_site_url() ); ?>"
	   class="branding <?php echo ! empty( $site_branding['logo-tag'] ) ? 'image-logo' : 'text-logo'; ?> ">
		<?php

		if ( ! empty( $site_branding['logo-tag'] ) ) {
			echo $site_branding['logo-tag']; // escaped before
		} else {
			echo $site_branding['name']; // escaped before
		}

		?>
	</a>
	<?php

	return ob_get_clean();
}

function better_amp_default_theme_sidebar_logo() {

	ob_start();
	$site_branding = better_amp_get_branding_info( 'sidebar' );
	?>
	<a href="<?php echo esc_attr( better_amp_site_url() ); ?>"
	   class="branding <?php echo ! empty( $site_branding['logo-tag'] ) ? 'image-logo' : 'text-logo'; ?> ">
		<?php

		if ( ! empty( $site_branding['logo-tag'] ) ) {
			echo $site_branding['logo-tag']; // escaped before
		} else {
			echo $site_branding['name']; // escaped before
		}

		?>
	</a>
	<?php

	return ob_get_clean();
}


if ( ! function_exists( 'better_amp_page_listing' ) ) {
	/**
	 * Detects and returns current page listing style
	 *
	 * @return string
	 */
	function better_amp_page_listing() {

		static $listing;

		if ( $listing ) {
			return $listing;
		}

		$listing = 'default';

		if ( is_home() ) {
			$listing = better_amp_get_theme_mod( 'better-amp-home-listing' );
		}

		if ( empty( $listing ) || $listing === 'default' ) {
			$listing = better_amp_get_theme_mod( 'better-amp-archive-listing' );
		}

		return $listing;
	}
}


add_filter( 'better-amp/translation/fields', 'better_amp_translation_fields' );

if ( ! function_exists( 'better_amp_translation_fields' ) ) {
	/**
	 * Adds translation fields into panel
	 *
	 * @param array $fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_translation_fields( $fields = array() ) {

		$fields['prev']                     = array(
			'id'      => 'prev',
			'type'    => 'text',
			'title'   => 'Previous',
			'default' => 'Previous'
		);
		$fields['next']                     = array(
			'id'      => 'next',
			'type'    => 'text',
			'title'   => 'Next',
			'default' => 'Next'
		);
		$fields['page']                     = array(
			'id'      => 'page',
			'type'    => 'text',
			'title'   => 'Page',
			'default' => 'Page'
		);
		$fields['page_of']                  = array(
			'id'       => 'page_of',
			'type'     => 'text',
			'title'    => 'of %d',
			'default'  => 'of %d',
			'subtitle' => __( '%d will be replace with page number.', 'better-amp' ),
		);
		$fields['by_on']                    = array(
			'id'       => 'by_on',
			'type'     => 'text',
			'title'    => 'By %s1 on %s2',
			'default'  => 'By %s1 on %s2',
			'subtitle' => __( '%s1 is author name and %s2 is post publish date.', 'better-amp' ),

		);
		$fields['browse_author_articles']   = array(
			'id'      => 'browse_author_articles',
			'type'    => 'text',
			'title'   => 'Browse Author Articles',
			'default' => 'Browse Author Articles',
		);
		$fields['comments']                 = array(
			'id'      => 'comments',
			'type'    => 'text',
			'title'   => 'Comments',
			'default' => 'Comments',
		);
		$fields['add_comment']              = array(
			'id'      => 'add_comment',
			'type'    => 'text',
			'title'   => 'Add Comment',
			'default' => 'Add Comment',
		);
		$fields['share']                    = array(
			'id'      => 'share',
			'type'    => 'text',
			'title'   => 'Share',
			'default' => 'Share',
		);
		$fields['view_desktop']             = array(
			'id'      => 'view_desktop',
			'type'    => 'text',
			'title'   => 'View Desktop Version',
			'default' => 'View Desktop Version',
		);
		$fields['read_more']                = array(
			'id'      => 'read_more',
			'type'    => 'text',
			'title'   => 'Read more',
			'default' => 'Read more',
		);
		$fields['listing_2_date']           = array(
			'id'      => 'listing_2_date',
			'type'    => 'text',
			'title'   => 'Large Listing Date Format',
			'default' => 'M d, Y',
		);
		$fields['search_on_site']           = array(
			'id'      => 'search_on_site',
			'type'    => 'text',
			'title'   => 'Search on site:',
			'default' => 'Search on site:',
		);
		$fields['search_input_placeholder'] = array(
			'id'      => 'search_input_placeholder',
			'type'    => 'text',
			'title'   => 'Search input placeholder',
			'default' => 'Search &hellip;',
		);
		$fields['search_button']            = array(
			'id'      => 'search_button',
			'type'    => 'text',
			'title'   => 'Search button',
			'default' => 'Search',
		);
		$fields['header']                   = array(
			'id'      => 'header',
			'type'    => 'text',
			'title'   => 'Header',
			'default' => 'Header',
		);
		$fields['tags']                     = array(
			'id'      => 'tags',
			'type'    => 'text',
			'title'   => 'Tags:',
			'default' => 'Tags:',
		);
		$fields['mr_404']                   = array(
			'id'      => 'mr_404',
			'type'    => 'text',
			'title'   => '404 Page Message',
			'default' => 'Oops! That page cannot be found.',
		);

		$fields['browsing']                  = array(
			'id'      => 'browsing',
			'type'    => 'text',
			'title'   => 'Browsing',
			'default' => 'Browsing',
		);
		$fields['archive']                   = array(
			'id'      => 'archive',
			'type'    => 'text',
			'title'   => 'Archive',
			'default' => 'Archive',
		);
		$fields['browsing_category']         = array(
			'id'      => 'browsing_category',
			'type'    => 'text',
			'title'   => 'Browsing category',
			'default' => 'Browsing category',
		);
		$fields['browsing_tag']              = array(
			'id'      => 'browsing_tag',
			'type'    => 'text',
			'title'   => 'Browsing tag',
			'default' => 'Browsing tag',
		);
		$fields['browsing_author']           = array(
			'id'      => 'browsing_author',
			'type'    => 'text',
			'title'   => 'Browsing author',
			'default' => 'Browsing author',
		);
		$fields['browsing_yearly']           = array(
			'id'      => 'browsing_yearly',
			'type'    => 'text',
			'title'   => 'Browsing yearly archive',
			'default' => 'Browsing yearly archive',
		);
		$fields['browsing_monthly']          = array(
			'id'      => 'browsing_monthly',
			'type'    => 'text',
			'title'   => 'Browsing monthly archive',
			'default' => 'Browsing monthly archive',
		);
		$fields['browsing_daily']            = array(
			'id'      => 'browsing_daily',
			'type'    => 'text',
			'title'   => 'Browsing daily archive',
			'default' => 'Browsing daily archive',
		);
		$fields['browsing_archive']          = array(
			'id'      => 'browsing_archive',
			'type'    => 'text',
			'title'   => 'Browsing archive',
			'default' => 'Browsing archive',
		);
		$fields['browsing_product_category'] = array(
			'id'      => 'browsing_product_category',
			'type'    => 'text',
			'title'   => 'Browsing shop category',
			'default' => 'Browsing shop category',
		);
		$fields['browsing_product_tag']      = array(
			'id'      => 'browsing_product_tag',
			'type'    => 'text',
			'title'   => 'Browsing shop tag',
			'default' => 'Browsing shop tag',
		);
		$fields['related_posts']             = array(
			'id'      => 'related_posts',
			'type'    => 'text',
			'title'   => 'Related Posts',
			'default' => 'Related Posts',
		);

		/**
		 * Comments Texts
		 */
		$fields['comments_edit']        = array(
			'id'      => 'comments_edit',
			'type'    => 'text',
			'title'   => 'Edit Comment',
			'default' => 'Edit',
		);
		$fields['comments_reply']       = array(
			'id'      => 'comments_reply',
			'type'    => 'text',
			'title'   => 'Reply',
			'default' => 'Reply',
		);
		$fields['comments_reply_to']    = array(
			'id'      => 'comments_reply_to',
			'type'    => 'text',
			'title'   => 'Reply To %s',
			'default' => 'Reply To %s',
		);
		$fields['comments']             = array(
			'id'      => 'comments',
			'type'    => 'text',
			'title'   => 'Comments',
			'default' => 'Comments',
		);
		$fields['comment_previous']     = array(
			'id'      => 'comment_previous',
			'type'    => 'text',
			'title'   => 'Previous',
			'default' => 'Previous',
		);
		$fields['comment_next']         = array(
			'id'      => 'comment_next',
			'type'    => 'text',
			'title'   => 'Next',
			'default' => 'Next',
		);
		$fields['comment_page_numbers'] = array(
			'id'      => 'comment_page_numbers',
			'type'    => 'text',
			'title'   => 'Page %1$s of %2$s',
			'default' => 'Page %1$s of %2$s',
		);


		$fields['asides']    = array(
			'id'      => 'asides',
			'type'    => 'text',
			'title'   => 'Asides',
			'default' => 'Asides',
		);
		$fields['galleries'] = array(
			'id'      => 'galleries',
			'type'    => 'text',
			'title'   => 'Galleries',
			'default' => 'Galleries',
		);
		$fields['images']    = array(
			'id'      => 'images',
			'type'    => 'text',
			'title'   => 'Images',
			'default' => 'Images',
		);
		$fields['videos']    = array(
			'id'      => 'videos',
			'type'    => 'text',
			'title'   => 'Videos',
			'default' => 'Videos',
		);
		$fields['quotes']    = array(
			'id'      => 'quotes',
			'type'    => 'text',
			'title'   => 'Quotes',
			'default' => 'Quotes',
		);
		$fields['links']     = array(
			'id'      => 'links',
			'type'    => 'text',
			'title'   => 'Links',
			'default' => 'Links',
		);
		$fields['statuses']  = array(
			'id'      => 'statuses',
			'type'    => 'text',
			'title'   => 'Statuses',
			'default' => 'Statuses',
		);
		$fields['audio']     = array(
			'id'      => 'audio',
			'type'    => 'text',
			'title'   => 'Audio',
			'default' => 'Audio',
		);
		$fields['chats']     = array(
			'id'      => 'chats',
			'type'    => 'text',
			'title'   => 'Chats',
			'default' => 'Chats',
		);

		/**
		 * Attachment Texts
		 */
		$fields['attachment-return-to']     = array(
			'id'      => 'attachment-return-to',
			'type'    => 'text',
			'title'   => 'Return to post',
			'default' => 'Return to "%s"',
		);
		$fields['click-here']               = array(
			'id'      => 'click-here',
			'type'    => 'text',
			'title'   => 'Click here',
			'default' => 'Click here',
		);
		$fields['attachment-play-video']    = array(
			'id'      => 'attachment-play-video',
			'type'    => 'text',
			'title'   => 'Play Video',
			'default' => '%s to play video',
		);
		$fields['attachment-play-audio']    = array(
			'id'      => 'attachment-play-audio',
			'type'    => 'text',
			'title'   => 'Play Audio',
			'default' => '%s to play audio',
		);
		$fields['attachment-download-file'] = array(
			'id'      => 'attachment-download-file',
			'type'    => 'text',
			'title'   => 'Download File',
			'default' => '%s to Download File',
		);
		$fields['attachment-next']          = array(
			'id'      => 'attachment-next',
			'type'    => 'text',
			'title'   => 'Next  Attachment',
			'default' => 'Next',
		);
		$fields['attachment-prev']          = array(
			'id'      => 'attachment-prev',
			'type'    => 'text',
			'title'   => 'Previous  Attachment',
			'default' => 'Previous',
		);

		/**
		 * WooCommerce Texts
		 */
		$fields['product-shop']    = array(
			'id'      => 'product-shop',
			'type'    => 'text',
			'title'   => 'Shop',
			'default' => 'Shop',
		);
		$fields['product-desc']    = array(
			'id'      => 'product-desc',
			'type'    => 'text',
			'title'   => 'Product Description',
			'default' => 'Description',
		);
		$fields['product-reviews'] = array(
			'id'      => 'product-reviews',
			'type'    => 'text',
			'title'   => 'Product Reviews',
			'default' => 'Reviews(%s)',
		);
		$fields['product-view']    = array(
			'id'      => 'product-view',
			'type'    => 'text',
			'title'   => 'View',
			'default' => 'View',
		);
		$fields['product-sale']    = array(
			'id'      => 'product-sale',
			'type'    => 'text',
			'title'   => 'Sale!',
			'default' => 'Sale!',
		);

		return $fields;

	} // better_amp_translation_fields
}


add_filter( 'better-amp/translation/std', 'better_amp_translation_stds' );

if ( ! function_exists( 'better_amp_translation_stds' ) ) {
	/**
	 * Prepares translation default values
	 *
	 * @param array $std
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_translation_stds( $std = array() ) {

		$std['prev']                     = 'Previous';
		$std['next']                     = 'Next';
		$std['page']                     = 'Page';
		$std['page_of']                  = 'of %d';
		$std['by_on']                    = 'By %s1 on %s2';
		$std['browse_author_articles']   = 'Browse Author Articles';
		$std['add_comment']              = 'Add Comment';
		$std['share']                    = 'Share';
		$std['header']                   = 'Header';
		$std['tags']                     = 'Tags:';
		$std['mr_404']                   = 'Oops! That page cannot be found.';
		$std['view_desktop']             = 'View Desktop Version';
		$std['read_more']                = 'Read more';
		$std['listing_2_date']           = 'M d, Y';
		$std['search_on_site']           = 'Search on site:';
		$std['search_input_placeholder'] = 'Search &hellip;';
		$std['search_button']            = 'Search';

		$std['browsing']                  = 'Browsing';
		$std['archive']                   = 'Archive';
		$std['browsing_category']         = 'Browsing category';
		$std['browsing_tag']              = 'Browsing tag';
		$std['browsing_author']           = 'Browsing author';
		$std['browsing_yearly']           = 'Browsing yearly archive';
		$std['browsing_monthly']          = 'Browsing monthly archive';
		$std['browsing_daily']            = 'Browsing daily archive';
		$std['browsing_product_category'] = 'Browsing shop category';
		$std['browsing_product_tag']      = 'Browsing shop tag';
		$std['related_posts']             = 'Related Posts';

		$std['asides']    = 'Asides';
		$std['galleries'] = 'Galleries';
		$std['images']    = 'Images';
		$std['videos']    = 'Videos';
		$std['quotes']    = 'Quotes';
		$std['links']     = 'Links';
		$std['statuses']  = 'Statuses';
		$std['audio']     = 'Audio';
		$std['chats']     = 'Chats';


		/**
		 * Comments Texts
		 */

		$std['comments_edit']        = 'Edit';
		$std['comments_reply']       = 'Reply';
		$std['comments_reply_to']    = 'Reply To %s';
		$std['comments']             = 'Comments';
		$std['comment_previous']     = 'Previous';
		$std['comment_next']         = 'Next';
		$std['comment_page_numbers'] = 'Page %1$s of %2$s';

		/**
		 * Attachment Texts
		 */
		$std['attachment-return-to'] = 'Return to "%s"';
		// todo change this id
		$std['click-here']               = 'Click here';
		$std['attachment-play-video']    = '%s to play video';
		$std['attachment-play-audio']    = '%s to play audio';
		$std['attachment-download-file'] = '%s to Download File';
		$std['attachment-prev']          = 'Previous';
		$std['attachment-next']          = 'Next';


		/**
		 * WooCommerce Texts
		 */
		$std['product-shop']    = 'Shop';
		$std['product-desc']    = 'Description';
		$std['product-reviews'] = 'Reviews(%s)';
		$std['product-view']    = 'View';
		$std['product-sale']    = 'Sale!';

		return $std;

	} // better_amp_translation_stds
}

if ( ! function_exists( 'better_amp_auto_embed_content' ) ) {
	/**
	 * Filter Callback: Auto-embed using a link
	 *
	 * @param string $content
	 *
	 * @since 1.2.1
	 * @return string
	 */
	function better_amp_auto_embed_content( $content ) {

		if ( ! is_string( $content ) ) {

			return array(
				'type'    => 'unknown',
				'content' => '',
			);
		}

		//
		// Custom External Videos
		//
		preg_match( '#^(http|https)://.+\.(mp4|m4v|webm|ogv|wmv|flv)$#i', $content, $matches );
		if ( ! empty( $matches[0] ) ) {
			return array(
				'type'    => 'external-video',
				'content' => do_shortcode( '[video src="' . $matches[0] . '"]' ),
			);
		}


		//
		// Custom External Audio
		//
		preg_match( '#^(http|https)://.+\.(mp3|m4a|ogg|wav|wma)$#i', $content, $matches );
		if ( ! empty( $matches[0] ) ) {
			return array(
				'type'    => 'external-audio',
				'content' => do_shortcode( '[audio src="' . $matches[0] . '"]' ),
			);
		}


		//
		// Default embeds and other registered
		//

		global $wp_embed;

		if ( ! is_object( $wp_embed ) ) {
			return array(
				'type'    => 'unknown',
				'content' => $content,
			);
		}

		$embed = $wp_embed->autoembed( $content );

		if ( $embed !== $content ) {
			return array(
				'type'    => 'embed',
				'content' => $embed,
			);
		}

		// No embed detected!
		return array(
			'type'    => 'unknown',
			'content' => $content,
		);
	}
}

add_filter( 'better-amp/template/show-on-front', 'better_amp_set_show_on_front' );

if ( ! function_exists( 'better_amp_set_show_on_front' ) ) {

	/**
	 * Setup show on front option value
	 *
	 * @since 1.2.4
	 * @return bool|string
	 */
	function better_amp_set_show_on_front() {

		return better_amp_get_theme_mod( 'better-amp-show-on-front' );
	}
}

add_filter( 'better-amp/template/page-on-front', 'better_amp_set_page_on_front' );

if ( ! function_exists( 'better_amp_set_page_on_front' ) ) {

	/**
	 * Setup page on front option value
	 *
	 * @since 1.2.4
	 * @return bool|string
	 */
	function better_amp_set_page_on_front() {

		return better_amp_get_theme_mod( 'better-amp-page-on-front' );
	}
}

if ( is_better_amp() ) {

	if ( $exclude_urls = better_amp_get_theme_mod( 'better-amp-exclude-urls' ) ) {
		Better_AMP_Content_Sanitizer::set_none_amp_url( explode( "\n", $exclude_urls ) );
	}
}


add_action( 'better-amp/template/head', 'better_amp_custom_code_head' );

/**
 * Prints custom codes inside head tag
 *
 * @hooked better-amp/template/head
 */
function better_amp_custom_code_head() {

	echo better_amp_get_option( 'better-amp-code-head', false );
}


add_action( 'better-amp/template/body/start', 'better_amp_custom_code_body_start' );

/**
 * Prints custom codes right after body tag start
 *
 * @hooked better-amp/template/body/start
 */
function better_amp_custom_code_body_start() {

	echo better_amp_get_option( 'better-amp-code-body-start', false );
}


add_action( 'better-amp/template/footer', 'better_amp_custom_code_body_stop' );

/**
 * Prints custom codes before body tag close
 *
 * @hooked better-amp/template/footer
 */
function better_amp_custom_code_body_stop() {

	echo better_amp_get_option( 'better-amp-code-body-stop', false );
}


add_filter( 'better-amp/template/auto-redirect', 'better_amp_auto_redirect_mobiles' );

if ( ! function_exists( 'better_amp_auto_redirect_mobiles' ) ) {

	/**
	 * Trigger Auto Redirect Option
	 *
	 * @since 1.2.4
	 * @return bool true if active
	 */
	function better_amp_auto_redirect_mobiles() {

		return better_amp_get_theme_mod( 'better-amp-mobile-auto-redirect' );
	}
}


if ( ! function_exists( 'better_amp_list_post_types' ) ) {

	/**
	 * List available and public post types.
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function better_amp_list_post_types() {

		$results = array(
			__( '- none -', 'better-amp' ),
		);

		foreach (
			get_post_types( array(
				'public'             => true,
				'publicly_queryable' => true
			) ) as $post_type => $_
		) {

			if ( ! $post_type_object = get_post_type_object( $post_type ) ) {
				continue;
			}

			$results[ $post_type ] = $post_type_object->label;
		}

		return $results;
	}
}


if ( ! function_exists( 'better_amp_list_taxonomies' ) ) {

	/**
	 * List available and public taxonomies.
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function better_amp_list_taxonomies() {

		$results    = array(
			__( '- none -', 'better-amp' ),
		);
		$taxonomies = get_taxonomies( array( 'public' => true, ) );
		unset( $taxonomies['post_format'] );

		foreach ( $taxonomies as $id => $_ ) {

			if ( $object = get_taxonomy( $id ) ) {

				$results[ $id ] = $object->label;
			}
		}

		return $results;
	}
}

add_filter( 'better-amp/filter/config', 'better_amp_filter_config' );

if ( ! function_exists( 'better_amp_filter_config' ) ) {

	/**
	 * @param array $filters
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function better_amp_filter_config( $filters ) {

		$filters['disabled_post_types'] = (array) better_amp_get_theme_mod( 'better-amp-filter-post-types' );
		$filters['disabled_taxonomies'] = (array) better_amp_get_theme_mod( 'better-amp-filter-taxonomies' );
		$filters['disabled_homepage']   = ! better_amp_get_theme_mod( 'better-amp-on-home' );
		$filters['disabled_search']     = ! better_amp_get_theme_mod( 'better-amp-on-search' );

		return $filters;
	}
}


add_filter( 'better-amp/url/format', 'better_amp_set_url_format' );

if ( ! function_exists( 'better_amp_set_url_format' ) ) {

	/**
	 * Set default amp url structure.
	 *
	 * @hooked better-amp/url/format
	 *
	 * @param string $default
	 *
	 * @since  1.9.0
	 * @return string
	 */
	function better_amp_set_url_format( $default ) {

		return better_amp_get_option( 'better-amp-url-struct', $default );
	}
}


add_filter( 'better-amp/url/excluded', 'better_amp_set_excluded_url_format' );

if ( ! function_exists( 'better_amp_set_excluded_url_format' ) ) {

	/**
	 * Set the urls list which is not available in AMP version.
	 *
	 * @hooked better-amp/url/excluded
	 *
	 * @param array $default
	 *
	 * @since  1.9.8
	 * @return array
	 */
	function better_amp_set_excluded_url_format( $default ) {

		if ( $excluded = trim( better_amp_get_option( 'better-amp-excluded-url-struct', '' ) ) ) {

			return explode( "\n", $excluded );
		}

		return $default;
	}
}


add_filter( 'the_content', 'better_amp_do_block_styles', 2 );

if ( ! function_exists( 'better_amp_do_block_styles' ) ) {

	/**
	 * Enqueue gutenberg block styles.
	 *
	 * @param string $content
	 *
	 * @since 1.9.6
	 * @return string
	 */
	function better_amp_do_block_styles( $content ) {

		global $wp_query;

		if ( ! is_better_amp() || ! $wp_query || ! $wp_query->is_main_query() ) {

			return $content;
		}

		$blocks_list = array(
			'button',
			'columns',
			'cover',
			'file',
			'gallery',
			'image',
			'latest-comments',
			'list',
			'quote',
			'separator',
			'table',
			'verse',
		);

		if ( preg_match_all(
			'/<!--\s+(?<closer>\/)?wp:(?:<namespace>[a-z][a-z0-9_-]*\/)?(?<name>[a-z][a-z0-9_-]*)\s+(?:<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?<void>\/)?-->/s',
			$content,
			$matches
		) ) {

			foreach ( array_unique( $matches[2] ) as $block ) {

				if ( in_array( $block, $blocks_list ) ) {

					better_amp_enqueue_block_style( 'block-' . $block, 'css/block/' . $block, false );
				}
			}
		}

		return $content;
	}
}

