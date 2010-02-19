<?php

class TodoyuSysmanagerRoleActionController extends TodoyuActionController {


	public function listingAction(array $params) {
		return TodoyuListingRenderer::render('sysmanager', 'roles');
	}


	public function editAction(array $params) {
		$idRole	= intval($params['role']);

		return TodoyuRoleEditorRenderer::renderEdit($idRole);
	}


	public function saveAction(array $params) {
		$xmlPath= 'ext/sysmanager/config/form/role.xml';
		$data	= $params['role'];
		$idRole	= intval($data['id']);

		$form	= TodoyuFormManager::getForm($xmlPath, $idRole);

		$form->setFormData($data);

		if( $form->isValid() ) {
			$storageData= $form->getStorageData();
			$idRole		= TodoyuRoleManager::saveRole($storageData);
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}



	/**
	 * Add a subform to the person form
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function addSubformAction(array $params) {
		restrict('sysmanager', 'role:edit');

		$xmlPath	= 'ext/sysmanager/config/form/role.xml';

		$formName	= $params['form'];
		$fieldName	= $params['field'];

		$index		= intval($params['index']);
		$idRecord	= intval($params['record']);

		return TodoyuFormManager::renderSubformRecord($xmlPath, $fieldName, $formName, $index, $idRecord);
	}

}

?>