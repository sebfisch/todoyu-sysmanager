<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

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
 * @param	Dwoo	$dwoo
 * @param	String	$extKey
 * @return	Bool
 */
function Dwoo_Plugin_extMgr_hasRecords_compile(Dwoo_Compiler $dwoo, $extKey)	{
	return 'sizeof(TodoyuExtManager::getRecordTypes(' . $extKey . ')) > 0';
}



/**
 * Checks whether extension has rights config registered
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo	$dwoo
 * @param	String	$extKey
 * @return	Bool
 */
function Dwoo_Plugin_extMgr_hasRighsConfig_compile(Dwoo_Compiler $dwoo, $extKey)	{
	return 'TodoyuRightsEditorManager::hasRightsConfig(' . $extKey . ')';
}



/**
 * Checks whether extension has something to configure
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo	$dwoo
 * @param	String	$extKey
 * @return	Bool
 */
function Dwoo_Plugin_extMgr_hasConfig_compile(Dwoo_Compiler $dwoo, $extKey)	{
	return 'TodoyuExtManager::extensionHasConfig(' . $extKey . ')';
}



/**
 * Checks whether extension has informations registered
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo	$dwoo
 * @param	String	$extKey
 * @return	Bool
 */
function Dwoo_Plugin_extMgr_hasExtInfo_compile(Dwoo_Compiler $dwoo, $extKey)	{
	return 'TodoyuExtManager::getExtInfos(' . $extKey . ') !== false';
}



/**
 * Render extension icon image tag (if exists)
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo $dwoo
 * @param	String	$extKey
 * @return	String
 *
 */
function Dwoo_Plugin_extIcon_compile(Dwoo_Compiler $dwoo, $extKey)	{
//	$iconPath	= TodoyuDiv::pathWeb(PATH_EXT . '/' . strtolower($extKey));
//	$extInfo	= TodoyuExtManager::getExtInfos($extKey);
//	$altText	= $extKey. ' Extension' . ($extInfo ? ' version ' . $extInfo['version'] : '');

	return "'<img src=\"ext/' . " . $extKey . " . '/assets/img/exticon.png\" width=\"16\" height=\"16\" />'";
}


?>