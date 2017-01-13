<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>" target="_blank">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search on site:', 'better-amp' ) ?></span>
		<input type="search" class="search-field"
		       placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'better-amp' ) ?>"
		       value="<?php the_search_query() ?>" name="s"/>
	</label>
	<input type="submit" class="search-submit"
	       value="<?php echo esc_attr_x( 'Search', 'submit button', 'better-amp' ) ?>"/>
</form>
