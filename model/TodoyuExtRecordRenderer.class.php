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
 * Extension record renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtRecordRenderer {

	/**
	 * Render extension records
	 * If parameter type is set, render type records, else render a list of record types
	 *
	 * @param	String		$extKey
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderRecords($extKey, array $params = array()) {
		if( isset($params['type']) ) {
			return self::renderRecordList($extKey, $params['type']);
		} else {
			return self::renderTypeList($extKey);
		}
	}



	/**
	 * Render type list
	 *
	 * @param	String	$extKey
	 * @return	String	HTML
	 */
	public static function renderTypeList($extKey) {
		$tmpl	= 'ext/sysmanager/view/records-typelist.tmpl';
		$data	= array(
			'extKey'	=> $extKey,
			'types'		=> array()
		);

		$typeConfigs	= TodoyuExtManager::getRecordConfigs($extKey);

		foreach($typeConfigs as $type => $config) {
			$data['types'][$type] = array(
				'type'	=> $type,
				'label'	=> Label($config['label']),
				'count'	=> TodoyuExtRecordManager::getRecordCount($config['table'])
			);
		}

		return render($tmpl, $data);
	}



	/**
	 * Render record list
	 *
	 * @param	String	$extKey
	 * @param	String	$type
	 * @return	String
	 */
	public static function renderRecordList($extKey, $type)	{
		$typeConfigs = TodoyuExtManager::getRecordTypeConfig($extKey, $type);

		if( TodoyuFunction::isFunctionReference($typeConfigs['list']) )	{
			$records = TodoyuFunction::callUserFunction($typeConfigs['list']);

			$tmpl = 'ext/sysmanager/view/records-recordlist.tmpl';
			$data = array(
				'records'	=> $records,
				'extKey'	=> $extKey,
				'type'		=> $type,
				'labels'	=> array(
					'typeLabel' => $typeConfigs['label']
				)
			);

			return render($tmpl, $data);
		} else {
			return 'NO VALID LIST FUNCTION FOR RECORD TYPE: ' . $type . ' IN MODULE ' . $extKey . '(ERROR occurs in <strong>' . __METHOD__ . '</strong> on line: ' . __LINE__ . ')';
		}
	}
}

?>