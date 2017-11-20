<?php
/***
 *  BetterAMP Rules for validating final page codes.
 *  All rights reserved to BetterStudio, The creator of BetterAMP
 *
 * \--> BetterStudio, 2017 <--/
 */


$rules = array(
	0  =>
		array(
			'tag_name' => 'base',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'             => 'target',
							'value_regex_case' => '(_blank|_self)',
						),
				),
		),
	1  =>
		array(
			'tag_name' => 'h1',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	2  =>
		array(
			'tag_name' => 'h2',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	3  =>
		array(
			'tag_name' => 'h3',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	4  =>
		array(
			'tag_name' => 'h4',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	5  =>
		array(
			'tag_name' => 'h5',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	6  =>
		array(
			'tag_name' => 'h6',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	7  =>
		array(
			'tag_name' => 'p',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
				),
		),
	8  =>
		array(
			'tag_name' => 'blockquote',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
					1 =>
						array(
							'name'      => 'cite',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
											2 => 'mailto',
											3 => 'ftp',
											4 => 'fb-messenger',
											5 => 'sms',
											6 => 'tel',
											7 => 'viber',
											8 => 'whatsapp',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
		),
	9  =>
		array(
			'tag_name' => 'ol',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'reversed',
						),
					1 =>
						array(
							'name'        => 'start',
							'value_regex' => '[0-9]*',
						),
					2 =>
						array(
							'name'        => 'type',
							'value_regex' => '[1AaIi]',
						),
				),
		),
	10 =>
		array(
			'tag_name' => 'li',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'value',
							'value_regex' => '[0-9]*',
						),
				),
		),
	11 =>
		array(
			'tag_name' => 'div',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
					1 =>
						array(
							'name' => 'style',
						),
				),
		),
	12 =>
		array(
			'tag_name' => 'a',
			'attrs'    =>
				array(
					0  =>
						array(
							'name'      => 'href',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'ftp',
											1 => 'http',
											2 => 'https',
											3 => 'mailto',
											4 => 'fb-messenger',
											5 => 'sms',
											6 => 'tel',
											7 => 'viber',
											8 => 'whatsapp',
										),
									'allow_relative'   => TRUE,
								),
						),
					1  =>
						array(
							'name' => 'hreflang',
						),
					2  =>
						array(
							'name'                    => 'rel',
							'blacklisted_value_regex' => '(^|\\s)(canonical|components|dns-prefetch|import|manifest|preconnect|prefetch|preload|prerender|serviceworker|stylesheet|subresource|)(\\s|$)',
						),
					3  =>
						array(
							'name'     => 'role',
							'implicit' => TRUE,
						),
					4  =>
						array(
							'name'     => 'tabindex',
							'implicit' => TRUE,
						),
					5  =>
						array(
							'name'        => 'target',
							'value_regex' => '(_blank|_self)',
						),
					6  =>
						array(
							'name' => 'download',
						),
					7  =>
						array(
							'name' => 'media',
						),
					8  =>
						array(
							'name'  => 'type',
							'value' => 'text/html',
						),
					9  =>
						array(
							'name' => 'border',
						),
					10 =>
						array(
							'name' => 'name',
						),
				),
		),
	13 =>
		array(
			'tag_name' => 'time',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'datetime',
						),
				),
		),
	14 =>
		array(
			'tag_name' => 'bdo',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'dir',
						),
				),
		),
	15 =>
		array(
			'tag_name' => 'ins',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'datetime',
						),
					1 =>
						array(
							'name'      => 'cite',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
											2 => 'mailto',
											3 => 'ftp',
											4 => 'fb-messenger',
											5 => 'sms',
											6 => 'tel',
											7 => 'viber',
											8 => 'whatsapp',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
		),
	16 =>
		array(
			'tag_name' => 'del',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'datetime',
						),
					1 =>
						array(
							'name'      => 'cite',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
											2 => 'mailto',
											3 => 'ftp',
											4 => 'fb-messenger',
											5 => 'sms',
											6 => 'tel',
											7 => 'viber',
											8 => 'whatsapp',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
		),
	17 =>
		array(
			'tag_name' => 'source',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'type',
						),
				),
		),
	18 =>
		array(
			'tag_name' => 'source',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'type',
						),
				),
		),
	19 =>
		array(
			'tag_name' => 'source',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'mandatory' => TRUE,
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					1 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
					2 =>
						array(
							'name' => 'media',
						),
				),
		),
	20 =>
		array(
			'tag_name' => 'source',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'mandatory' => TRUE,
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					1 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
					2 =>
						array(
							'name' => 'media',
						),
				),
		),
	21 =>
		array(
			'tag_name' => 'table',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'sortable',
						),
					1 =>
						array(
							'name' => 'align',
						),
					2 =>
						array(
							'name'        => 'border',
							'value_regex' => '0|1',
						),
					3 =>
						array(
							'name' => 'bgcolor',
						),
					4 =>
						array(
							'name' => 'cellpadding',
						),
					5 =>
						array(
							'name' => 'cellspacing',
						),
					6 =>
						array(
							'name' => 'width',
						),
				),
		),
	22 =>
		array(
			'tag_name' => 'colgroup',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'span',
						),
				),
		),
	23 =>
		array(
			'tag_name' => 'col',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'span',
						),
				),
		),
	24 =>
		array(
			'tag_name' => 'tr',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'align',
						),
					1 =>
						array(
							'name' => 'bgcolor',
						),
					2 =>
						array(
							'name' => 'height',
						),
					3 =>
						array(
							'name' => 'valign',
						),
				),
		),
	25 =>
		array(
			'tag_name' => 'td',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'colspan',
						),
					1 =>
						array(
							'name' => 'headers',
						),
					2 =>
						array(
							'name' => 'rowspan',
						),
					3 =>
						array(
							'name' => 'align',
						),
					4 =>
						array(
							'name' => 'bgcolor',
						),
					5 =>
						array(
							'name' => 'height',
						),
					6 =>
						array(
							'name' => 'valign',
						),
					7 =>
						array(
							'name' => 'width',
						),
				),
		),
	26 =>
		array(
			'tag_name' => 'th',
			'attrs'    =>
				array(
					0  =>
						array(
							'name' => 'abbr',
						),
					1  =>
						array(
							'name' => 'colspan',
						),
					2  =>
						array(
							'name' => 'headers',
						),
					3  =>
						array(
							'name' => 'rowspan',
						),
					4  =>
						array(
							'name' => 'scope',
						),
					5  =>
						array(
							'name' => 'sorted',
						),
					6  =>
						array(
							'name' => 'align',
						),
					7  =>
						array(
							'name' => 'bgcolor',
						),
					8  =>
						array(
							'name' => 'height',
						),
					9  =>
						array(
							'name' => 'valign',
						),
					10 =>
						array(
							'name' => 'width',
						),
				),
		),
	27 =>
		array(
			'tag_name' => 'button',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'disabled',
						),
					1 =>
						array(
							'name' => 'name',
						),
					2 =>
						array(
							'name'     => 'role',
							'implicit' => TRUE,
						),
					3 =>
						array(
							'name'     => 'tabindex',
							'implicit' => TRUE,
						),
					4 =>
						array(
							'name' => 'type',
						),
					5 =>
						array(
							'name' => 'value',
						),
				),
		),
	28 =>
		array(
			'tag_name' => 'amp-ad',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'alt',
						),
					1 =>
						array(
							'name' => 'json',
						),
					2 =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					3 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
					4 =>
						array(
							'name' => 'media',
						),
					5 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	29 =>
		array(
			'tag_name' => 'amp-embed',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'alt',
						),
					1 =>
						array(
							'name' => 'json',
						),
					2 =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					3 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
					4 =>
						array(
							'name' => 'media',
						),
					5 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	30 =>
		array(
			'tag_name' => 'amp-img',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'alt',
						),
					1 =>
						array(
							'name' => 'attribution',
						),
					2 =>
						array(
							'name' => 'placeholder',
						),
					3 =>
						array(
							'name' => 'media',
						),
					4 =>
						array(
							'name' => 'noloading',
						),
					5 =>
						array(
							'name'              => 'src',
							'alternative_names' =>
								array(
									0 => 'srcset',
								),
							'mandatory'         => TRUE,
							'value_url'         =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'data',
											1 => 'http',
											2 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					6 =>
						array(
							'name' => 'srcset',
						),
					7 =>
						array(
							'name' => 'role',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	31 =>
		array(
			'tag_name' => 'amp-pixel',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'mandatory' => TRUE,
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts'      =>
						array(
							0 => 'FIXED',
							1 => 'NODISPLAY',
						),
					'defines_default_width'  => TRUE,
					'defines_default_height' => TRUE,
				),
		),
	32 =>
		array(
			'tag_name' => 'amp-video',
			'attrs'    =>
				array(
					0  =>
						array(
							'name' => 'alt',
						),
					1  =>
						array(
							'name' => 'attribution',
						),
					2  =>
						array(
							'name' => 'autoplay',
						),
					3  =>
						array(
							'name' => 'controls',
						),
					4  =>
						array(
							'name' => 'loop',
						),
					5  =>
						array(
							'name' => 'muted',
						),
					6  =>
						array(
							'name' => 'placeholder',
						),
					7  =>
						array(
							'name' => 'poster',
						),
					8  =>
						array(
							'name'        => 'preload',
							'value_regex' => '(none|metadata|auto|)',
						),
					9  =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					10 =>
						array(
							'name' => 'media',
						),
					11 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	33 =>
		array(
			'tag_name' => 'amp-accordion',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'animate',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'CONTAINER',
						),
				),
		),
	34 =>
		array(
			'tag_name' => 'section',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'expanded',
						),
				),
		),
	35 =>
		array(
			'tag_name' => 'amp-analytics',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'type',
						),
					1 =>
						array(
							'name'      => 'config',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
		),
	36 =>
		array(
			'tag_name' => 'amp-anim',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'alt',
						),
					1 =>
						array(
							'name' => 'attribution',
						),
					2 =>
						array(
							'name' => 'autoplay',
						),
					3 =>
						array(
							'name' => 'controls',
						),
					4 =>
						array(
							'name' => 'media',
						),
					5 =>
						array(
							'name' => 'noloading',
						),
					6 =>
						array(
							'name'              => 'src',
							'alternative_names' =>
								array(
									0 => 'srcset',
								),
							'mandatory'         => TRUE,
							'value_url'         =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'data',
											1 => 'http',
											2 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	37 =>
		array(
			'tag_name' => 'amp-audio',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'autoplay',
							'value_regex' => '^$|desktop|tablet|mobile|autoplay',
						),
					1 =>
						array(
							'name' => 'controls',
						),
					2 =>
						array(
							'name' => 'loop',
						),
					3 =>
						array(
							'name' => 'muted',
						),
					4 =>
						array(
							'name'      => 'src',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					5 =>
						array(
							'name' => 'media',
						),
					6 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts'      =>
						array(
							0 => 'FIXED',
							1 => 'FIXED-HEIGHT',
							2 => 'NODISPLAY',
						),
					'defines_default_width'  => TRUE,
					'defines_default_height' => TRUE,
				),
		),
	38 =>
		array(
			'tag_name' => 'amp-brid-player',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'data-partner',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9]+',
						),
					1 =>
						array(
							'name'        => 'data-player',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9]+',
						),
					2 =>
						array(
							'name'            => 'data-playlist',
							'mandatory_oneof' =>
								array(
									'data-playlist' => 0,
									'data-video'    => 1,
								),
							'value_regex'     => '[0-9]+',
						),
					3 =>
						array(
							'name'            => 'data-video',
							'mandatory_oneof' =>
								array(
									'data-playlist' => 0,
									'data-video'    => 1,
								),
							'value_regex'     => '[0-9]+',
						),
					4 =>
						array(
							'name' => 'media',
						),
					5 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	39 =>
		array(
			'tag_name' => 'amp-brightcove',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-account',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name' => 'data-embed',
						),
					2 =>
						array(
							'name' => 'data-player',
						),
					3 =>
						array(
							'name' => 'data-playlist-id',
						),
					4 =>
						array(
							'name' => 'data-video-id',
						),
					5 =>
						array(
							'name' => 'media',
						),
					6 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	40 =>
		array(
			'tag_name' => 'amp-carousel',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'arrows',
						),
					1 =>
						array(
							'name' => 'autoplay',
						),
					2 =>
						array(
							'name' => 'controls',
						),
					3 =>
						array(
							'name'        => 'delay',
							'value_regex' => '[0-9]+',
						),
					4 =>
						array(
							'name' => 'dots',
						),
					5 =>
						array(
							'name' => 'loop',
						),
					6 =>
						array(
							'name'        => 'type',
							'value_regex' => 'slides|carousel',
						),
					7 =>
						array(
							'name' => 'media',
						),
					8 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	41 =>
		array(
			'tag_name' => 'amp-dailymotion',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'data-endscreen-enable',
							'value_regex' => 'true|false',
						),
					1 =>
						array(
							'name'        => 'data-info',
							'value_regex' => 'true|false',
						),
					2 =>
						array(
							'name'        => 'data-mute',
							'value_regex' => 'true|false',
						),
					3 =>
						array(
							'name'        => 'data-sharing-enable',
							'value_regex' => 'true|false',
						),
					4 =>
						array(
							'name'        => 'data-start',
							'value_regex' => '[0-9]+',
						),
					5 =>
						array(
							'name'             => 'data-ui-highlight',
							'value_regex_case' => '([0-9a-f]{3}){1,2}',
						),
					6 =>
						array(
							'name'        => 'data-ui-logo',
							'value_regex' => 'true|false',
						),
					7 =>
						array(
							'name'             => 'data-videoid',
							'mandatory'        => TRUE,
							'value_regex_case' => '[a-z0-9]+',
						),
					8 =>
						array(
							'name' => 'media',
						),
					9 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'RESPONSIVE',
						),
				),
		),
	42 =>
		array(
			'tag_name' => 'amp-facebook',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-href',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	43 =>
		array(
			'tag_name' => 'amp-fit-text',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'max-font-size',
						),
					1 =>
						array(
							'name' => 'min-font-size',
						),
					2 =>
						array(
							'name' => 'media',
						),
					3 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	44 =>
		array(
			'tag_name' => 'amp-font',
			'attrs'    =>
				array(
					0  =>
						array(
							'name'      => 'font-family',
							'mandatory' => TRUE,
						),
					1  =>
						array(
							'name' => 'font-style',
						),
					2  =>
						array(
							'name' => 'font-variant',
						),
					3  =>
						array(
							'name'        => 'timeout',
							'value_regex' => '[0-9]+',
						),
					4  =>
						array(
							'name' => 'font-weight',
						),
					5  =>
						array(
							'name' => 'on-error-add-class',
						),
					6  =>
						array(
							'name' => 'on-error-remove-class',
						),
					7  =>
						array(
							'name' => 'on-load-add-class',
						),
					8  =>
						array(
							'name' => 'on-load-remove-class',
						),
					9  =>
						array(
							'name' => 'media',
						),
					10 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'NODISPLAY',
						),
				),
		),
	45 =>
		array(
			'tag_name' => 'amp-iframe',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'  => 'allowfullscreen',
							'value' => '',
						),
					1 =>
						array(
							'name'  => 'allowtransparency',
							'value' => '',
						),
					2 =>
						array(
							'name'        => 'frameborder',
							'value_regex' => '0|1',
						),
					3 =>
						array(
							'name' => 'resizable',
						),
					4 =>
						array(
							'name' => 'sandbox',
						),
					5 =>
						array(
							'name'        => 'scrolling',
							'value_regex' => 'auto|yes|no',
						),
					6 =>
						array(
							'name'            => 'src',
							'mandatory_oneof' =>
								array(
									'src'    => 0,
									'srcdoc' => 1,
								),
							'value_url'       =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'data',
											1 => 'https',
										),
									'allow_relative'   => FALSE,
								),
						),
					7 =>
						array(
							'name'            => 'srcdoc',
							'mandatory_oneof' =>
								array(
									'src'    => 0,
									'srcdoc' => 1,
								),
						),
					8 =>
						array(
							'name' => 'media',
						),
					9 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	46 =>
		array(
			'tag_name' => 'amp-image-lightbox',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'controls',
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'NODISPLAY',
						),
				),
		),
	47 =>
		array(
			'tag_name' => 'amp-instagram',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'alt',
						),
					1 =>
						array(
							'name'            => 'data-shortcode',
							'mandatory_oneof' =>
								array(
									'data-shortcode' => 0,
									'src'            => 1,
								),
						),
					2 =>
						array(
							'name'            => 'shortcode',
							'mandatory_oneof' =>
								array(
									'data-shortcode' => 0,
									'src'            => 1,
								),
							'deprecation'     => 'data-shortcode',
							'deprecation_url' => 'https://www.ampproject.org/docs/reference/extended/amp-instagram.html',
						),
					3 =>
						array(
							'name'            => 'src',
							'mandatory_oneof' =>
								array(
									'data-shortcode' => 0,
									'src'            => 1,
								),
							'value_url'       =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					4 =>
						array(
							'name' => 'media',
						),
					5 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	48 =>
		array(
			'tag_name' => 'amp-install-serviceworker',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'src',
							'mandatory' => TRUE,
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'NODISPLAY',
						),
				),
		),
	49 =>
		array(
			'tag_name' => 'amp-jwplayer',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'             => 'data-media-id',
							'value_regex_case' => '[0-9a-z]{8}',
						),
					1 =>
						array(
							'name'             => 'data-player-id',
							'mandatory'        => TRUE,
							'value_regex_case' => '[0-9a-z]{8}',
						),
					2 =>
						array(
							'name'             => 'data-playlist-id',
							'value_regex_case' => '[0-9a-z]{8}',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	50 =>
		array(
			'tag_name' => 'amp-kaltura-player',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-partner',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	51 =>
		array(
			'tag_name' => 'amp-lightbox',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'controls',
						),
					1 =>
						array(
							'name' => 'from',
						),
					2 =>
						array(
							'name' => 'media',
						),
					3 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'NODISPLAY',
						),
				),
		),
	52 =>
		array(
			'tag_name' => 'amp-list',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'credentials',
						),
					1 =>
						array(
							'name'      => 'src',
							'mandatory' => TRUE,
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					2 =>
						array(
							'name' => 'template',
						),
					3 =>
						array(
							'name' => 'media',
						),
					4 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	53 =>
		array(
			'tag_name' => 'template',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
							'value'     => 'amp-mustache',
						),
				),
		),
	54 =>
		array(
			'tag_name' => 'amp-pinterest',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-do',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	55 =>
		array(
			'tag_name' => 'amp-reach-player',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'data-embed-id',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9a-z-]+',
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'RESPONSIVE',
						),
				),
		),
	56 =>
		array(
			'tag_name' => 'amp-sidebar',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'side',
							'value_regex' => '(left|right)',
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'NODISPLAY',
						),
				),
		),
	57 =>
		array(
			'tag_name' => 'amp-social-share',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name'      => 'data-share-endpoint',
							'value_url' =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'ftp',
											1 => 'http',
											2 => 'https',
											3 => 'mailto',
											4 => 'fb-messenger',
											5 => 'snapchat',
											6 => 'sms',
											7 => 'tel',
											8 => 'viber',
											9 => 'whatsapp',
										),
								),
						),
					2 =>
						array(
							'name' => 'media',
						),
					3 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'CONTAINER',
							1 => 'FILL',
							2 => 'FIXED',
							3 => 'FIXED-HEIGHT',
							4 => 'FLEX-ITEM',
							5 => 'NODISPLAY',
							6 => 'RESPONSIVE',
						),
				),
		),
	58 =>
		array(
			'tag_name' => 'amp-soundcloud',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'             => 'data-color',
							'value_regex_case' => '([0-9a-f]{3}){1,2}',
						),
					1 =>
						array(
							'name'        => 'data-trackid',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9]+',
						),
					2 =>
						array(
							'name'        => 'data-visual',
							'value_regex' => 'true|false',
						),
					3 =>
						array(
							'name' => 'media',
						),
					4 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FIXED-HEIGHT',
						),
				),
		),
	59 =>
		array(
			'tag_name' => 'amp-springboard-player',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-content-id',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name'      => 'data-domain',
							'mandatory' => TRUE,
						),
					2 =>
						array(
							'name'      => 'data-items',
							'mandatory' => TRUE,
						),
					3 =>
						array(
							'name'             => 'data-mode',
							'mandatory'        => TRUE,
							'value_regex_case' => 'playlist|video',
						),
					4 =>
						array(
							'name'             => 'data-player-id',
							'mandatory'        => TRUE,
							'value_regex_case' => '[a-z0-9]+',
						),
					5 =>
						array(
							'name'        => 'data-site-id',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9]+',
						),
					6 =>
						array(
							'name' => 'media',
						),
					7 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FLEX-ITEM',
							3 => 'RESPONSIVE',
						),
				),
		),
	60 =>
		array(
			'tag_name' => 'amp-twitter',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'            => 'data-tweetid',
							'mandatory_oneof' =>
								array(
									'data-tweetid' => 0,
									'src'          => 1,
								),
						),
					1 =>
						array(
							'name'            => 'src',
							'mandatory_oneof' =>
								array(
									'data-tweetid' => 0,
									'src'          => 1,
								),
							'value_url'       =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					2 =>
						array(
							'name' => 'media',
						),
					3 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	61 =>
		array(
			'tag_name' => 'amp-vimeo',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'        => 'data-videoid',
							'mandatory'   => TRUE,
							'value_regex' => '[0-9]+',
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'RESPONSIVE',
						),
				),
		),
	62 =>
		array(
			'tag_name' => 'amp-vine',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'data-vineid',
							'mandatory' => TRUE,
						),
					1 =>
						array(
							'name' => 'media',
						),
					2 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	63 =>
		array(
			'tag_name' => 'amp-youtube',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'            => 'data-videoid',
							'mandatory_oneof' =>
								array(
									'src'          => 0,
									'data-videoid' => 1,
								),
						),
					1 =>
						array(
							'name'            => 'src',
							'mandatory_oneof' =>
								array(
									'src'          => 0,
									'data-videoid' => 1,
								),
							'value_url'       =>
								array(
									'allowed_protocol' =>
										array(
											0 => 'http',
											1 => 'https',
										),
									'allow_relative'   => TRUE,
								),
						),
					2 =>
						array(
							'name'            => 'video-id',
							'mandatory_oneof' =>
								array(
									'src'          => 0,
									'data-videoid' => 1,
								),
							'deprecation'     => 'data-videoid',
							'deprecation_url' => 'https://www.ampproject.org/docs/reference/extended/amp-youtube.html',
						),
					3 =>
						array(
							'name' => 'media',
						),
					4 =>
						array(
							'name' => 'noloading',
						),
				),
			'layouts'  =>
				array(
					'supported_layouts' =>
						array(
							0 => 'FILL',
							1 => 'FIXED',
							2 => 'FIXED-HEIGHT',
							3 => 'FLEX-ITEM',
							4 => 'NODISPLAY',
							5 => 'RESPONSIVE',
						),
				),
		),
	64 =>
		array(
			'tag_name' => 'amp-auto-ads',
			'attrs'    =>
				array(
					0 =>
						array(
							'name'      => 'type',
							'mandatory' => TRUE,
						),
				),
		),
	65 =>
		array(
			'tag_name' => 'span',
			'attrs'    =>
				array(
					0 =>
						array(
							'name' => 'rel',
						),
				),
		),
);
