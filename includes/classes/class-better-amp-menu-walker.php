<?php

/**
 * Custom class to display navigation menu AMP-Friendly - some like this:
 *
 * <a href="#">Menu</a>         # Parent item with no child elements
 *    <amp-accordion>
 *      <section>
 *          <h6><span>Submenu 1</span></h6># Parent item with childs
 *          <div>
 *                <a href="#">Submenu 2</a>
 *                ....                      #list sub menus
 *          </div>
 *       </section>
 *     </amp-accordion>
 *
 *
 * @since 1.0.0
 */
class Better_AMP_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Start_el method use this property to detect if previous element was
	 * an accordion print <h6> element like above html
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $accordion_started = FALSE;


	/**
	 * flag for detecting childs started or not
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $accordion_childs_started = FALSE;


	/**
	 * Starts the list before the elements are added.
	 *
	 * @see   Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of wp_nav_menu() arguments.
	 *
	 * @since 1.0.0
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
	}


	/**
	 * Starts the list before the elements are added.
	 *
	 * @see   Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of wp_nav_menu() arguments.
	 *
	 * @since 1.0.0
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->accordion_childs_started && $depth == 0 ) {

			$this->end_accordion_child_wrapper( $output, $depth );
		}

		if ( $this->accordion_started && $depth == 0 ) {
			$this->end_accordion( $output, $depth );
		}

	}


	/**
	 * Starts the element output.
	 *
	 * @see   Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of wp_nav_menu() arguments.
	 * @param int    $id     Current item ID.
	 *
	 * @since 1.0.0
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// Remove menu-item-has-children class
		if ( $depth ) {
			$index = array_search( 'menu-item-has-children', $item->classes );
			if ( $index !== FALSE ) {
				unset( $item->classes[ $index ] );
			}
		}

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $depth > 1 ) {
			$classes[] = 'menu-item-deep';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		if ( $this->has_children && $depth == 0 ) {
			add_theme_support( 'better-amp-has-nav-child', TRUE );

			$this->start_accordion( $output, $depth );

			$output .= '<h6><span ' . $class_names . '>';
			$output .= $this->get_anchor_tag( $item, $depth, $args, $id );
			$output .= '</span></h6>';

			$this->start_accordion_child_wrapper( $output, $depth );

		} else {

			$output .= '<span ' . $class_names . '>';
			$output .= $this->get_anchor_tag( $item, $depth, $args, $id );
			$output .= '</span>';

		}

	} // start_el


	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
	}


	/**
	 * Adds the start code of accordion
	 *
	 * @param     $output
	 * @param int $depth
	 *
	 * @since 1.0.0
	 */
	public function start_accordion( &$output, $depth = 0 ) {

		$output .= "<amp-accordion><section>";

		$this->accordion_started = TRUE;
		$this->enqueue_accordion = TRUE;
	}


	/**
	 * Ads close tag for accordion
	 *
	 * @param     $output
	 * @param int $depth
	 *
	 * @since 1.0.0
	 */
	public function end_accordion( &$output, $depth = 0 ) {

		$output .= "</section></amp-accordion>";

		$this->accordion_started = FALSE;
	}


	/**
	 * Adds acordion childs wrapper div
	 *
	 * @param     $output
	 * @param int $depth
	 *
	 * @since 1.0.0
	 */
	public function start_accordion_child_wrapper( &$output, $depth = 0 ) {

		$output .= "\n<div>\n";

		$this->accordion_childs_started = TRUE;
	}


	/**
	 * Closes the accordion childs wrapper tag
	 *
	 * @param     $output
	 * @param int $depth
	 *
	 * @since 1.0.0
	 */
	public function end_accordion_child_wrapper( &$output, $depth = 0 ) {

		$output .= "</div>\n";

		$this->accordion_childs_started = FALSE;
	}


	/**
	 * @param $item
	 * @param $depth
	 * @param $args
	 * @param $id
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_anchor_tag( $item, $depth, $args, $id ) {

		$current_el = '';

		parent::start_el( $current_el, $item, $depth, $args, $id );

		// Unwrap li tag
		if ( preg_match( '#<\s*li\s* [^>]* > (.+) #ix', $current_el, $matched ) ) {
			return $matched[1];
		}

		return $this->make_anchor_tag( $item, $args, $depth );
	}


	/**
	 * Make <a> HTML Tag
	 *
	 * @copyright Credit goes to wordPress team
	 * @see       Walker_Nav_Menu::start_el
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 * @param array  $args  An array of wp_nav_menu() arguments.
	 *
	 * @since     1.0.0
	 *
	 * @return string
	 */
	protected function make_anchor_tag( $item, $args, $depth ) {

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $atts   {
		 *                       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string  $title  Title attribute.
		 * @type string  $target Target attribute.
		 * @type string  $rel    The rel attribute.
		 * @type string  $href   The href attribute.
		 * }
		 *
		 * @param object $item   The current menu item.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		return $item_output;
	}
}
