<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Represents a Ext.Direct request.
 *
 *
 * TODO: When extbase request handling is made dont extends Tx_Extbase_MVC_Web_Request
 * but extend Tx_Extbase_MVC_Request
 * @package MvcExtjs
 * @subpackage MVC\Web
 * @version $ID:$
 *
 * @scope prototype
 * @api
 */
class Tx_MvcExtjs_MVC_ExtDirect_Request extends Tx_Extbase_MVC_Web_Request {
	
	/**
	 * @var int The transaction id.
	 */
	protected $tid = 0;
	
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var string The requested representation format
	 */
	protected $format = 'json';

	/**
	 * @var string Contains the request method
	 */
	protected $method = 'POST';

	/**
	 * @var string
	 */
	protected $requestURI;

	/**
	 * @var string The base URI for this request - ie. the host and path leading to the index.php
	 */
	protected $baseURI;

	/**
	 * @var boolean TRUE if the current request is cached, false otherwise.
	 */
	protected $isCached = FALSE;
	
	/**
	 * Sets the Transaction Id (tid).
	 * 
	 * @param int $tid
	 * @return void
	 */
	public function setTransactionId($tid) {
		$this->tid = $tid;
	}
	
	/**
	 * Returns the Transaction Id (tid).
	 * 
	 * @return int
	 */
	public function getTransactionId() {
		return $this->tid;
	}
	
	/**
	 * Returns the type.
	 * 
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Gets the type.
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Sets the request method
	 *
	 * @param string $method Name of the request method
	 * @return void
	 * @throws Tx_Extbase_MVC_Exception_InvalidRequestMethod if the request method is not supported
	 */
	public function setMethod($method) {
		if ($method === '' || (strtoupper($method) !== $method)) throw new Tx_Extbase_MVC_Exception_InvalidRequestMethod('The request method "' . $method . '" is not supported.', 1217778382);
		$this->method = $method;
	}

	/**
	 * Returns the name of the request method
	 *
	 * @return string Name of the request method
	 * @api
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Sets the request URI
	 *
	 * @param string $requestURI URI of this web request
	 * @return void
	 */
	public function setRequestURI($requestURI) {
		$this->requestURI = $requestURI;
	}

	/**
	 * Returns the request URI
	 *
	 * @return string URI of this web request
	 * @api
	 */
	public function getRequestURI() {
		return $this->requestURI;
	}

	/**
	 * Sets the base URI for this request.
	 *
	 * @param string $baseURI New base URI
	 * @return void
	 */
	public function setBaseURI($baseURI) {
		$this->baseURI = $baseURI;
	}

	/**
	 * Returns the base URI
	 *
	 * @return string Base URI of this web request
	 * @api
	 */
	public function getBaseURI() {
		if (TYPO3_MODE === 'BE') {
			return $this->baseURI . TYPO3_mainDir;
		} else {
			return $this->baseURI;
		}
	}
	
	/**
	 * Set if the current request is cached.
	 * 
	 * @param boolean $isCached
	 */
	public function setIsCached($isCached) {
		$this->isCached = (boolean) $isCached;
	} 
	/**
	 * Return whether the current request is a cached request or not.
	 * 
	 * @api (v4 only)
	 * @return boolean the caching status.
	 */
	public function isCached() {
		return $this->isCached;
	}
}
?>