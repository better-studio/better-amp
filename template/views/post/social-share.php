<?php

if ( better_amp_get_theme_mod( 'better-amp-post-social-share-show' ) !== 'show' ) {
	return;
}

better_amp_enqueue_block_style( 'social-list' );

$in_customizer       = is_customize_preview();
$count_status        = better_amp_get_theme_mod( 'better-amp-post-social-share-count' );
$show_count          = $count_status === 'total' || $count_status === 'total-and-site';
$show_count_per_site = $count_status === 'total-and-site';

$active_sites = better_amp_get_theme_mod( 'better-amp-post-social-share' );
unset( $active_sites['rand'] );

?>
<?php if ( $in_customizer ) { ?>
	<style>
		<?php if(!$show_count) { ?>
		.post-social-list .share-handler .number {
			display: none;
		}

		<?php } ?>

		<?php if(!$show_count_per_site) { ?>
		.post-social-list .social-item .number {
			display: none;
		}

		<?php } ?>
	</style>
<?php } ?>

<div class="social-list-wrapper share-list post-social-list">
	<?php

	if ( $show_count || $in_customizer ) {
		$count_labels = better_amp_social_shares_count( $active_sites );
	} else {
		$count_labels = array();
	}

	?>
	<span class="share-handler post-share-btn">
		<i class="fa fa-share-alt" aria-hidden="true"></i>
		<?php if ( ( $total_count = array_sum( $count_labels ) ) && ( $show_count || $in_customizer ) ) { ?>
			<b class="number"><?php echo better_amp_human_number_format( $total_count ) ?></b>
		<?php } else {
			?>
			<b class="text"><?php better_amp_translation_echo( 'share' ); ?></b>
			<?php
		} ?>
	</span>

	<ul class="social-list clearfix">
		<?php

		foreach ( $active_sites as $site_key => $active ) {

			if ( ! $active && ! $in_customizer ) {
				continue;
			}

			$count_label = ( $in_customizer || $show_count_per_site ) && isset( $count_labels[ $site_key ] ) ? $count_labels[ $site_key ] : 0;
			echo better_amp_social_share_get_li( $site_key, FALSE, $count_label ); // escaped before
		}

		?>
	</ul>
</div>
