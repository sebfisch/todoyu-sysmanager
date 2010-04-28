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
 * Dynamic context menu loaded by AJAX request
 * Extensions can register menu items for menu types
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuRecordsOverviewManager {

	/**
	 * Get infos about all record types
	 *
	 * @return	Integer
	 */
	public static function getAllRecordInfos() {
		$info		= array();
		$extRecords	= TodoyuExtManager::getAllRecordsConfig();

		foreach($extRecords as $extKey => $records) {
			$info[$extKey]['title']		= Label($extKey . '.ext.title');
			$info[$extKey]['records'] 	= array();

			foreach($records as $type => $config) {
				$info[$extKey]['records'][$type]['type']	= $type;
				$info[$extKey]['records'][$type]['title']	= Label($config['label']);

				if( isset($config['table']) ) {
					$info[$extKey]['records'][$type]['count']	= TodoyuExtRecordManager::getRecordCount($config['table']);
				} else {
					$info[$extKey]['records'][$type]['count']	= '???';
				}
			}
		}

		return $info;
	}

}

?>