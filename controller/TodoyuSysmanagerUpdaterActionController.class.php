<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuSysmanagerUpdaterActionController extends TodoyuActionController {

	/**
	 * Get rendered list of available extensions updates
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function searchAction(array $params) {
		$query	= trim($params['query']);

		return TodoyuUpdaterRenderer::renderBrowseResultList($query);
	}



	/**
	 * Install update of todoyu core
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function installCoreUpdateAction(array $params) {
		$hash	= trim($params['hash']);

		TodoyuUpdaterManager::installCoreUpdate($hash);

		return 'Core update installed';
	}



	/**
	 * Install extension update
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function installExtensionUpdateAction(array $params) {
		$hash	= trim($params['hash']);
		$ext	= trim($params['extkey']);

		TodoyuUpdaterManager::installExtensionUpdate($ext, $hash);

		return 'Extension update installed';
	}

}

?>