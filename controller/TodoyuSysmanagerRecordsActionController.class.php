<?php

class TodoyuSysmanagerRecordsActionController extends TodoyuActionController {

	/**
	 * @var	Extension key
	 */
	protected $extKey;

	/**
	 * @var	Type
	 */
	protected $type;

	public function init(array $params) {
		$this->extKey	= $params['extKey'];
		$this->type		= $params['type'];
	}


	public function listTypesAction(array $params) {
		return TodoyuExtManagerRenderer::renderRecords($this->extKey, $params);
	}

	public function listExtTypesAction(array $params) {
		return TodoyuExtRecordRenderer::renderRecordList($this->extKey, $this->type);
	}




	public function editAction(array $params) {
		$idRecord	= intval($params['record']);
		$config		= TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);
		$content	= '';


		$form = new TodoyuForm($config['form']);

		if( ! empty($config['object']) )	{
			$className	= $config['object'];
			$record = new $className($idRecord);
		} elseif( ! empty($config['table']) )	{
			$record = new TodoyuBaseObject($idRecord, $config['table']);
		}

		if( is_object($record) ) {
			$data	= $record->getTemplateData(true);
			$form->setFormData($data);
			$content= $form->render();
		} else {
			$content= 'ERROR';
		}

		return $content;
	}


	public function deleteAction(array $params) {
		$recordConfig = TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);
		if($recordConfig['delete'])	{
			if( TodoyuDiv::isFunctionReference($recordConfig['delete']) ) {
				$idRecord = intval($params['recordID']);

				TodoyuDiv::callUserFunction($recordConfig['delete'], $idRecord);
			}
		}

		return TodoyuExtRecordRenderer::renderRecordList($this->extKey, $this->type);
	}


	public function saveRecordAction(array $params) {
		$jsonResponse	= new stdClass();
		$recordConfig	= TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);

			// Load form data
		$formName	= $params['form'];
		$formData 	= $params[$formName];

		$idForm	= 0;

		$formData	= TodoyuFormHook::callLoadData($recordConfig['form'], $formData, $idForm);

			// Construct form object
		$form 		= new TodoyuForm($recordConfig['form']);
		$form		= TodoyuFormHook::callBuildForm($recordConfig['form'], $form, $idForm);

			// Set form data
		$form->setFormData($formData);

			// Validate, render
		if( $form->isValid() )	{
			$saveFunc = $recordConfig['save'];

			if( TodoyuDiv::isFunctionReference($saveFunc) ) {
				TodoyuDiv::callUserFunction($saveFunc, $formData, $recordConfig['form']);
			}
			$jsonResponse->saved = true;
		} else {
				// Not valid: re-render with errors marked
			$jsonResponse->saved	= false;
			$jsonResponse->formHTML = $form->render();
		}

		TodoyuHeader::sendHeaderJSON();

		return json_encode($jsonResponse);
	}

}

?>