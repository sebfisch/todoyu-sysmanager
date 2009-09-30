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
function Dwoo_Plugin_hasRecordRegistered(Dwoo $dwoo, $extKey)	{
	$arrayToCheck = TodoyuExtManager::getRecordTypes($extKey);

	return (count($arrayToCheck) > 0) ? true : false;
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
function Dwoo_Plugin_hasRightsConfiguration(Dwoo $dwoo, $extKey)	{
	return TodoyuRightsEditorManager::hasRightsConfig($extKey);
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
function Dwoo_Plugin_hasRegisteredConfigurations(Dwoo $dwoo, $extKey)	{
	return TodoyuExtManager::extensionHasConfig($extKey);
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
function Dwoo_Plugin_hasExtensionInfo(Dwoo $dwoo, $extKey)	{
	return TodoyuExtManager::getExtInfos($extKey) !== false;
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
function Dwoo_Plugin_extIcon(Dwoo $dwoo, $extKey)	{
	$iconTag 	= '';
	$extPath	= constant( 'PATH_EXT_' . strtoupper($extKey) );
	$extPathRel	= str_replace( realpath('.'), '', $extPath );
	$extInfo	= TodoyuExtManager::getExtInfos($extKey);
	$altText	= $extKey. ' Extension' . ($extInfo ? ' version ' . $extInfo['version'] : '');

	$iconTag	= '<img style="border:1px solid #BDD1AF; background-color:#DCECD2;" src="' . 'ext/' . $extKey . '/assets/img/exticon.png" width="16" height="16" alt="' . $altText . '"/>';

	return $iconTag;
}


?>