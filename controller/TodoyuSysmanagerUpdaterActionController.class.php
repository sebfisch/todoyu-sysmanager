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
 * System and extension updater controller
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerUpdaterActionController extends TodoyuActionController {

	/**
	 * Restrict access
	 *
	 * @param	Array	$params
	 */
	public function init(array $params) {
		restrictAdmin();
	}



	/**
	 * Get rendered list of available extensions updates
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function searchAction(array $params) {
		$query	= trim($params['query']);

		TodoyuSysmanagerUpdaterManager::saveLastQuery($query);

		return TodoyuSysmanagerUpdaterRenderer::renderSearchResults($query);
	}



	/**
	 * Install update of todoyu core
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function installCoreUpdateAction(array $params) {
		$archiveHash= trim($params['archive']);
		$result		= TodoyuSysmanagerUpdaterManager::installCoreUpdate($archiveHash);

		if( $result !== true ) {
			TodoyuHeader::sendTodoyuErrorHeader();
			TodoyuHeader::sendTodoyuHeader('message', $result);
		}
	}



	/**
	 * Install extension update from TER
	 *
	 * @param	Array	$params
	 */
	public function installExtensionUpdateAction(array $params) {
		$hash	= trim($params['hash']);
		$ext	= trim($params['extkey']);

		$result	= TodoyuSysmanagerUpdaterManager::installExtensionUpdate($ext, $hash);

		if( $result !== true ) {
			TodoyuHeader::sendTodoyuErrorHeader();
			TodoyuHeader::sendTodoyuHeader('message', $result);
		}
	}



	/**
	 * Install an extension from TER
	 *
	 * @param	Array	$params
	 */
	public function installTerExtensionAction(array $params) {
		$extKey		= trim($params['extkey']);
		$archiveHash= trim($params['archive']);

		$result		= TodoyuSysmanagerUpdaterManager::installExtension($extKey, $archiveHash);

		if( $result !== true ) {
			TodoyuHeader::sendTodoyuErrorHeader();
			TodoyuHeader::sendTodoyuHeader('message', $result);
		}
	}

}

?>