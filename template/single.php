<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'single' );
better_amp_enqueue_block_style( 'post' );

better_amp_the_post();

?>
	<div <?php better_amp_post_classes( 'single-post clearfix' ) ?>>

		<?php

		better_amp_show_ad_location( 'amp_post_title_before' );

		?>
		<h1 class="post-title">
			<?php the_title() ?>
		</h1>
		<?php

		better_amp_post_subtitle();

		better_amp_show_ad_location( 'amp_post_title_after' );

		$show_image_thumbnail = better_amp_get_theme_mod( 'better-amp-post-show-thumbnail' );

		if ( get_post_format() === 'video' ) {
			$meta_key = better_amp_get_theme_mod( 'better-amp-featured-va-key' );

			if ( empty( $media_key ) ) {
				$meta_key = '_featured_embed_code';
			}

			$media_url = get_post_meta( get_the_ID(), $meta_key, TRUE );
		} else {
			$media_url = FALSE;
		}

		if ( ! empty( $media_url ) ) {

			$embeded = better_amp_auto_embed_content( $media_url );

			$show_image_thumbnail = FALSE;

			?>
			<div
					class="post-thumbnail embeded" <?php better_amp_customizer_hidden_attr( 'better-amp-post-show-thumbnail' ) ?>>
				<?php echo $embeded['content'] ?>
			</div>
			<?php
		}

		if ( $show_image_thumbnail && has_post_thumbnail() ) { ?>
			<div
					class="post-thumbnail" <?php better_amp_customizer_hidden_attr( 'better-amp-post-show-thumbnail' ) ?>>
				<?php better_amp_the_post_thumbnail( 'better-amp-large' ); ?>
			</div>
		<?php } ?>

		<div class="post-meta">
			<?php

			$author_ID = get_the_author_meta( 'ID' );

			?>
			<a href="<?php echo get_author_posts_url( $author_ID ); ?>"
			   title="<?php better_amp_translation_echo( 'browse_author_articles' ); ?>"
			   class="post-author-avatar"><?php echo get_avatar( $author_ID, 26 ); ?></a><?php

			$meta_text = str_replace(
				array(
					'%s1',
					'%s2'
				),
				array(
					'<a href="%1$s">%2$s</a>',
					'%3$s'
				),
				better_amp_translation_get( 'by_on' )
			);

			printf( $meta_text,
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				get_the_author(),
				get_the_date()
			);

			?>
		</div>

		<div class="post-content entry-content">
			<?php

			the_content();

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'better-amp' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'better-amp' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

			?>
		</div>

		<?php

		better_amp_enqueue_block_style( 'post-terms' );

		the_tags(
			'<div class="post-terms tags"><span class="term-type"><i class="fa fa-tags"></i></span>',
			'',
			'</div>'
		);

		$cats = get_the_category_list( '' );
		if ( ! empty( $cats ) ) {

			?>
			<div class="post-terms cats"><span class="term-type"><i class="fa fa-folder-open"></i></span>
				<?php echo $cats; ?>
			</div>
			<?php
		}

		?>
	</div>

<?php

better_amp_template_part( 'views/post/social-share' );

if ( better_amp_get_theme_mod( 'better-amp-post-show-related' ) ) {
	better_amp_template_part( 'views/post/related' );
}

if ( better_amp_get_theme_mod( 'better-amp-post-show-comment' ) && ( comments_open() || get_comments_number() ) ) { ?>
	<div class="comments-wrapper"<?php better_amp_customizer_hidden_attr( 'better-amp-post-show-comment' ) ?>>
		<div class="comment-header clearfix">

			<div class="comments-label strong-label">
				<i class="fa fa-comments" aria-hidden="true"></i>
				<?php better_amp_translation_echo( 'comments' ); ?>

				<span class="counts-label">(<?php echo number_format_i18n( get_comments_number() ); ?>)</span>

			</div>
			<?php

			$link = better_amp_get_comment_link();

			// disable auto redirect for this link
			if ( better_amp_get_theme_mod( 'better-amp-mobile-auto-redirect' ) ) {
				$link = add_query_arg( 'bamp-skip-redirect', TRUE, $link );
			}

			?>
			<a href="<?php echo $link; ?>"
			   class="button add-comment"><?php better_amp_translation_echo( 'add_comment' ); ?></a>
		</div>

		<ul class="comment-list">
			<?php better_amp_list_comments(); ?>
		</ul>
	</div>

	<?php

	if ( get_comment_pages_count() ) { ?>
		<div class="comments-pagination pagination">
			<?php better_amp_comments_paginate() ?>

			<span class="page-numbers">
			<?php printf( better_amp_translation_get( 'comment_page_numbers' ), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() ); ?>

		</div>
		<?php
	}
}

better_amp_show_ad_location( 'amp_post_comment_after' );


better_amp_get_footer();
