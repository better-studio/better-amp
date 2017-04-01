<?php
/***
 *  BetterAMP factions to show ads in AMP.
 *  This feature is depended to our Ads Manager plugin (Better Ads v1.8)
 *  For more information about BetterAds you can contact us info@betterstudio.com
 */


if ( ! function_exists( 'better_amp_is_ad_plugin_active' ) ) {
	/**
	 * Detect the "Better Ads Manager" v1.8.0 is active or not
	 *
	 * @return bool
	 */
	function better_amp_is_ad_plugin_active() {

		static $state;

		if ( ! is_null( $state ) ) {
			return $state;
		}

		$state = class_exists( 'Better_Ads_Manager' ) && ( defined( 'BETTER_ADS_MANAGER_AMP' ) && BETTER_ADS_MANAGER_AMP );

		// Min BetterAds v1.9
		if ( $state && ! function_exists( 'better_ads_inject_ad_repeater_field_to_fields' ) ) {
			$state = FALSE;
		}

		return $state;
	}
}


if ( ! function_exists( 'better_amp_get_ad_location_data' ) ) {
	/**
	 * Return data of Ad location by its ID prefix
	 *
	 * @param string $ad_location_prefix
	 *
	 * @return array
	 */
	function better_amp_get_ad_location_data( $ad_location_prefix = '' ) {

		if ( ! better_amp_is_ad_plugin_active() ) {
			return array(
				'format'          => '',
				'type'            => '',
				'banner'          => '',
				'campaign'        => '',
				'active_location' => '',
			);
		}

		return better_ads_get_ad_location_data( $ad_location_prefix );
	}
}


if ( ! function_exists( 'better_amp_show_ad_location' ) ) {
	/**
	 * Return data of Ad location by its ID prefix
	 *
	 * @param string $ad_location_prefix
	 *
	 * @param array  $args
	 *
	 * @return array
	 */
	function better_amp_show_ad_location( $ad_location_prefix = '', $args = array() ) {

		if ( ! better_amp_is_ad_plugin_active() ) {
			return;
		}

		$ad_data = better_ads_get_ad_location_data( $ad_location_prefix );

		if ( ! $ad_data['active_location'] ) {
			return;
		}

		better_ads_show_ad_location( $ad_location_prefix, $ad_data, $args );
	}
}
