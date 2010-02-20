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
 * Representing a ExtExtend call
 * Use this to provide extjs objects preconfigured for your needs
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass extends Tx_MvcExtjs_CodeGeneration_JavaScript_Variable {
	
	/**
	 * @var string
	 */
	protected $class;
	
	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $config;
	
	/**
	 * @var array
	 */
	protected $inlineDeclarations;
	
	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Object
	 */
	protected $additionalFunctions;
	
	/**
	 * The internal used AnonymFunction Object
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration
	 */
	protected $constructorFunction;
	
	/**
	 * Default constructor
	 * 
	 * @param string $name
	 * @param string $class
	 * @param array $inlineDeclarations
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_Object $additionalFunctions HAS NO EFFECT ATM
	 * @param mixed $namespace FALSE or string
	 */
	public function __construct($name = NULL,
								$class = NULL,
								array $inlineDeclarations = array(),
								Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config,
								Tx_MvcExtjs_CodeGeneration_JavaScript_Object $additionalFunctions = NULL,
								$namespace = FALSE) {
		
		foreach ($inlineDeclarations as $snippet) {
			if (!$snippet instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a inlinedeclaration for the has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
			}
		}
		
		$this->class = $class;
		$this->config = $config;
		$this->inlineDeclarations = $inlineDeclarations;
		$this->additionalFunctions = $additionalFunctions;
		$this->constructorFunction = new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration(array('config'),$this->inlineDeclarations,TRUE);
		
		parent::__construct($name,NULL,FALSE,$namespace);
	}
	
	/**
	 * Sets the class that should be extended
	 * 
	 * @param string $class
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	/**
	 * Adds a config parameter
	 * 
	 * @param string $name
	 * @param string $value
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	public function addConfig($name,$value) {
		$this->config->set($name,$value);
		return $this;
	}
	
	/**
	 * Adds a raw config parameter
	 * 
	 * @param string $name
	 * @param mixed $value string or something that implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	public function addRawConfig($name,$value) {
		$this->config->setRaw($name,$value);
		return $this;
	}
	
	/**
	 * Sets a config object for the extend constructor
	 * 
	 * @param $config
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	public function setConfig(Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config $config) {
		$this->config = $config;
		return $this;
	}
	
	/**
	 * Adds a function to the new class definition
	 * 
	 * @param string $name
	 * @param array $parameter
	 * @param mixed $content string or Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 * @return void
	 */
	public function addFunction($name, array $parameters = array(), $content) {
		if(!is_string($content) && !$content instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface)
			throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('content has to be string or a object implementing Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface', 1265284984);
		if(is_string($content))
			$content = new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($content);	
		$objectElement = new Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement($name);
		$function = new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration($parameters,array($content),true);
		$objectElement->setValue($function);
		$this->additionalFunctions->addElement($objectElement);
	}
	
	/**
	 * Gets the config object from the extend constructor
	 * 
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	public function getConfig() {
		return $this->config;
	}
	
	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_Variable#build()
	 */
	public function build() {
		$configConstructor = new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall('Ext.apply',array($this->config,new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet('config')));
		$configVariable = new Tx_MvcExtjs_CodeGeneration_JavaScript_Variable('config',$configConstructor);
				
		$this->inlineDeclarations[] = $configVariable;
		
		$superClassConstructorName = $this->namespace . '.' . $this->name . '.superclass.constructor.call';
		$superClassCall = new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall($superClassConstructorName,array(new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet('this'),new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet('config')));
		
		$this->inlineDeclarations[] = $superClassCall;

		$this->constructorFunction->setContent($this->inlineDeclarations);
		
		$extExtendConfigObjectArray = array(new Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement('constructor',$this->constructorFunction));
		$extExtendConfig = new Tx_MvcExtjs_CodeGeneration_JavaScript_Object($extExtendConfigObjectArray);
		
		$additionalFunctionElements = $this->additionalFunctions->getElements();
		
		foreach($additionalFunctionElements as $element) {
			$extExtendConfig->addElement($element);
		}
		
		$extendParameters = array(
			new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($this->class),
			$extExtendConfig,
		);
		$this->setValue(new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall('Ext.extend',$extendParameters));
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