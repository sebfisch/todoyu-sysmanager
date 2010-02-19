<?php

class TodoyuRoleEditorRenderer {

	public static function renderRoleActions($idRole) {
		$tmpl	= 'ext/sysmanager/view/role-actions.tmpl';
		$data	= array(
			'id'	=> intval($idRole)
		);

		return render($tmpl, $data);
	}

	public static function renderEdit($idRole) {
		$idRole		= intval($idRole);
		$xmlPath	= 'ext/sysmanager/config/form/role.xml';

		$form	= TodoyuFormManager::getForm($xmlPath, $idRole);

		$role	= TodoyuRoleManager::getRole($idRole);

		$formData	= $role->getTemplateData(true);
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, $idRole);

		TodoyuDebug::printInFirebug($formData);

		$form->setFormData($formData);
		$form->setRecordID($idRole);

		return $form->render();
	}

}

?>