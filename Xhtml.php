<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 *	@package XHTML
 *	@version 1.0
 *	@license MIT License
 *
 *	@author Serafim Junior Dos Santos Fagundes <serafim@cyb3r.ca>
 *	@copyright Serafim Junior Dos Santos Fagundes Cyb3r Network
 *
 *	File containing classes for XHTML document generation. 
 */


/**
 *	@name CYB3RNET_CI_XHTML Constant for detection and interaction with other libraries
 */
define( 'CYB3RNET_CI_XHTML', '20100429_1' );
 

/**
 *	@name XHTMLSHORTLANG Constant for the hash array of the constructor parameter; key of the document language
 */
define( 'XHTMLSHORTLANG', 'short_lang' );


/**
 *	@name XHTMLENCODING Constant for the hash array of the constructor parameter; key of the document encoding
 */
define( 'XHTMLENCODING', 'encoding' );


/**
 *	Class for generating XHTML documents
 */
class Xhtml
{
	/**
	 *	Hash holding the tags by key/tag name and value/object
	 *
	 *	@access private
	 */
	var $_aTags;
	
	
	/**
	 *	Holds the XHTML document object
	 *
	 *	@access private
	 */
	var $_oDoc;


	/**
	 *	Holds the XHTML document string
	 *
	 *	@access private
	 */
	var $_sDoc;


	/**
	 *	@param array $aParams [short_lang] for shortened language name; [encoding] for encoding used
	 *
	 *	@access public
	 */
	function Xhtml( $aParams )
	{
		if ( $this->_validate_init_params( $aParams ) )
		{
			$this->_sLang = $aParams[XHTMLSHORTLANG];
			$this->_sEncoding = $aParams[XHTMLENCODING];
		}
		else
		{
			show_error( 'Instanciation parameters of '.__CLASS__.' are not valid', 500 );
		}
	
		$this->_oCI =& get_instance();
		
		$this->_oCI->load->helper( 'html' );
		
		$this->_aTags = array();
		
		$this->_oDoc = NULL;
		$this->_sDoc = "";
	}
	
	
	/**
	 *	Validates instanciation parameters; returns TRUE on valid, FALSE otherwise
	 *
	 *	@param array $aParams Parameters passed in class instanciation
	 *
	 *	@access private
	 *	@return bool
	 */
	function _validate_init_params( $aParams )
	{
		$bShortLangOK = array_key_exists( XHTMLSHORTLANG, $aParams );
		$bEncodingOK = array_key_exists( XHTMLENCODING, $aParams );
		
		return ( $bShortLangOK && $bEncodingOK );
	}
	
	
	/**
	 *	Creates a tag and returns it
	 *
	 *	@param string $sTagName Tag name to be created
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *	@param bool $bHasEnd Indicates if tag has end; true full end, false self closed
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *
	 *	@return object
	 *	@access private
	 */
	function _create_tag( $sTagName, $aAttrs, $bHasEnd, $vContent )
	{
		if ( ! is_null( $vContent ) )
			$oMULC = new MULContent( $sTagName, $vContent );
		else
			$oMULC = NULL;
	
		$oMULO = NULL;
	
		if ( array_key_exists( $sTagName, $this->_aTags ) )
		{
			$oMULO =& $this->_aTags[$sTagName];
			
			if ( count( $aAttrs ) > 0 )
				$oMULO->add_attrs( $aAttrs );
			
			if ( ! is_null( $oMULC ) )
				$oMULO->append( $oMULC );
		}
		else
		{
			$oMULO = new MULObject( $sTagName, $aAttrs, $bHasEnd, $oMULC );
		}

		$this->_aTags[$sTagName] = $oMULO;
		
		return $oMULO;
	}
	
	
	/*
	 *	*********
	 *	Head Tags
	 *	*********
	 */
	
	
	/**
	 *	Creates a head tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function head( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}
	

	/**
	 *	Creates a title tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function title( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}
	

	/**
	 *	Creates a base tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function base( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a meta tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function meta( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a link tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function link( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a style tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function style( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;		
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}

	/**
	 *	Creates a script tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function script( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}

	/**
	 *	Creates a noscript tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function noscript( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/*
	 *	*********
	 *	Body Tags
	 *	*********
	 */


