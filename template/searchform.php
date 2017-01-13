<form role="search" method="get" class="search-form clearfix <?php echo ! get_search_query( FALSE ) ? 'empty' : ''; ?>"
      action="<?php echo esc_url( better_amp_site_url() ) ?>">

	<label class="search-label">
		<?php better_amp_translation_echo( 'search_on_site' ); ?>
	</label>

	<div class="search-input">
		<input type="search" class="search-field"
		       placeholder="<?php better_amp_translation_echo( 'search_input_placeholder' ); ?>"
		       value="<?php the_search_query() ?>" name="s"/>
		<input type="submit" class="search-submit button"
		       value="<?php better_amp_translation_echo( 'search_button' ); ?>"/>
	</div>

</form>
