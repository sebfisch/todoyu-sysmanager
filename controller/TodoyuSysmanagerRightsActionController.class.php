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
 * Rightsmanagement
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerRightsActionController extends TodoyuActionController {

	/**
	 * Render tab in rights admin module
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function tabAction(array $params) {
		return TodoyuRightsEditorRenderer::renderModuleContent($params);
	}



	/**
	 * Save rights
	 *
	 * @param	Array	$params
	 */
	public function saveAction(array $params) {
		$extKey	= $params['extension'];
		$rights	= TodoyuArray::assure($params['rights']);

		TodoyuRightsEditorManager::saveRoleRights($extKey, $rights);
	}



	/**
	 * Save current extension's rights and render rights matrix
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function matrixAction(array $params) {
		$roles	= $params['rightseditor']['roles'];
		$roles	= TodoyuArray::intval($roles, true, true);
		$ext	= $params['rightseditor']['extension'];

		TodoyuSysmanagerPreferences::saveRightsExt($ext);
		TodoyuSysmanagerPreferences::saveRightsRoles($roles);

		return TodoyuRightsEditorRenderer::renderRightsMatrix($roles, $ext);
	}

}

?>