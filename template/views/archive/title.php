<?php

$title = better_amp_get_archive_title_fields();

$title_classes = array(
	'archive-page-header'
);

if ( ! empty( $title['icon'] ) ) {
	$title_classes[] = 'have-icon';
}

if ( ! empty( $title['pre_title'] ) ) {
	$title_classes[] = 'pre_title';
}

?>
<header class="<?php echo implode( ' ', $title_classes ); ?>">
	<?php

	if ( ! empty( $title['pre_title'] ) ) {
		echo '<p class="pre-title">', $title['pre_title'], '</p>';
	}

	echo '<h1 class="archive-title">', $title['icon'], $title['title'], '</h1>';

	if ( ! empty( $title['description'] ) ) {

		echo '<div class="archive-description">', $title['description'], '</div>';
	}
	?>
</header>
