<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
*  All rights reserved
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
 * JavaScript Code Snippet
 * Representing a JavaScript Variable that has a extjs Constructor Call on the right side
 * 
 * $Namespace.$name = new Ext.someClass($config, $parameters as csv);
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor extends Tx_MvcExtjs_CodeGeneration_JavaScript_Variable {
	
	/**
	 * The extjs Class that should be instanciated
	 * 
	 * @var string
	 */
	protected $class;
	
	/**
	 * The extjs config array
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $config;
	
	/**
	 * Parameters for the constructor, additional to the config object.
	 * The config object will always be the first parameter
	 * 
	 * @var array
	 */
	protected $parameters;
	
	/**
	 * Internal used variable that will be filled up with class, config and parameters when build the js code
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ConstructorCall
	 */
	private $constructorCall;
	
	/**
	 * Default constructor
	 * 
	 * @param string $name
	 * @param string $class
	 * @param array $inlineDeclarations
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_Object $parameters HAS NO EFFECT ATM
	 * @param mixed $namespace FALSE or string
	 */
	public function __construct($name = NULL,
								$class = NULL,
								Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config,
								array $parameters,
								$namespace = FALSE) {
		
		foreach ($parameters as $snippet) {
			if (!$snippet instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a parameter for a constrcutor has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
			}
		}
		
		$this->class = $class;
		$this->config = $config;
		$this->parameters = $parameters;
		$this->constructorCall = new Tx_MvcExtjs_CodeGeneration_JavaScript_ConstructorCall($name);
		
		parent::__construct($name,NULL,FALSE,$namespace);
	}
	
	/**
	 * Sets the class that should be extended
	 * 
	 * @param string $class
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	/**
	 * Adds a config parameter to the constructor of the object that should be extended
	 * 
	 * @param string $name
	 * @param string $value
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor
	 */
	public function addConfig($name,$value) {
		$this->config->set($name,$value);
		return $this;
	}
	
	/**
	 * Adds a config parameter to to configuration of the extjs object
	 * The given parameter is outputted raw (not quoted)
	 * 
	 * @param string $name
	 * @param mixed $value string or something that implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor
	 */
	public function addRawConfig($name,$value) {
		$this->config->setRaw($name,$value);
		return $this;
	}
	
	/**
	 * Sets the config for the Constructor
	 * 
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor
	 */
	public function setConfig(Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config) {
		$this->config = $config;
		return $this;
	}
	
	/**
	 * Adds a parameter to the constructor
	 * 
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $parameter
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor
	 */
	public function addParameter(Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $parameter) {
		$this->parameters[] = $parameter;
		return $this;
	}
	
	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_Variable#build()
	 */
	public function build() {
		$this->value = new Tx_MvcExtjs_CodeGeneration_JavaScript_ConstructorCall($this->class,array($this->config));
		foreach ($this->parameters as $snippet) {
			$this->value->addParameter($snippet);
		}
		return parent::build();
	}
	
	/**
	 * Wraps build() as __toString()
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->build();
	}
	
}

?>