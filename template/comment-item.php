<?php

/**
 * @var WP_Comment_Query $comment
 */
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>

	<article class="clearfix">

		<div class="column-1">
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 55 ); ?>
			</div><!-- .comment-avatar -->
		</div>


		<div class="column-2">
			<header class="comment-meta">
				<cite class="comment-author"><?php comment_author_link(); ?></cite>
				<time class="comment-published"><?php echo human_time_diff( get_comment_date( 'U' ) ) ?></time>
			</header><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->


			<footer class="comment-footer clearfix">
				<?php edit_comment_link( ' <i class="fa fa-edit"></i> ' . better_amp_translation_get( 'comments_edit' ) ); ?>
				<?php better_amp_comment_reply_link(
					array(
						'max_depth'     => get_option( 'thread_comments_depth' ),
						'reply_text'    => '<i class="fa fa-reply"></i> ' . better_amp_translation_get( 'comments_reply' ),
						'reply_to_text' => '<i class="fa fa-reply"></i> ' . better_amp_translation_get( 'comments_reply_to' ),
					)
				); ?>
			</footer><!-- .comment-footer -->
		</div>

	</article>

<?php /* No closing </li> is needed. */ ?>