<?php

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



	public function listTypeRecordsAction(array $params) {
		return TodoyuExtRecordRenderer::renderRecordList($this->extKey, $this->type);
	}




	public function editAction(array $params) {
		$idRecord	= intval($params['record']);

		$form		= TodoyuExtRecordManager::getRecordForm($this->extKey, $this->type, $idRecord);

		return $form->render();
	}


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
		$idRecord	= intval($data['id']);
		$config		= TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);

		$form		= TodoyuExtRecordManager::getRecordForm($this->extKey, $this->type, $idRecord);

		$form->addFormData($data);

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