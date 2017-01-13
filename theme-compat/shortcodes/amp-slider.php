<amp-carousel class="amp-slider" layout="responsive" type="slides" <?php better_amp_hw_attr() ?> autoplay>
	<?php

	while( better_amp_have_posts() ) {

		better_amp_the_post();

		if ( has_post_thumbnail() ):
			?>
			<div>
				<a href="<?php the_permalink() ?>">
					<?php the_post_thumbnail() ?>
				</a>
			</div>
			<?php
		endif;

	}

	?>
</amp-carousel>
