<?php
/***
 *  BetterAMP Rules for validating final page codes.
 *  All rights reserved to BetterStudio, The creator of BetterAMP
 *
 * \--> BetterStudio, 2017 <--/
 */


$rules = array(
	array(
		'tag_name' => 'br',
		'attrs'    => array(),
	),
	array(
		'tag_name' => 'base',
		'attrs'    => array(
			array(
				'name'             => 'target',
				'value_regex_case' => '(_blank|_self)',
			),
		),
	),
	array(
		'tag_name' => 'h1',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'h2',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'h3',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'h4',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'h5',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'h6',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'p',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
		),
	),
	array(
		'tag_name' => 'blockquote',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
			array(
				'name'      => 'cite',
				'value_url' => array(
					'allowed_protocol' => array(
						'http',
						'https',
						'mailto',
						'ftp',
						'fb-messenger',
						'sms',
						'tel',
						'viber',
						'whatsapp',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
	),
	array(
		'tag_name' => 'ol',
		'attrs'    => array(
			array(
				'name' => 'reversed',
			),
			array(
				'name'        => 'start',
				'value_regex' => '[0-9]*',
			),
			array(
				'name'        => 'type',
				'value_regex' => '[1AaIi]',
			),
		),
	),
	array(
		'tag_name' => 'li',
		'attrs'    => array(
			array(
				'name'        => 'value',
				'value_regex' => '[0-9]*',
			),
		),
	),
	array(
		'tag_name' => 'div',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
			array(
				'name' => 'style',
			),
		),
	),
	array(
		'tag_name' => 'a',
		'attrs'    => array(
			array(
				'name'      => 'href',
				'value_url' => array(
					'allowed_protocol' => array(
						'ftp',
						'http',
						'https',
						'mailto',
						'fb-messenger',
						'sms',
						'tel',
						'viber',
						'whatsapp',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'hreflang',
			),
			array(
				'name'                    => 'rel',
				'blacklisted_value_regex' => '(^|\\s)(canonical|components|dns-prefetch|import|manifest|preconnect|prefetch|preload|prerender|serviceworker|stylesheet|subresource|)(\\s|$)',
			),
			array(
				'name'     => 'role',
				'implicit' => TRUE,
			),
			array(
				'name'     => 'tabindex',
				'implicit' => TRUE,
			),
			array(
				'name'        => 'target',
				'value_regex' => '(_blank|_self)',
			),
			array(
				'name' => 'download',
			),
			array(
				'name' => 'media',
			),
			array(
				'name'  => 'type',
				'value' => 'text/html',
			),
			array(
				'name' => 'border',
			),
			array(
				'name' => 'name',
			),
		),
	),
	array(
		'tag_name' => 'time',
		'attrs'    => array(
			array(
				'name' => 'datetime',
			),
		),
	),
	array(
		'tag_name' => 'bdo',
		'attrs'    => array(
			array(
				'name' => 'dir',
			),
		),
	),
	array(
		'tag_name' => 'ins',
		'attrs'    => array(
			array(
				'name' => 'datetime',
			),
			array(
				'name'      => 'cite',
				'value_url' => array(
					'allowed_protocol' => array(
						'http',
						'https',
						'mailto',
						'ftp',
						'fb-messenger',
						'sms',
						'tel',
						'viber',
						'whatsapp',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
	),
	array(
		'tag_name' => 'del',
		'attrs'    => array(
			array(
				'name' => 'datetime',
			),
			array(
				'name'      => 'cite',
				'value_url' => array(
					'allowed_protocol' => array(
						'http',
						'https',
						'mailto',
						'ftp',
						'fb-messenger',
						'sms',
						'tel',
						'viber',
						'whatsapp',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
	),
	array(
		'tag_name' => 'source',
		'attrs'    => array(
			array(
				'name'      => 'src',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'type',
			),
		),
	),
	array(
		'tag_name' => 'table',
		'attrs'    => array(
			array(
				'name' => 'sortable',
			),
			array(
				'name' => 'align',
			),
			array(
				'name'        => 'border',
				'value_regex' => '0|1',
			),
			array(
				'name' => 'bgcolor',
			),
			array(
				'name' => 'cellpadding',
			),
			array(
				'name' => 'cellspacing',
			),
			array(
				'name' => 'width',
			),
		),
	),
	array(
		'tag_name' => 'colgroup',
		'attrs'    => array(
			array(
				'name' => 'span',
			),
		),
	),
	array(
		'tag_name' => 'col',
		'attrs'    => array(
			array(
				'name' => 'span',
			),
		),
	),
	array(
		'tag_name' => 'tr',
		'attrs'    => array(
			array(
				'name' => 'align',
			),
			array(
				'name' => 'bgcolor',
			),
			array(
				'name' => 'height',
			),
			array(
				'name' => 'valign',
			),
		),
	),
	array(
		'tag_name' => 'td',
		'attrs'    => array(
			array(
				'name' => 'colspan',
			),
			array(
				'name' => 'headers',
			),
			array(
				'name' => 'rowspan',
			),
			array(
				'name' => 'align',
			),
			array(
				'name' => 'bgcolor',
			),
			array(
				'name' => 'height',
			),
			array(
				'name' => 'valign',
			),
			array(
				'name' => 'width',
			),
		),
	),
	array(
		'tag_name' => 'th',
		'attrs'    => array(
			array(
				'name' => 'abbr',
			),
			array(
				'name' => 'colspan',
			),
			array(
				'name' => 'headers',
			),
			array(
				'name' => 'rowspan',
			),
			array(
				'name' => 'scope',
			),
			array(
				'name' => 'sorted',
			),
			array(
				'name' => 'align',
			),
			array(
				'name' => 'bgcolor',
			),
			array(
				'name' => 'height',
			),
			array(
				'name' => 'valign',
			),
			array(
				'name' => 'width',
			),
		),
	),
	array(
		'tag_name' => 'button',
		'attrs'    => array(
			array(
				'name' => 'disabled',
			),
			array(
				'name' => 'name',
			),
			array(
				'name'     => 'role',
				'implicit' => TRUE,
			),
			array(
				'name'     => 'tabindex',
				'implicit' => TRUE,
			),
			array(
				'name' => 'type',
			),
			array(
				'name' => 'value',
			),
		),
	),
	array(
		'tag_name' => 'amp-ad',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name' => 'json',
			),
			array(
				'name'      => 'src',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name'      => 'type',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-embed',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name' => 'json',
			),
			array(
				'name'      => 'src',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name'      => 'type',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-img',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name' => 'attribution',
			),
			array(
				'name' => 'placeholder',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
			array(
				'name'              => 'src',
				'alternative_names' => array(
					'srcset',
				),
				'mandatory'         => TRUE,
				'value_url'         => array(
					'allowed_protocol' => array(
						'data',
						'http',
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'srcset',
			),
			array(
				'name' => 'role',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-pixel',
		'attrs'    => array(
			array(
				'name'      => 'src',
				'mandatory' => TRUE,
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts'      => array(
				'FIXED',
				'NODISPLAY',
			),
			'defines_default_width'  => TRUE,
			'defines_default_height' => TRUE,
		),
	),
	array(
		'tag_name' => 'amp-video',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name' => 'attribution',
			),
			array(
				'name' => 'autoplay',
			),
			array(
				'name' => 'controls',
			),
			array(
				'name' => 'loop',
			),
			array(
				'name' => 'muted',
			),
			array(
				'name' => 'placeholder',
			),
			array(
				'name' => 'poster',
			),
			array(
				'name'        => 'preload',
				'value_regex' => '(none|metadata|auto|)',
			),
			array(
				'name'      => 'src',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-accordion',
		'attrs'    => array(
			array(
				'name' => 'animate',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'CONTAINER',
			),
		),
	),
	array(
		'tag_name' => 'section',
		'attrs'    => array(
			array(
				'name' => 'expanded',
			),
		),
	),
	array(
		'tag_name' => 'amp-analytics',
		'attrs'    => array(
			array(
				'name' => 'type',
			),
			array(
				'name'      => 'config',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
	),
	array(
		'tag_name' => 'amp-anim',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name' => 'attribution',
			),
			array(
				'name' => 'autoplay',
			),
			array(
				'name' => 'controls',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
			array(
				'name'              => 'src',
				'alternative_names' => array(
					'srcset',
				),
				'mandatory'         => TRUE,
				'value_url'         => array(
					'allowed_protocol' => array(
						'data',
						'http',
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-audio',
		'attrs'    => array(
			array(
				'name'        => 'autoplay',
				'value_regex' => '^$|desktop|tablet|mobile|autoplay',
			),
			array(
				'name' => 'controls',
			),
			array(
				'name' => 'loop',
			),
			array(
				'name' => 'muted',
			),
			array(
				'name'      => 'src',
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts'      => array(
				'FIXED',
				'FIXED-HEIGHT',
				'NODISPLAY',
			),
			'defines_default_width'  => TRUE,
			'defines_default_height' => TRUE,
		),
	),
	array(
		'tag_name' => 'amp-brid-player',
		'attrs'    => array(
			array(
				'name'        => 'data-partner',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9]+',
			),
			array(
				'name'        => 'data-player',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9]+',
			),
			array(
				'name'            => 'data-playlist',
				'mandatory_oneof' => array(
					'data-playlist' => 0,
					'data-video'    => 1,
				),
				'value_regex'     => '[0-9]+',
			),
			array(
				'name'            => 'data-video',
				'mandatory_oneof' => array(
					'data-playlist' => 0,
					'data-video'    => 1,
				),
				'value_regex'     => '[0-9]+',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-brightcove',
		'attrs'    => array(
			array(
				'name'      => 'data-account',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'data-embed',
			),
			array(
				'name' => 'data-player',
			),
			array(
				'name' => 'data-playlist-id',
			),
			array(
				'name' => 'data-video-id',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-carousel',
		'attrs'    => array(
			array(
				'name' => 'arrows',
			),
			array(
				'name' => 'autoplay',
			),
			array(
				'name' => 'controls',
			),
			array(
				'name'        => 'delay',
				'value_regex' => '[0-9]+',
			),
			array(
				'name' => 'dots',
			),
			array(
				'name' => 'loop',
			),
			array(
				'name'        => 'type',
				'value_regex' => 'slides|carousel',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-dailymotion',
		'attrs'    => array(
			array(
				'name'        => 'data-endscreen-enable',
				'value_regex' => 'true|false',
			),
			array(
				'name'        => 'data-info',
				'value_regex' => 'true|false',
			),
			array(
				'name'        => 'data-mute',
				'value_regex' => 'true|false',
			),
			array(
				'name'        => 'data-sharing-enable',
				'value_regex' => 'true|false',
			),
			array(
				'name'        => 'data-start',
				'value_regex' => '[0-9]+',
			),
			array(
				'name'             => 'data-ui-highlight',
				'value_regex_case' => '([0-9a-f]{3}){1,2}',
			),
			array(
				'name'        => 'data-ui-logo',
				'value_regex' => 'true|false',
			),
			array(
				'name'             => 'data-videoid',
				'mandatory'        => TRUE,
				'value_regex_case' => '[a-z0-9]+',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-facebook',
		'attrs'    => array(
			array(
				'name'      => 'data-href',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-fit-text',
		'attrs'    => array(
			array(
				'name' => 'max-font-size',
			),
			array(
				'name' => 'min-font-size',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-font',
		'attrs'    => array(
			array(
				'name'      => 'font-family',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'font-style',
			),
			array(
				'name' => 'font-variant',
			),
			array(
				'name'        => 'timeout',
				'value_regex' => '[0-9]+',
			),
			array(
				'name' => 'font-weight',
			),
			array(
				'name' => 'on-error-add-class',
			),
			array(
				'name' => 'on-error-remove-class',
			),
			array(
				'name' => 'on-load-add-class',
			),
			array(
				'name' => 'on-load-remove-class',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'NODISPLAY',
			),
		),
	),
	array(
		'tag_name' => 'amp-iframe',
		'attrs'    => array(
			array(
				'name'  => 'allowfullscreen',
				'value' => '',
			),
			array(
				'name'  => 'allowtransparency',
				'value' => '',
			),
			array(
				'name'        => 'frameborder',
				'value_regex' => '0|1',
			),
			array(
				'name' => 'resizable',
			),
			array(
				'name' => 'sandbox',
			),
			array(
				'name'        => 'scrolling',
				'value_regex' => 'auto|yes|no',
			),
			array(
				'name'            => 'src',
				'mandatory_oneof' => array(
					'src'    => 0,
					'srcdoc' => 1,
				),
				'value_url'       => array(
					'allowed_protocol' => array(
						'data',
						'https',
					),
					'allow_relative'   => FALSE,
				),
			),
			array(
				'name'            => 'srcdoc',
				'mandatory_oneof' => array(
					'src'    => 0,
					'srcdoc' => 1,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-image-lightbox',
		'attrs'    => array(
			array(
				'name' => 'controls',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'NODISPLAY',
			),
		),
	),
	array(
		'tag_name' => 'amp-instagram',
		'attrs'    => array(
			array(
				'name' => 'alt',
			),
			array(
				'name'            => 'data-shortcode',
				'mandatory_oneof' => array(
					'data-shortcode' => 0,
					'src'            => 1,
				),
			),
			array(
				'name'            => 'shortcode',
				'mandatory_oneof' => array(
					'data-shortcode' => 0,
					'src'            => 1,
				),
				'deprecation'     => 'data-shortcode',
				'deprecation_url' => 'https://www.ampproject.org/docs/reference/extended/amp-instagram.html',
			),
			array(
				'name'            => 'src',
				'mandatory_oneof' => array(
					'data-shortcode' => 0,
					'src'            => 1,
				),
				'value_url'       => array(
					'allowed_protocol' => array(
						'http',
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-install-serviceworker',
		'attrs'    => array(
			array(
				'name'      => 'src',
				'mandatory' => TRUE,
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'NODISPLAY',
			),
		),
	),
	array(
		'tag_name' => 'amp-jwplayer',
		'attrs'    => array(
			array(
				'name'             => 'data-media-id',
				'value_regex_case' => '[0-9a-z]{8}',
			),
			array(
				'name'             => 'data-player-id',
				'mandatory'        => TRUE,
				'value_regex_case' => '[0-9a-z]{8}',
			),
			array(
				'name'             => 'data-playlist-id',
				'value_regex_case' => '[0-9a-z]{8}',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-kaltura-player',
		'attrs'    => array(
			array(
				'name'      => 'data-partner',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-lightbox',
		'attrs'    => array(
			array(
				'name' => 'controls',
			),
			array(
				'name' => 'from',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'NODISPLAY',
			),
		),
	),
	array(
		'tag_name' => 'amp-list',
		'attrs'    => array(
			array(
				'name' => 'credentials',
			),
			array(
				'name'      => 'src',
				'mandatory' => TRUE,
				'value_url' => array(
					'allowed_protocol' => array(
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'template',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'template',
		'attrs'    => array(
			array(
				'name'      => 'type',
				'mandatory' => TRUE,
				'value'     => 'amp-mustache',
			),
		),
	),
	array(
		'tag_name' => 'amp-pinterest',
		'attrs'    => array(
			array(
				'name'      => 'data-do',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-reach-player',
		'attrs'    => array(
			array(
				'name'        => 'data-embed-id',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9a-z-]+',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-sidebar',
		'attrs'    => array(
			array(
				'name'        => 'side',
				'value_regex' => '(left|right)',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'NODISPLAY',
			),
		),
	),
	array(
		'tag_name' => 'amp-social-share',
		'attrs'    => array(
			array(
				'name'      => 'type',
				'mandatory' => TRUE,
			),
			array(
				'name'      => 'data-share-endpoint',
				'value_url' => array(
					'allowed_protocol' => array(
						'ftp',
						'http',
						'https',
						'mailto',
						'fb-messenger',
						'snapchat',
						'sms',
						'tel',
						'viber',
						'whatsapp',
					),
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'CONTAINER',
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-soundcloud',
		'attrs'    => array(
			array(
				'name'             => 'data-color',
				'value_regex_case' => '([0-9a-f]{3}){1,2}',
			),
			array(
				'name'        => 'data-trackid',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9]+',
			),
			array(
				'name'        => 'data-visual',
				'value_regex' => 'true|false',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FIXED-HEIGHT',
			),
		),
	),
	array(
		'tag_name' => 'amp-springboard-player',
		'attrs'    => array(
			array(
				'name'      => 'data-content-id',
				'mandatory' => TRUE,
			),
			array(
				'name'      => 'data-domain',
				'mandatory' => TRUE,
			),
			array(
				'name'      => 'data-items',
				'mandatory' => TRUE,
			),
			array(
				'name'             => 'data-mode',
				'mandatory'        => TRUE,
				'value_regex_case' => 'playlist|video',
			),
			array(
				'name'             => 'data-player-id',
				'mandatory'        => TRUE,
				'value_regex_case' => '[a-z0-9]+',
			),
			array(
				'name'        => 'data-site-id',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9]+',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FLEX-ITEM',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-twitter',
		'attrs'    => array(
			array(
				'name'            => 'data-tweetid',
				'mandatory_oneof' => array(
					'data-tweetid' => 0,
					'src'          => 1,
				),
			),
			array(
				'name'            => 'src',
				'mandatory_oneof' => array(
					'data-tweetid' => 0,
					'src'          => 1,
				),
				'value_url'       => array(
					'allowed_protocol' => array(
						'http',
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-vimeo',
		'attrs'    => array(
			array(
				'name'        => 'data-videoid',
				'mandatory'   => TRUE,
				'value_regex' => '[0-9]+',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-vine',
		'attrs'    => array(
			array(
				'name'      => 'data-vineid',
				'mandatory' => TRUE,
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-youtube',
		'attrs'    => array(
			array(
				'name'            => 'data-videoid',
				'mandatory_oneof' => array(
					'src'          => 0,
					'data-videoid' => 1,
				),
			),
			array(
				'name'            => 'src',
				'mandatory_oneof' => array(
					'src'          => 0,
					'data-videoid' => 1,
				),
				'value_url'       => array(
					'allowed_protocol' => array(
						'http',
						'https',
					),
					'allow_relative'   => TRUE,
				),
			),
			array(
				'name'            => 'video-id',
				'mandatory_oneof' => array(
					'src'          => 0,
					'data-videoid' => 1,
				),
				'deprecation'     => 'data-videoid',
				'deprecation_url' => 'https://www.ampproject.org/docs/reference/extended/amp-youtube.html',
			),
			array(
				'name' => 'media',
			),
			array(
				'name' => 'noloading',
			),
		),
		'layouts'  => array(
			'supported_layouts' => array(
				'FILL',
				'FIXED',
				'FIXED-HEIGHT',
				'FLEX-ITEM',
				'NODISPLAY',
				'RESPONSIVE',
			),
		),
	),
	array(
		'tag_name' => 'amp-auto-ads',
		'attrs'    => array(
			array(
				'name'      => 'type',
				'mandatory' => TRUE,
			),
		),
	),
	array(
		'tag_name' => 'span',
		'attrs'    => array(
			array(
				'name' => 'rel',
			),
		),
	),
);