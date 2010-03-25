<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

class TodoyuSysmanagerQuickCreateRoleActionController extends TodoyuActionController {

	/**
	 * Get quick role creation form rendered
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function popupAction(array $params) {
		return TodoyuRoleEditorRenderer::renderRoleQuickCreateForm($params);
	}



	/**
	 * Save role record
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function saveAction(array $params) {
//		restrict('sysmanager', 'role:edit');
		$data	= $params['role'];
		$idRole	= intval($data['id']);

			// Get form, call save hooks, set data
		$form	= TodoyuRoleEditorManager::getQuickCreateForm($idRole);
		$data	= TodoyuFormHook::callSaveData('core/config/form/role.xml', $data, $idRole);
		$form->setFormData($data);

			// Validate, render
		if( $form->isValid() )	{
			$storageData= $form->getStorageData();

			$idRole	= TodoyuRoleManager::saveRole($storageData);

			TodoyuHeader::sendTodoyuHeader('idRole', $idRole);
			TodoyuHeader::sendTodoyuHeader('recordLabel', $storageData['title']);

			return $idRole;
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}

}

?>