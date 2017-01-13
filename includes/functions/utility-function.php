<?php

/**
 * Remove anything after question mark
 *
 * Example: betterstudio.com/?publisher=great
 * becomes: betterstudio.com/
 *
 * @param string $string
 *
 * @since 1.0.0
 *
 * @return string
 */
function better_amp_remove_query_string( $string ) {

	if ( preg_match( '/([^\?]+)\?/', $string, $matches ) ) {
		return $matches[1];
	}

	return $string;
}


/**
 * Filter AMP element attributes
 *
 * @param array  $attributes       key-value paired attributes list
 * @param array  $valid_attributes valid attributes key
 * @param string $tag_name         optional. amp tag-name
 *
 * @since 1.0.0
 *
 * @return array filtered attributes
 */
function better_amp_filter_attributes( $attributes, $valid_attributes, $tag_name = '' ) {

	$attributes = wp_array_slice_assoc( $attributes, $valid_attributes );

	return apply_filters( 'better-amp/htmldom/filter-attributes', $attributes, $tag_name, $valid_attributes );
}
