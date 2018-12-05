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
	public function __construct( $html = '', $encoding = 'UTF-8', $version = null ) {

		parent::__construct( $version, $encoding );

		if ( $html ) {
			$this->loadHTML( $html );
		}
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
	 * Add attributes to the node
	 *
	 * @param DOMElement $node
	 * @param array      $attributes key-value paired attributes
	 *
	 * @since 1.1
	 */
	public function remove_attributes( &$node, $attributes ) {

		foreach ( $attributes as $name ) {
			$node->removeAttribute( $name );
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
	 * @param bool $body_element return just body elements
	 *
	 * @return string body tag inner HTML
	 */
	public function get_content( $body_element = true ) {

		if ( $body_element ) {

			if ( preg_match( '#<\s*body[^>]*>(.+)<\s*/\s*body\s*>#isx', $this->saveHTML(), $match ) ) {

				return $match[1];
			}

			return '';
		}

		return $this->saveHTML();
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
	 * Get an attribute of an element.
	 *
	 * @param DOMElement $node
	 * @param string     $tag_name
	 * @param string     $attribute
	 *
	 * @since 1.9.6
	 *
	 * @return string|null string on success
	 */
	public static function get_child_tag_attribute( $node, $tag_name, $attribute ) {

		if ( $child = self::child( $node, $tag_name, array( $attribute ) ) ) {

			if ( $attr = $child->attributes->getNamedItem( $attribute ) ) {

				return $attr->value;
			}
		}
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
	 *
	 * @link  http://php.net/manual/domdocument.loadhtml.ph
	 *
	 * @param string   $html          The HTML string
	 * @param null|int $options       - nothing! just for prevent trigger PHP Strict warning!
	 * @param bool     $wrap_body_tag wrap content into html>body tag
	 *
	 * @return bool true on success or false on failure.
	 * @since 1.0.0
	 *
	 */
	public function loadHTML( $html, $options = null, $wrap_body_tag = true ) {

		$prev = libxml_use_internal_errors( true );

		if ( $wrap_body_tag ) {
			parent::loadHTML( '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head><body>' . $html . '</body></html>' );
		} else {
			$options = 0;

			if ( defined( 'LIBXML_HTML_NODEFDTD' ) ) { // Libxml >= 2.7.8
				$options |= LIBXML_HTML_NODEFDTD;
			}

			if ( defined( 'LIBXML_HTML_NOIMPLIED' ) ) { // Libxml >= 2.7.7
				$options |= LIBXML_HTML_NOIMPLIED;
			}

			if ( $options ) {
				parent::loadHTML( $html, $options );
			} else { // support for old php version
				parent::loadHTML( $html );
			}
		}

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


	/**
	 * Get Single Children Element
	 *
	 * @param DOMElement $node
	 * @param string     $tag_name HTML Tag Name
	 * @param array      $required_atts
	 *
	 * @return bool|DOMElement DOMElement on success or false on failure
	 * @since 1.1
	 */
	public static function child( $node, $tag_name, $required_atts = array() ) {

		if ( empty( $node->childNodes ) ) {
			return false;
		}

		$tag_name = strtolower( $tag_name );

		/**
		 * @var DOMElement $child_node
		 */
		foreach ( $node->childNodes as $child_node ) {

			if ( $tag_name === $child_node->tagName ) {

				foreach ( $required_atts as $attr ) {

					if ( ! $child_node->hasAttribute( $attr ) ) {
						continue 2;
					}
				}

				return $child_node;
			}
		}

		return false;
	}


	/**
	 * Rename element tag name
	 *
	 * @param DOMElement $element
	 * @param string     $newName
	 *
	 * @see   http://stackoverflow.com/questions/12463550/rename-an-xml-node-using-php
	 *
	 * @since 1.1
	 */
	public static function renameElement( $element, $newName ) {

		$newElement    = $element->ownerDocument->createElement( $newName );
		$parentElement = $element->parentNode;
		$parentElement->insertBefore( $newElement, $element );

		$childNodes = $element->childNodes;
		while( $childNodes->length > 0 ) {
			$newElement->appendChild( $childNodes->item( 0 ) );
		}

		$attributes = $element->attributes;
		while( $attributes->length > 0 ) {
			$attribute = $attributes->item( 0 );
			if ( ! is_null( $attribute->namespaceURI ) ) {
				$newElement->setAttributeNS( 'http://www.w3.org/2000/xmlns/',
					'xmlns:' . $attribute->prefix,
					$attribute->namespaceURI );
			}
			$newElement->setAttributeNode( $attribute );
		}

		$parentElement->removeChild( $element );
	}


	/**
	 * Append given HTML into the element.
	 *
	 * @param DOMElement $element
	 * @param string     $html
	 *
	 * @since 1.9.3
	 */
	public static function set_inner_HTML( $element, $html ) {

		$fragment = $element->ownerDocument->createDocumentFragment();
		$fragment->appendXML( $html );

		while( $element->hasChildNodes() ) {
			$element->removeChild( $element->firstChild );
		}

		$element->appendChild( $fragment );
	}


	/**
	 * Replace element with given html.
	 *
	 * @param DOMElement $element
	 * @param string     $html
	 *
	 * @since 1.9.3
	 */
	public static function set_outer_HTML( $element, $html ) {

		$fragment = $element->ownerDocument->createDocumentFragment();
		$fragment->appendXML( $html );

		if ( $element->parentNode ) {
			$element->parentNode->appendChild( $fragment );
		}

		while( $element->parentNode && $element->parentNode->hasChildNodes() ) {

			$element->parentNode->removeChild( $element->parentNode->firstChild );
		}
	}

}
