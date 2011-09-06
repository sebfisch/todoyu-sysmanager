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
 * Extension record manager
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtRecordManager {

	/**
	 * Get configuration for the tabs
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 * @return	Array
	 */
	public static function getTabsConfig($ext = '', $type = '', $idRecord = 0) {
		$ext		= trim($ext);
		$type		= trim($type);
		$idRecord	= intval($idRecord);
		$tabs		= array();

			// List
		$tabs[] = array(
			'id'		=> 'all',
			'label'		=> 'sysmanager.ext.records.tab.all'
		);

			// Extension
		if( $ext !== '' ) {
			$extLabel	= Todoyu::Label($ext . '.ext.ext.title');

			$tabs[] = array(
				'id'	=> $ext,
				'label'	=> TodoyuString::crop($extLabel, 18, '..', false),
				'class'	=> 'extTypes'
			);
		}

			// Type
		if( $type !== '' ) {
			$typeConfig	= TodoyuSysmanagerExtManager::getRecordConfig($ext, $type);
			$tabs[] = array(
				'id'	=> $ext . '-' . $type,
				'label'	=> TodoyuString::crop(Todoyu::Label($typeConfig['label']), 18, '..', false),
				'class'	=> 'typeRecords'
			);
		}

			// Record
		if( $idRecord !== 0 ) {
			if( $idRecord === -1 ) {
				$recordLabel	= Todoyu::Label('core.global.createNew');
			} else {
				$recordLabel	= TodoyuSysmanagerExtManager::getRecordObjectLabel($ext, $type, $idRecord);
			}

			$tabs[] = array(
				'id'	=> $ext . '-' . $type . '-record',
				'label'	=> TodoyuString::crop($recordLabel, 18, '..', false),
				'class'	=> 'openRecord'
			);
		}

		return $tabs;
	}




	/**
	 * Get infos about all record types
	 *
	 * @return	Integer
	 */
	public static function getAllRecordsList() {
		$info		= array();
		$extRecords	= TodoyuSysmanagerExtManager::getAllRecordsConfig();

		foreach($extRecords as $extKey => $records) {
			$info[$extKey]['title']		= Todoyu::Label($extKey . '.ext.ext.title');
			$info[$extKey]['records'] 	= array();

			foreach($records as $type => $config) {
				$info[$extKey]['records'][$type]['type']	= $type;
				$info[$extKey]['records'][$type]['title']	= Todoyu::Label($config['label']);

				if( isset($config['table']) ) {
					$info[$extKey]['records'][$type]['count']	= TodoyuSysmanagerExtRecordManager::getRecordCount($config['table']);
				} else {
					$info[$extKey]['records'][$type]['count']	= '???';
				}
			}
		}

		return $info;
	}



	/**
	 * Get record form object with injected save buttons
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 * @return	TodoyuForm
	 */
	public static function getRecordForm($ext, $type, $idRecord) {
		$idRecord	= intval($idRecord);
		$config		= TodoyuSysmanagerExtManager::getRecordConfig($ext, $type);

			// Record form
		$form 		= TodoyuFormManager::getForm($config['form'], $idRecord);
//		$form->setAttribute('onsubmit', "return Todoyu.Ext.sysmanager.Extensions.Records.save(this, '" . $ext . "', '" . $type . "')");
		$form->setAction('?ext=sysmanager&amp;controller=records');
		$form->setName('record');

			// Save buttons form
		$xmlPath	= 'ext/sysmanager/config/form/record-save.xml';
		$saveForm	= TodoyuFormManager::getForm($xmlPath);
		$saveButtons= $saveForm->getFieldset('buttons');

			// Add save buttons
		$form->addFieldset('buttons', $saveButtons);

			// Load record data
		$data	= $form->getFormData();

		if( $idRecord !== 0 ) {
			if( ! empty($config['object']) ) {
				$className	= $config['object'];
				$record		= new $className($idRecord);
			} elseif( ! empty($config['table']) ) {
				$record = new TodoyuBaseObject($idRecord, $config['table']);
			}

				// If record object created, get data
			if( is_object($record) ) {
				$data	= $record->getTemplateData(true);
			}
		}

		$data	= TodoyuFormHook::callLoadData($config['form'], $data, $idRecord);

		$data['record-extkey']	= $ext;
		$data['record-type']	= $type;

		$form->setFormData($data);

		return $form;
	}



	/**
	 * Save extension record
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Array		$data
	 * @return	Integer
	 */
	public static function saveRecord($ext, $type, array $data) {
		$config		= TodoyuSysmanagerExtManager::getRecordConfig($ext, $type);
		$idRecord	= intval($data['id']);

		if( TodoyuFunction::isFunctionReference($config['save']) ) {
			$idRecord = TodoyuFunction::callUserFunction($config['save'], $data);
		} else {
			TodoyuLogger::logError('Save function for record ' . $ext . '/' . $type . ' is missing');
		}

		return $idRecord;
	}



	/**
	 * Delete record
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	Integer		$idRecord
	 */
	public static function deleteRecord($ext, $type, $idRecord) {
		$config		= TodoyuSysmanagerExtManager::getRecordConfig($ext, $type);
		$idRecord	= intval($idRecord);

		if( TodoyuFunction::isFunctionReference($config['delete']) ) {
			TodoyuFunction::callUserFunction($config['delete'], $idRecord);
		} else {
			TodoyuLogger::logError('Delete function for record ' . $ext . '/' . $type . ' is missing');
		}
	}



	/**
	 * Get row count of a table
	 *
	 * @param	String		$table
	 * @return	Integer
	 */
	public static function getRecordCount($table) {
		$fields	= 'id';
		$where	= 'deleted = 0';

		$result	= Todoyu::db()->doSelect($fields, $table, $where);

		return Todoyu::db()->getNumRows($result);
	}

}

?>