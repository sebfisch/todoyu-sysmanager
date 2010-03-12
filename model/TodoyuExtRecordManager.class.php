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
 * Extension record manager
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtRecordManager {

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
		$config		= TodoyuExtManager::getRecordTypeConfig($ext, $type);

			// Record form
		$form 		= TodoyuFormManager::getForm($config['form'], $idRecord);
		$form->setAttribute('onsubmit', "return Todoyu.Ext.sysmanager.Extensions.Records.save(this, '" . $ext . "', '" . $type . "')");
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
			if( ! empty($config['object']) )	{
				$className	= $config['object'];
				$record		= new $className($idRecord);
			} elseif( ! empty($config['table']) )	{
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
		$config		= TodoyuExtManager::getRecordTypeConfig($ext, $type);
		$idRecord	= intval($data['id']);

		if( TodoyuDiv::isFunctionReference($config['save']) ) {
			$idRecord = TodoyuDiv::callUserFunction($config['save'], $data);
		} else {
			Todoyu::log('Save function for record ' . $ext . '/' . $type . ' is missing', LOG_LEVEL_ERROR);
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
		$config		= TodoyuExtManager::getRecordTypeConfig($ext, $type);
		$idRecord	= intval($idRecord);

		if( TodoyuDiv::isFunctionReference($config['delete']) ) {
			TodoyuDiv::callUserFunction($config['delete'], $idRecord);
		} else {
			Todoyu::log('Delete function for record ' . $ext . '/' . $type . ' is missing', LOG_LEVEL_ERROR);
		}
	}


	public static function getRecordCount($table) {
		$fields	= 'COUNT(*) as total';
		$where	= 'deleted = 0';
		$group	= 'id';

		$res	= Todoyu::db()->doSelect($fields, $table, $where, $group);

		return Todoyu::db()->getNumRows($res);
	}

}

?>