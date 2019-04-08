<?php


class Test_End_Point_URL_Transformer extends AMP_Test {


	protected function convert( $url ) {

		return Better_AMP_Content_Sanitizer::transform_to_end_point_amp( $url );
	}

	/**
	 * @test
	 */
	public function urls_should_not_convert_external_urls() {

		$url = 'https://betterstudio.com/publisher-wp-theme/';

		$this->assertFalse( $this->convert( $url ) );
	}

	/**
	 * @test
	 * @dataProvider amp_urls
	 *
	 * @param string $url
	 */
	public function do_not_convert_amp_urls( $url ) {

		$this->assertFalse( $this->convert( $url ) );
	}

	public function amp_urls() {

		return array(
			[ site_url( '/amp' ) ],
			[ site_url( '/slug/amp/' ) ],
			[ site_url( '/amp/' ) ],
		);
	}

	/**
	 * @test
	 */
	public function do_not_convert_wp_content_urls() {

		$object = array(
			'post_title'   => 'Filename',
			'context'      => 'custom-background',
			'post_content' => site_url( 'wp-content/themes/bs-site-d1/image/bs-logo.svg' ),
			'guid'         => site_url( 'wp-content/themes/bs-site-d1/image/bs-logo.svg' ),
		);

		$attachment_id = wp_insert_attachment( $object );

		$this->assertFalse(
			$this->convert(
				wp_get_attachment_url( $attachment_id )
			)
		);
	}

	/**
	 * @test
	 */
	public function convert_single_post_urls() {

		$slug    = 'the-post-title';
		$post_id = $this->factory()->post->create( array(
			'post_title'  => 'sample',
			'post_type'   => 'post',
			'post_status' => 'publish',
			'post_name'   => $slug,
		) );

		$this->set_permalink_structure( false );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/$slug/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/$slug/amp" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%postname%' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$slug/amp" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%postname%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$slug/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%postname%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$slug/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%post_id%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$post_id/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%post_id%' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$post_id/amp" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%post_id%/%postname%' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$post_id/$slug/amp" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/topic/%post_id%/%postname%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/topic/$post_id/$slug/amp/" ), $this->convert( $permalink ) );
	}

	/**
	 * @test
	 */
	public function convert_page_urls() {

		$slug    = 'the-page-title';
		$post_id = $this->factory()->post->create( array(
			'post_title'  => 'sample',
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_name'   => $slug,
		) );

		$this->set_permalink_structure( false );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%/' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/$slug/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%' );
		$permalink = get_the_permalink( $post_id );
		$this->assertEquals( site_url( "/$slug/amp" ), $this->convert( $permalink ) );
	}

	/**
	 * @test
	 */
	public function convert_author_page_urls() {

		$user_id  = $this->login();
		$user     = get_userdata( $user_id );
		$nicename = $user->user_nicename;

		$this->factory()->post->create_many( 2, array(
			'post_title'  => 'sample',
			'post_type'   => 'post',
			'post_status' => 'publish',
		) );


		$this->set_permalink_structure( false );
		$permalink = get_author_posts_url( $user_id );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%/' );
		$permalink = get_author_posts_url( $user_id );
		$this->assertEquals( site_url( "/author/$nicename/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%' );
		$permalink = get_author_posts_url( $user_id );
		$this->assertEquals( site_url( "/author/$nicename/amp" ), $this->convert( $permalink ) );
	}


	/**
	 * @test
	 */
	public function convert_embed_page_urls() {

		$slug          = 'the-filename';
		$object        = array(
			'post_title'   => 'Filename',
			'post_name'    => $slug,
			'context'      => 'custom-background',
			'post_content' => site_url( 'wp-content/themes/bs-site-d1/image/bs-logo.svg' ),
			'guid'         => site_url( 'wp-content/themes/bs-site-d1/image/bs-logo.svg' ),
		);
		$attachment_id = wp_insert_attachment( $object );


		$this->set_permalink_structure( false );
		$permalink = get_post_embed_url( $attachment_id );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%/' );
		$permalink = get_post_embed_url( $attachment_id );
		$this->assertEquals( site_url( "/$slug/embed/amp/" ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%' );
		$permalink = get_post_embed_url( $attachment_id );
		$this->assertEquals( site_url( "/$slug/embed/amp" ), $this->convert( $permalink ) );
	}


	/**
	 * @test
	 */
	public function convert_search_page_urls() {


		$this->set_permalink_structure( false );
		$permalink = add_query_arg( 's', 'search', site_url( '/' ) );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );

		$permalink = add_query_arg( 's', '', site_url( '/' ) );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );

		$this->set_permalink_structure( '/%postname%/' );
		$permalink = site_url( '/search/query/' );
		$this->assertEquals( site_url( '/search/query/amp/' ), $this->convert( $permalink ) );

		$permalink = site_url( '/search/query' );
		$this->assertEquals( site_url( '/search/query/amp' ), $this->convert( $permalink ) );
	}


	/**
	 * @test
	 */
	public function convert_home_page() {

		$this->set_permalink_structure( false );
		$permalink = site_url( '/' );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );


		$this->set_permalink_structure( '/%postname%/' );
		$permalink = site_url( '/' );
		$this->assertEquals( site_url( '/amp/' ), $this->convert( $permalink ) );
	}


	/**
	 * @test
	 */
	public function convert_post_type_archive_urls() {

		register_post_type(
			'book',
			[
				'public'              => true,
				'show_ui'             => true,
				'map_meta_cap'        => true,
				'exclude_from_search' => false,
				'hierarchical'        => true,
				'query_var'           => true,
				'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
				'has_archive'         => true,
				'show_in_nav_menus'   => true,
				'rewrite'             => [
					'slug'       => 'book',
					'with_front' => true,
				],
			]
		);

		$this->set_permalink_structure( false );
		$permalink = get_post_type_archive_link( 'book' );
		$this->assertEquals( add_query_arg( 'amp', true, $permalink ), $this->convert( $permalink ) );

		$this->set_permalink_structure( '/%postname%/' );
		$permalink = get_post_type_archive_link( 'book' );
		$this->assertEquals( site_url( '/book/amp/' ), $this->convert( $permalink ) );
	}


	/**
	 * @test
	 */
	public function convert_paginated_urls() {

		$this->set_permalink_structure( '/%postname%/' );

		$this->assertEquals( site_url( '/author/admin/amp/page/2/' ), $this->convert( site_url( '/author/admin/page/2/' ) ) );
		$this->assertEquals( site_url( '/category/uncategorized/amp/page/2/' ), $this->convert( site_url( '/category/uncategorized/page/2/' ) ) );


		$this->set_permalink_structure( '/topic/%postname%/' );
		$this->assertEquals( site_url( '/topic/category/uncategorized/amp/page/2/' ), $this->convert( site_url( '/topic/category/uncategorized/page/2/' ) ) );
		$this->assertEquals( site_url( '/topic/slug/amp/comment-page-2/' ), $this->convert( site_url( '/topic/slug/comment-page-2/' ) ) );
	}
}
