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
	 * Render type list
	 *
	 * @param	String	$extKey
	 * @return	String	HTML
	 */
	public static function renderTypeList($extKey) {
		$data	= array(
			'types'		=> array(),
			'extKey'	=> $extKey
		);

		$typeConfigs	= TodoyuExtManager::getRecordConfigs($extKey);

		foreach($typeConfigs as $typeName => $config) {
			if( TodoyuDiv::isFunctionReference($config['list']) )	{
				$records = TodoyuDiv::callUserFunction($config['list']);
			}

			$data['types'][] = array(
				'key'		=> $typeName,
				'label'		=> Label( $config['label'] ),
				'records'	=> $records
			);

		}

		return render('ext/sysmanager/view/extension-record-typelist.tmpl', $data);
	}



	/**
	 * Render record list
	 *
	 * @param	String	$extKey
	 * @param	String	$type
	 * @return	String
	 */
	public static function renderRecordList($extKey, $type)	{
		$tmpl = 'ext/sysmanager/view/extension-record-list.tmpl';

		$typeConfigs = TodoyuExtManager::getRecordTypeConfig($extKey, $type);

		if( TodoyuDiv::isFunctionReference($typeConfigs['list']) )	{
			$records = TodoyuDiv::callUserFunction($typeConfigs['list']);

			$data = array('records'	=> $records,
						  'extKey'	=> $extKey,
						  'type'	=> $type,
						  'labels'	=> array(
								'createNew' => 'LLL:sysmanager.createNew',
								'typeLabel' => $typeConfigs['label'],
								'backbutton'=> 'LLL:sysmanager.backToTypeList'
						  ));

			return render($tmpl, $data);
		} else {
			return 'NO VALID LIST FUNCTION FOR RECORD TYPE: ' . $type . ' IN MODULE ' . $extKey . '(ERROR occurs in <strong>' . __METHOD__ . '</strong> on line: ' . __LINE__ . ')';
		}
	}
}

?>