<?php

/**
 * Better AMP Component Factory Class
 *
 * @since 1.0.0
 */
class Better_AMP_Component extends Better_AMP_Component_Base {

	/**
	 * Component instance
	 *
	 * @since 1.0.0
	 *
	 * @var Better_AMP_Component_Interface
	 */
	private $component;


	/**
	 * Component class name
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $class_name;


	/**
	 * Store components instance
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $instances;


	/**
	 * Get self instance
	 *
	 * @param  string $component_class component class name that implements Better_AMP_Component_Interface
	 * @param bool    $fresh           new fresh object?
	 *
	 * @since 1.0.0
	 *
	 * @return Better_AMP_Component|bool Better_AMP_Component object on success or false on failure.
	 */
	public static function instance( $component_class, $fresh = FALSE ) {
		if ( isset( self::$instances[ $component_class ] ) && ! $fresh ) {
			return self::$instances[ $component_class ];
		}

		if ( class_exists( $component_class ) ) {
			return self::$instances[ $component_class ] = new Better_AMP_Component( $component_class );
		}

		return FALSE;
	}


	/**
	 * Clean instance storage cache
	 *
	 * @since 1.0.0
	 */
	public static function flush_instances() {
		self::$instances = array();
	}


	/**
	 * Better_AMP_Component constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $component_class
	 */
	public function __construct( $component_class ) {
		$this->class_name = $component_class;
		$this->set_component_instance( new $this->class_name );
	}


	/**
	 * Set a component class instance
	 *
	 * @param Better_AMP_Component_Interface $instance
	 *
	 * @since 1.0.0
	 */
	public function set_component_instance( Better_AMP_Component_Interface $instance ) {
		$this->component = $instance;
	}


	/**
	 * Get a component instance
	 *
	 * @return Better_AMP_Component_Interface;
	 *
	 * @since 1.0.0
	 */
	public function get_component_instance() {
		return $this->component;
	}

	/**
	 * Execute component and Transform HTML content to AMP content
	 *
	 * @param string $content html content
	 *
	 * @since 1.0.0
	 *
	 * @return string transformed content
	 */
	public function render( $content ) {
		return $this->component->transform( $content );
	}


	/**
	 * Get component config
	 *
	 * @see   Better_AMP_Component_Interface for more documentation
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_config() {
		return wp_parse_args(
			$this->component->config(),
			array(
				'shortcodes' => array(),
				'scripts'    => array(),
			)
		);
	}


	/**
	 * Replaces the default shortcode with the component shortcode
	 *
	 * @since 1.0.0
	 */
	public function register_shortcodes() {

		$config = $this->get_config();

		foreach ( $config['shortcodes'] as $shortcode => $callback ) {

			remove_shortcode( $shortcode );

			add_shortcode( $shortcode, $callback );
		}

	}


	/**
	 * Magic method handler
	 *
	 * Make private/protected methods readable for fire component method via this object instance
	 *
	 * @param string $method
	 * @param array  $args
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function __call( $method, $args ) {

		$callback = array( $this->component, $method );

		if ( is_callable( $callback ) ) {
			return call_user_func_array( $callback, $args );
		}
	}


	/**
	 * Enqueues component script.
	 *
	 * @param array $deps
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function enqueue_amp_scripts( $deps = array() ) {

		$config = $this->get_config();

		foreach ( $config['scripts'] as $name => $script ) {
			better_amp_enqueue_script( $name, $script, $deps );
		}

		return $deps; // pass $deps to work with series call {@see Better_AMP::call_component_method}
	}


	/**
	 * Determines the script should be enqueued!
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function can_enqueue_scripts() {
		return ! empty( $this->component->enable_enqueue_scripts );
	}

	/**
	 * Enqueue AMP component script if amp tag exists in the page and script was not printed yet
	 *
	 * @param Better_AMP_HTML_Util $dom
	 *
	 * @return Better_AMP_HTML_Util
	 */
	public function enqueue_amp_tags_script( $dom ) {

		$has_enqueue_scripts = $this->can_enqueue_scripts();

		if ( ! $has_enqueue_scripts ) { // if script was not printed previously

			$config = $this->get_config();

			foreach ( $config['scripts'] as $tag => $script ) {

				if ( $dom->getElementsByTagName( $tag )->length ) {
					better_amp_enqueue_script( $tag, $script, array( 'ampproject' ) );
				}
			}
		}

		return $dom;
	}
}