	/**
	 *	Creates a body tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function body( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a div tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function div( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a p tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function p( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h1 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h1( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h2 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h2( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h3 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h3( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h4 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h4( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h5 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h5( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a h6 tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function h6( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a ul tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function ul( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a ol tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function ol( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a li tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function li( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a dl tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function dl( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a dt tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function dt( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a dd tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function dd( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a address tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function address( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a hr tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function hr($aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL);
	}


	/**
	 *	Creates a pre tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function pre( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}




	/**
	 *	Creates a blockquote tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function blockquote( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a ins tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function ins( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a del tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function del( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a a tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function a( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a span tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function span( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a bdo tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function bdo( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a br tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function br( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a em tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function em( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a strong tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function strong( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a dfn tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function dfn( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a code tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function code( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a samp tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function samp( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a kbd tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function kbd( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a var tag
	 *
	 *	Renamed x_var to resolve clash with PHP parser in conflict with the var keyword.
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function x_var( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a cite tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function cite( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a abbr tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function abbr( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a acronym tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function acronym( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a q tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function q( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a sub tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function sub( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a sup tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function sup( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a tt tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function tt( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a i tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function i( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a b tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function b( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a big tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function big( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a small tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function small( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a object tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function object( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a param tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function param( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a img tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function img( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a map tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function map( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a area tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function area( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/*
	 *	*********
	 *	Form Tags
	 *	*********
	 */


	/**
	 *	Creates a form tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function form( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a label tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function label( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a input tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function input( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a textarea tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function textarea( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a select tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function select( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a option tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function option( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a optgroup tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function optgroup( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a fieldset tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function fieldset( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a legend tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function legend( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a button tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function button( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/*
	 *	**********
	 *	Table Tags
	 *	**********
	 */


	/**
	 *	Creates a table tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function table( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a caption tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function caption( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a thead tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function thead( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a tfoot tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function tfoot( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a tbody tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function tbody( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a colgroup tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function colgroup( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a col tag
	 *
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function col( $aAttrs = array() )
	{
		$bHasEnd = FALSE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, NULL );
	}


	/**
	 *	Creates a tr tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function tr( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a th tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function th( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/**
	 *	Creates a td tag
	 *
	 *	@param mixed $vContent Content of the tag; XHTML object or string
	 *	@param array $aAttrs Hash of attributes; Name/Value pairs
	 *
	 *	@return object
	 *	@access public
	 */
	function td( $vContent = NULL, $aAttrs = array() )
	{
		$bHasEnd = TRUE;
		return $this->_create_tag( __FUNCTION__, $aAttrs, $bHasEnd, $vContent );
	}


	/*
	 *	*************
	 *	Root document
	 *	*************
	 */
	 

	/**
	 *	Creates a html tag
	 *
	 *	@param object $oHead Head of the to be XHTML document
	 *	@param object $oBody Body of the to be XHTML document
	 *
	 *	@return object
	 *	@access public
	 */
	function doc( $oHead, $oBody )
	{
		$aAttrs = array();
		
		$aAttrs['lang'] = $this->_sLang;
	
		$this->_create_tag( "html", $aAttrs, TRUE, $oHead );
		
		$this->_oDoc = $this->_create_tag( "html", array(), TRUE, $oBody );
		
		return $this->_oDoc;
	}
	
	
	/**
	 *	Generates the html tag with its content
	 *
	 *	@return string
	 *	@access public
	 */
	function generate()
	{
		if ( ! is_null( $this->_oDoc ) )
		{
    			$this->_sDoc = doctype(); 		
		
			$this->_sDoc .= $this->_oDoc->generate();

			return $this->_sDoc;
		}
		else
			show_error( 'You must use the <big>doc</big> method before using <big>output</big> or <big>generate</big> methods', 500 );
	}
	
	
	/**
	 *	Echoes the generated html document
	 *
	 *	@access public
	 */
	function output()
	{
		if ( strlen( $this->_sDoc ) > 0)
		{
			echo $this->_sDoc;
		}
		else
		{
			echo $this->generate();
		}
	}
}


