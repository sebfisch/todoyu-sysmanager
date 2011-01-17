<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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

/**
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuRoleEditorManager {

	/**
	 * Get quick create role form object
	 *
	 * @return	TodoyuForm
	 */
	public static function getQuickCreateForm($idRole = 0) {
		$idRole	= intval($idRole);

			// Construct form object
		$xmlPath	= 'core/config/form/role.xml';
		$form		= TodoyuFormManager::getForm($xmlPath, $idRole);

			// Adjust form to needs of quick creation wizard
		$form->setAttribute('action', '?ext=sysmanager&amp;controller=quickcreaterole');
		$form->setAttribute('onsubmit', 'return false');
		$form->getFieldset('buttons')->getField('save')->setAttribute('onclick', 'Todoyu.Ext.sysmanager.QuickCreateRole.save(this.form)');
		$form->getFieldset('buttons')->getField('cancel')->setAttribute('onclick', 'Todoyu.Popup.close(\'quickcreate\')');

		return $form;
	}



	/**
	 * Get data for roles list
	 *
	 * @param	Integer		$size
	 * @param	Integer		$offset
	 * @param	String		$searchWord
	 * @return	Array
	 */
	public static function getRoleListingData($size, $offset = 0, $searchWord = '') {
		$roles		= TodoyuRoleManager::getAllRoles();

		$data	= array(
			'rows'	=> array(),
			'total'	=> sizeof($roles)
		);

			// Add all roles to list
		foreach($roles as $index => $role) {
			$data['rows'][] = array(
				'icon'			=> '',
				'title'			=> $role['title'],
				'description'	=> $role['description'],
				'persons'		=> TodoyuRoleManager::getNumPersons($role['id']) . ' ' . Label('contact.persons'),
				'actions'		=> TodoyuRoleEditorRenderer::renderRoleActions($role['id'])
			);
		}

		return $data;
	}
}

?>