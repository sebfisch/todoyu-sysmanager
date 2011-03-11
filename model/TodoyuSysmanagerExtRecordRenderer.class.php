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
 * Extension record renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtRecordRenderer {

	/**
	 * Render extension record module
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModule(array $params) {
		$ext		= trim($params['extkey']);
		$type		= trim($params['type']);
		$idRecord	= intval($params['record']);

		$tabs	= self::renderTabs($ext, $type, $idRecord);
		$body	= self::renderBody($ext, $type, $idRecord);

		return TodoyuRenderer::renderContent($body, $tabs);
	}



	/**
	 * Render extension records tabs
	 * They are composed dynamically, depending on current listing
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 * @return	String
	 */
	private static function renderTabs($ext, $type, $idRecord) {
		$name		= 'records';
		$jsHandler	= 'Todoyu.Ext.sysmanager.Records.onTabClick.bind(Todoyu.Ext.sysmanager.Records)';
		$tabs		= TodoyuSysmanagerExtRecordManager::getTabsConfig($ext, $type, $idRecord);
		$active		= 'all';

		if( $idRecord !== 0 ) {
			$active = implode('-', array($ext,$type,'record'));
		} elseif( $type !== '' ) {
			$active = implode('-', array($ext,$type));
		} elseif( $ext !== '' ) {
			$active = $ext;
		}

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $active);
	}



	/**
	 * Render extension records body (listing and editing)
	 * There are 4 views:
	 * - List all record types of all extensions
	 * - List record types of one extension
	 * - List records of a type
	 * - Edit a record
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 * @return	String
	 */
	private static function renderBody($ext, $type, $idRecord) {
		if( $idRecord !== 0 ) {
			$body	= self::renderBodyRecord($ext, $type, $idRecord);
		} elseif( $type !== '' ) {
			$body	= self::renderBodyType($ext, $type);
		} elseif( $ext !== '' ) {
			$body	= self::renderBodyExtension($ext);
		} else {
			$body	= self::renderBodyAll();
		}

			// Call hook for possible body modifications
		$bodyModified	= TodoyuHookManager::callHook('sysmanager', 'renderRecordsBody-' . $type, array($idRecord, $body));
		if( is_array($bodyModified) && ! empty($bodyModified[0]) ) {
			$body	= $bodyModified[0];
		}

		return $body;
	}



	/**
	 * Render listing of all record types
	 *
	 * @return	String
	 */
	private static function renderBodyAll() {
		$recordsList	= TodoyuSysmanagerExtRecordManager::getAllRecordsList();

		$tmpl	= 'ext/sysmanager/view/records-all.tmpl';
		$data	= array(
			'list'	=> $recordsList
		);

		return render($tmpl, $data);
	}



	/**
	 * Render listing of extension record types
	 *
	 * @param	String		$ext
	 * @return	String
	 */
	private static function renderBodyExtension($ext) {
		$tmpl	= 'ext/sysmanager/view/records-extension.tmpl';
		$data	= array(
			'extKey'	=> $ext,
			'types'		=> array()
		);

		$typeConfigs	= TodoyuSysmanagerExtManager::getRecordConfigs($ext);

		foreach($typeConfigs as $type => $config) {
			$data['types'][$type] = array(
				'type'	=> $type,
				'label'	=> Label($config['label']),
				'count'	=> TodoyuSysmanagerExtRecordManager::getRecordCount($config['table'])
			);
		}

		return render($tmpl, $data);
	}



	/**
	 * Render record listing of a type
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @return	String
	 */
	private static function renderBodyType($ext, $type) {
		$typeConfigs = TodoyuSysmanagerExtManager::getRecordConfig($ext, $type);

		if( TodoyuFunction::isFunctionReference($typeConfigs['list']) ) {
			$records = TodoyuFunction::callUserFunction($typeConfigs['list']);

			$tmpl = 'ext/sysmanager/view/records-records.tmpl';
			$data = array(
				'records'	=> $records,
				'extKey'	=> $ext,
				'type'		=> $type,
				'labels'	=> array(
					'typeLabel' => $typeConfigs['label']
				)
			);

			return render($tmpl, $data);
		} else {
			return 'NO VALID LIST FUNCTION FOR RECORD TYPE: ' . $type . ' IN MODULE ' . $ext . '(ERROR occurs in <strong>' . __METHOD__ . '</strong> on line: ' . __LINE__ . ')';
		}
	}



	/**
	 * Render edit form for a record
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 * @return	String
	 */
	private static function renderBodyRecord($ext, $type, $idRecord) {
		$form	= TodoyuSysmanagerExtRecordManager::getRecordForm($ext, $type, $idRecord);

		return $form->render();
	}

}

?>