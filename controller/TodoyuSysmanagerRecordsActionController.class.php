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

	public function showRecordListAction(array $params) {
		return TodoyuExtRecordRenderer::renderRecordList($this->extKey, $this->type);
	}


	public function recordFormAction(array $params) {
		$editID		= intval($params['recordID']);
		$content	= '';

		$recordConfig = TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);

		if( $recordConfig['form'] ) {
			$form = new TodoyuForm($recordConfig['form']);

			if($recordConfig['object'])	{
				$record = new $recordConfig['object']($editID);
//				if(method_exists($record, 'loadForeignData'))	{
//					$record->loadForeignData();
//				}
			} else if($recordConfig['table'])	{
				$record = new TodoyuBaseObject($editID, $recordConfig['table']);
			}

			if(is_object($record))	{
				$form->setFormData($record->getTemplateData(true));
				$content = $form->render();
			}
		}

		return $content;
	}


	public function deleteRecordAction(array $params) {
		$recordConfig = TodoyuExtManager::getRecordTypeConfig($this->extKey, $this->type);
		if($recordConfig['delete'])	{
			if(TodoyuDiv::checkOnMethodString($recordConfig['delete']))	{

				$recordID = intval($params['recordID']);

				call_user_func(explode('::', $recordConfig['delete']), $recordID);
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