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

class TodoyuSysmanagerRecordsActionController extends TodoyuActionController {

	/**
	 * @var	String		Extension parameter of the request
	 */
	protected $extKey;

	/**
	 * @var	String		Type parameter of the request
	 */
	protected $type;



	/**
	 * Set extKey and type on request start because its used by all functions
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		TodoyuExtensions::loadAllAdmin();

		$this->extKey	= $params['extKey'];
		$this->type		= $params['type'];
	}



	/**
	 * Get list of record types for an extension
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function listRecordTypesAction(array $params) {
		return TodoyuExtRecordRenderer::renderTypeList($this->extKey);
	}



	/**
	 * Get list of records of given type
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function listTypeRecordsAction(array $params) {
		return TodoyuExtRecordRenderer::renderRecordList($this->extKey, $this->type);
	}



	/**
	 * Get record editing form
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function editAction(array $params) {
		$idRecord	= intval($params['record']);

		$form		= TodoyuExtRecordManager::getRecordForm($this->extKey, $this->type, $idRecord);

		return $form->render();
	}



	/**
	 * Delete record
	 *
	 * @param	Array	$params
	 */
	public function deleteAction(array $params) {
		$idRecord	= intval($params['record']);

		TodoyuExtRecordManager::deleteRecord($this->extKey, $this->type, $idRecord);
	}



	/**
	 * Save extension record
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function saveAction(array $params) {
		$data		= $params['record'];
			// Declare fieldmarker-values for parsing of inline JS
		$data['record-extkey']	= $params['extKey'];
		$data['record-type']	= $params['type'];
		
		$idRecord	= intval($data['id']);
		$config		= TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);

		$form		= TodoyuExtRecordManager::getRecordForm($this->extKey, $this->type, $idRecord);
		$form->setFormData($data);

			// Validate, save, render
		if( $form->isValid() )	{
			$recordData	= $form->getStorageData();

			$idRecord	= TodoyuExtRecordManager::saveRecord($this->extKey, $this->type, $recordData);
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}

}

?>