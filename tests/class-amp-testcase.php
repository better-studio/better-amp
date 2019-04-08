<?php

abstract class AMP_Test extends \WP_UnitTestCase {

	public function login( $as = 'administrator' ) {

		$user_id = $this->factory()->user->create( array( 'role' => $as ) );

		wp_set_current_user( $user_id );

		return $user_id;
	}

	function go_to( $url ) {

		$this->set_permalink_structure( '/%postname%/' );

		return parent::go_to( $url );
	}
}
