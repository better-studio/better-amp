<?php

add_filter( 'better-framework/panel/better_ads_manager/fields', 'better_amp_better_ad_options', 80 );

if ( ! function_exists( 'better_amp_better_ad_options' ) ) {
	/**
	 * ThemeName ads
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function better_amp_better_ad_options( $fields ) {

		/**
		 *
		 * AMP Ads
		 *
		 */
		$fields['amp_ads'] = array(
			'name'       => __( 'AMP Ads', 'better-studio' ),
			'id'         => 'amp_ads',
			'type'       => 'tab',
			'icon'       => 'bsfi-better-amp',
			'margin-top' => 30,
		);

		$fields[] = array(
			'name'   => __( 'Header Ads', 'better-studio' ),
			'type'   => 'heading',
			'layout' => 'style-2',
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'After Header', 'better-studio' ),
				'group_desc'  => __( '<code>Note:</code> This ad will be shown after header in all AMP pages.', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_header_after',
				'format'      => 'amp',
			)
		);

		$fields[] = array(
			'name'   => __( 'Post Ads', 'better-studio' ),
			'type'   => 'heading',
			'layout' => 'style-2',
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'Before Post Title', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_title_before',
				'format'      => 'amp',
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'After Post Title', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_title_after',
				'format'      => 'amp',
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'Above Post Content', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_content_before',
				'format'      => 'amp',
			)
		);


		/**
		 * AMP Post content ads
		 */
		better_ads_inject_ad_repeater_field_to_fields( $fields, array(
				'id_prefix'        => 'amp_post_inline',
				'group_title'      => __( 'Inside Post Content (After X Paragraph)', 'better-studio' ),
				'field_desc'       => __( 'Add inline adds inside post content. <br>You can add multiple inline adds for multiple location of post content.', 'better-studio' ),
				'field_add_label'  => '<i class="fa fa-plus"></i> ' . __( 'Add New Inline Ad', 'better-studio' ),
				'field_item_title' => __( 'Inline Ad', 'better-studio' ),
				'group_auto_close' => TRUE,
				'format'           => 'amp',
				'field_end_fields' => array(
					'paragraph' => array(
						'name'          => __( 'After Paragraph', 'better-studio' ),
						'id'            => 'paragraph',
						'desc'          => __( 'Content of each post will analyzed and it will inject an ad after the selected number of paragraphs.', 'better-studio' ),
						'input-desc'    => __( 'After how many paragraphs the ad will display.', 'better-studio' ),
						'type'          => 'text',
						'repeater_item' => TRUE,
					)
				)
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'Middle Post Content', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_content_middle',
				'format'      => 'amp',
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'Below Post Content', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_content_after',
				'format'      => 'amp',
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'After Comments & Share Section', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_post_comment_after',
				'format'      => 'amp',
			)
		);


		$fields[] = array(
			'name'   => __( 'Footer Ads', 'better-studio' ),
			'type'   => 'heading',
			'layout' => 'style-2',
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'Footer Ad', 'better-studio' ),
				'group_desc'  => __( '<code>Note:</code> This ad will be shown before footer in all AMP pages.', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_footer_before',
				'format'      => 'amp',
			)
		);

		$fields[] = array(
			'name'   => __( 'Archive Page Ads', 'better-studio' ),
			'type'   => 'heading',
			'layout' => 'style-2',
		);
		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'       => TRUE,
				'group_title' => __( 'After Archive Page Title', 'better-studio' ),
				'group_desc'  => __( '<code>Note:</code> This ad will be shown after archive page title (category,tag...)', 'better-studio' ),
				'group_state' => 'close',
				'id_prefix'   => 'amp_archive_title_after',
				'format'      => 'amp',
			)
		);

		better_ads_inject_ad_field_to_fields(
			$fields,
			array(
				'group'            => TRUE,
				'group_title'      => __( 'After X Posts', 'better-studio' ),
				'group_state'      => 'close',
				'group_auto_close' => FALSE,
				'id_prefix'        => 'amp_archive_after_x',
				'format'           => 'amp',
			)
		);
		$fields['amp_archive_after_x_number'] = array(
			'name'       => __( 'After Each X Posts', 'better-studio' ),
			'id'         => 'amp_archive_after_x_number',
			'desc'       => __( 'Content of each post will analyzed and it will inject an ad after the selected number of paragraphs.', 'better-studio' ),
			'input-desc' => __( 'After how many paragraphs the ad will display.', 'better-studio' ),
			'type'       => 'text',
		);
		$fields[]                             = array(
			'type' => 'group_close',
		);

		return $fields;
	} // better_amp_better_ad_options
}


add_filter( 'better-framework/panel/better_ads_manager/std', 'better_amp_better_ad_std', 33 );

if ( ! function_exists( 'better_amp_better_ad_std' ) ) {
	/**
	 * Ads STD
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function better_amp_better_ad_std( $fields ) {

		$ad_locations = array(
			'amp_post_title_before',
			'amp_post_title_after',
			'amp_post_content_before',
			'amp_post_content_middle',
			'amp_post_content_after',
			'amp_post_comment_after',
			'amp_footer_before',
			'amp_archive_title_after',
			'amp_archive_after_x',
		);

		foreach ( $ad_locations as $location_id ) {
			$fields[ $location_id . '_type' ]     = array(
				'std' => '',
			);
			$fields[ $location_id . '_banner' ]   = array(
				'std' => 'none',
			);
			$fields[ $location_id . '_campaign' ] = array(
				'std' => 'none',
			);
			$fields[ $location_id . '_count' ]    = array(
				'std' => 1,
			);
			$fields[ $location_id . '_columns' ]  = array(
				'std' => 1,
			);
			$fields[ $location_id . '_orderby' ]  = array(
				'std' => 'rand',
			);
			$fields[ $location_id . '_order' ]    = array(
				'std' => 'ASC',
			);
			$fields[ $location_id . '_align' ]    = array(
				'std' => 'center',
			);
		}

		// Post inline
		$fields['amp_post_inline'] = array(
			'default' => array(
				array(
					'type'      => '',
					'campaign'  => 'none',
					'banner'    => 'none',
					'align'     => 'center',
					'paragraph' => 3,
					'count'     => 2,
					'columns'   => 2,
					'orderby'   => 'rand',
					'order'     => 'ASC',
				),
			),
			'std'     => array(
				array(
					'type'      => '',
					'campaign'  => 'none',
					'banner'    => 'none',
					'align'     => 'center',
					'paragraph' => 3,
					'count'     => 2,
					'columns'   => 2,
					'orderby'   => 'rand',
					'order'     => 'ASC',
				),
			),
		);

		return $fields;
	} // better_amp_better_ad_std
}
