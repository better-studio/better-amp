<?php

/**
 * DOMDocument Helper class
 *
 * utility function for working with php DOMDocument class
 *
 * @since 1.0.0
 */
class Better_AMP_HTML_Util extends DOMDocument {

	/**
	 * Better_AMP_HTML_Util constructor.
	 *
	 * @param string     $html
	 * @param string     $encoding optional. The encoding of the document as part of the html declaration. default UTF-8
	 * @param string|int $version  optional. The version number of the document as part of the html declaration.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $html = '', $encoding = 'UTF-8', $version = NULL ) {

		parent::__construct( $version, $encoding );

		$this->loadHTML( $html );
	}

	/**
	 * Add attributes to the node
	 *
	 * @param DOMElement $node
	 * @param array      $attributes key-value paired attributes
	 *
	 * @since 1.0.0
	 */
	public function add_attributes( &$node, $attributes ) {

		foreach ( $attributes as $name => $value ) {
			$node->setAttribute( $name, $value );
		}

	}

	/**
	 * Create a HTML Node
	 *
	 * @param string $tag        node tag name
	 * @param array  $attributes key-value paired attributes
	 *
	 * @since 1.0.0
	 *
	 * @return DOMElement
	 */
	public function create_node( $tag, $attributes ) {

		$node = $this->createElement( $tag );

		$this->add_attributes( $node, $attributes );

		return $node;
	}


	/**
	 * Returns body
	 *
	 * @since 1.0.0
	 *
	 * @return \DOMNode
	 */
	public function get_body_node() {
		return $this->getElementsByTagName( 'body' )->item( 0 );
	}

	/**
	 * Remove <body> tag and return body inner html
	 *
	 * @since 1.0.0
	 *
	 * @return string body tag inner HTML
	 */
	public function get_content() {

		$HTML = '';

		$body = $this->get_body_node();

		foreach ( $body->childNodes as $node ) {
			$HTML .= $this->saveXML( $node, LIBXML_NOEMPTYTAG );
		}

		return $HTML;
	}


	/**
	 *
	 * Get attributes of the element
	 *
	 * @param DOMElement $node
	 *
	 * @since 1.0.0
	 *
	 * @return array key-value paired attributes
	 */
	public static function get_node_attributes( $node ) {

		$attributes = array();

		foreach ( $node->attributes as $attribute ) {
			$attributes[ $attribute->nodeName ] = $attribute->nodeValue;
		}

		return $attributes;
	}


	/**
	 * Replace node with new node
	 *
	 * @param DOMElement $node2replace   a node to replace with
	 * @param     string $new_tag        new node tag name
	 * @param      array $new_attributes key-value paired attributes
	 *
	 * @since 1.0.0
	 *
	 * @return DOMNode The old node or false if an error occur.
	 */
	public function replace_node( $node2replace, $new_tag, $new_attributes ) {

		$new_node = $this->create_node( $new_tag, $new_attributes );

		return $node2replace->parentNode->replaceChild( $new_node, $node2replace );
	}


	/**
	 * Todo: filter attributes
	 *
	 * @param array $attributes key-value paired attributes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function filter_attributes( $attributes ) {

		if ( isset( $attributes['width'] ) ) { // sanitize width attribute value
			$attributes['width'] = Better_AMP_Content_Sanitizer::sanitize_dimension( $attributes['width'], 'width' );
		}

		if ( isset( $attributes['height'] ) ) { // sanitize height attribute value
			$attributes['height'] = Better_AMP_Content_Sanitizer::sanitize_dimension( $attributes['height'], 'height' );
		}

		return $attributes;
	}


	/**
	 * Load HTML from a string
	 * @link  http://php.net/manual/domdocument.loadhtml.ph
	 *
	 * @param string $html    The HTML string
	 * @param null   $options - nothing! just for prevent trigger PHP Strict warning!
	 *
	 * @since 1.0.0
	 *
	 * @return bool true on success or false on failure.
	 */
	public function loadHTML( $html, $options = NULL ) {

		$prev = libxml_use_internal_errors( TRUE );

		parent::loadHTML( '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head><body>' . $html . '</body></html>' );

		libxml_use_internal_errors( $prev );
		libxml_clear_errors();
	}

	/**
	 * Whether to check $node is empty
	 *
	 * @param DOMElement $node
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_node_empty( $node ) {
		return 0 === $node->childNodes->length && empty( $node->textContent );
	}
}
