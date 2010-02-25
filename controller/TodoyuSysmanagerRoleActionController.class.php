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
 * Role Action Controller
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerRoleActionController extends TodoyuActionController {

	/**
	 * List roles
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function listingAction(array $params) {
		return TodoyuListingRenderer::render('sysmanager', 'roles');
	}


	/**
	 * Edit role
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function editAction(array $params) {
		$idRole	= intval($params['role']);

		return TodoyuRoleEditorRenderer::renderEdit($idRole);
	}


	/**
	 * Delete role
	 *
	 * @param	Array		$params
	 */
	public function deleteAction(array $params) {
		restrict('sysmanager', 'roles:delete');

		$idRole	= intval($params['role']);
		TodoyuRoleManager::deleteRole($idRole);
	}



	/**
	 * Save role
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function saveAction(array $params) {
		$xmlPath= 'core/config/form/role.xml';
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

		$xmlPath	= 'core/config/form/role.xml';

		$formName	= $params['form'];
		$fieldName	= $params['field'];

		$index		= intval($params['index']);
		$idRecord	= intval($params['record']);

		return TodoyuFormManager::renderSubformRecord($xmlPath, $fieldName, $formName, $index, $idRecord);
	}

}

?>