/**
 *	Class for SGML MarkUp Language type generation
 */
class MULObject
{
	/**
	 *	@param string $sTagName Tag name of the markup object
	 *	@param array $aAttrs Hash of attributes name/value pairs
	 *	@param bool $bHasEnd Indicates if markup has end; true for full end, false for self closed
	 *	@param mixed $vContent Content of the markup; either a MULObject object, a MULContent object or a string
	 *
	 *	@access public
	 */
	function MULObject( $sTagName, $aAttrs = array(), $bHasEnd = TRUE, $vContent = NULL )
	{
		$this->_sTagName = $sTagName;
		$this->_bHasEnd = $bHasEnd;

		$this->_aAttrs = array();
		$this->_sContent = "";
				
		if ( count( $aAttrs ) > 0 )
		{
			$this->add_attrs( $aAttrs );
		}
		
		if ( ! is_null( $vContent ) )
		{
			$this->append( $vContent );
		}

		$this->_sAttrs = "";
	}
	
	
	/**
	 *	Assembles attributes from hash array
	 *
	 *	@access private
	 */
	function _assemble_attributes()
	{
		foreach ( $this->_aAttrs as $sName => $sValue )
		{
			$this->_sAttrs .= ' '.$sName.'="'.$sValue.'"';
		}
	}
	
	
	/**
	 *	Appends content in the markup
	 *
	 *	@param mixed $vContent Content to be appended to the markup; must be a MULObject object, a MULContent object or a string
	 *
	 *	@access public
	 */
	function append( $vContent )
	{
		if ( is_a( $vContent, 'MULContent' ) )
		{
			$sSource = $vContent->get_source();
			$vContent = $vContent->get_content();
		}

		if ( is_null( $vContent ) )
		{
			$this->_sContent = "";
		}
		else if ( is_string( $vContent ) )
		{
			$this->_sContent .= $vContent;
		}
		else if ( is_a( $vContent, 'MULObject' ) )
		{
			$this->_sContent .= $vContent->generate();
		}
		else
		{
			show_error( 'Content inserted in '.$sSource.' must be a <big>string</big> or an object generated by <big>$this->xhtml</big>', 500 );
		}
	}
	
	
	/**
	 *	Add a hash array of attributes to the object list
	 *
	 *	@param array $aAttrs Hash array to add to the attribute list
	 *
	 *	@access public
	 */
	function add_attrs( $aAttrs )
	{
		$this->_aAttrs = array_merge( $this->_aAttrs, $aAttrs );
	}
	
	
	/**
	 *	Generates the markup entity
	 *
	 *	@access public
	 *	@return string
	 */
	function generate()
	{
		if ( count( $this->_aAttrs ) > 0 )
		{
			$this->_assemble_attributes();
		}
	
		$sTag = '<'.$this->_sTagName.$this->_sAttrs;
		
		if ( ! $this->_bHasEnd )
		{
			$sTag .= '/>';
		}
		else
		{
			$sTag .= '>'.$this->_sContent.'</'.$this->_sTagName.'>';
		}
	
		return $sTag;
	}
}


/**
 *	Class for holding content reference for MULObject->append()
 */
class MULContent
{
	/**
	 *	@param string $sSource Method name inserting the content
	 *	@param mixed $vContent Content for MULObject; either a MULObject or a string
	 *
	 *	@access public
	 */
	function MULContent( $sSource = "", $vContent = NULL )
	{
		$this->_sSource = $sSource;
		$this->_vContent = $vContent;
	}
	
	
	/**
	 *	Returns the content to be inserted in the MULObject
	 *
	 *	@access public
	 *	@return mixed
	 */
	function get_content()
	{
		return $this->_vContent;
	}
	
	
	/**
	 *	Returns the method name inserting the content
	 *
	 *	@access public
	 *	@return string
	 */
	function get_source()
	{
		return $this->_sSource;
	}
}

?>
