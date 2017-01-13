<?php

$atts = better_amp_get_prop( 'Better_AMP_Carousel_Component' );

/**
 * @var Better_AMP_IMG_Component $img_component
 */
$img_component = Better_AMP_Component::instance( 'Better_AMP_IMG_Component' );

if ( empty( $atts['attachments'] ) ) {
	return;
}

?>
	<amp-carousel layout="responsive" type="slides" <?php better_amp_hw_attr() ?> autoplay>
		<?php

		foreach ( $atts['attachments'] as $attachment ) {
			echo $img_component->print_attachment_image( $attachment );
		}

		?>
	</amp-carousel>
<?php
