<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Sysmanager specific dwoo plugins
 *
 * @package		Todoyu
 * @subpackage	Template
 */



/**
 * Checks whether current extension has records registered
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	String
 */
function Dwoo_Plugin_extMgr_hasRecords_compile(Dwoo_Compiler $compiler, $extKey) {
	return 'sizeof(TodoyuSysmanagerExtManager::getRecordTypes(' . $extKey . ')) > 0';
}



/**
 * Checks whether extension has rights config registered
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	Boolean
 */
function Dwoo_Plugin_extMgr_hasRighsConfig_compile(Dwoo_Compiler $compiler, $extKey) {
	return 'TodoyuSysmanagerRightsEditorManager::hasRightsConfig(' . $extKey . ')';
}



/**
 * Checks whether extension has something to configure
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	String
 */
function Dwoo_Plugin_extMgr_hasConfig_compile(Dwoo_Compiler $compiler, $extKey) {
	return 'TodoyuSysmanagerExtManager::extensionHasConfig(' . $extKey . ')';
}



/**
 * Checks whether extension has informations registered
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	String
 */
function Dwoo_Plugin_extMgr_hasExtInfo_compile(Dwoo_Compiler $compiler, $extKey) {
	return 'TodoyuSysmanagerExtManager::getExtInfos(' . $extKey . ') !== false';
}


/**
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	String
 */
function Dwoo_Plugin_extMgr_isSysExt_compile(Dwoo_Compiler $compiler, $extKey) {
	return 'TodoyuSysmanagerExtManager::isSysExt(' . $extKey . ')';
}



/**
 * Render extension icon image tag (if exists)
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$extKey
 * @return	String
 */
function Dwoo_Plugin_extIcon_compile(Dwoo_Compiler $compiler, $extKey) {
	return "'<img src=\"ext/' . " . $extKey . " . '/asset/img/exticon.png\" width=\"16\" height=\"16\" />'";
}



/**
 * Convert the state key into an state image
 *
 * @param	Dwoo		$dwoo
 * @param	Integer		$state
 * @return	String
 */
function Dwoo_Plugin_ExtensionStatusIcon(Dwoo $dwoo, $state) {
	$states	= array(
		0		=> 'alpha',
		1		=> 'beta',
		2		=> 'stable',
		'alpha'	=> 'alpha',
		'beta'	=> 'beta',
		'stable'=> 'stable'
	);

	if( ! array_key_exists($state, $states) ) {
		$state	= 'alpha';
	} else {
		$state	= $states[$state];
	}

	return '<span class="extensionstate ' . $state . '"></span>';
}

?>