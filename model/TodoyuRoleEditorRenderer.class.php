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
 * Role Editor Renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuRoleEditorRenderer {

	/**
	 * Render action buttons for role listing
	 *
	 * @param	Integer		$idRole
	 * @return	String
	 */
	public static function renderRoleActions($idRole) {
		$tmpl	= 'ext/sysmanager/view/role-actions.tmpl';
		$data	= array(
			'id'	=> intval($idRole)
		);

		return render($tmpl, $data);
	}



	/**
	 * Render role quick creation form
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderRoleQuickCreateForm(array $params) {
		$form	= TodoyuRoleEditorManager::getQuickCreateForm();

			// Preset (empty) form data
		$formData	= $form->getFormData();
		$formData	= TodoyuFormHook::callLoadData('core/config/form/role.xml', $formData, 0);

		$form->setFormData($formData);

		return $form->render();
	}



	/**
	 * Render edit form for role
	 *
	 * @param	Integer		$idRole
	 * @return	String
	 */
	public static function renderEdit($idRole) {
		$idRole		= intval($idRole);
		$xmlPath	= 'core/config/form/role.xml';

		$form	= TodoyuFormManager::getForm($xmlPath, $idRole);

		$role	= TodoyuRoleManager::getRole($idRole);

		$formData	= $role->getTemplateData(true);
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, $idRole);

		$form->setFormData($formData);
		$form->setRecordID($idRole);

		return $form->render();
	}

}

?>