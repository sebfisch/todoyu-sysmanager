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
 * Render rights editor
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuRightsEditorRenderer {

	/**
	 * Render rights module content
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModuleContent(array $params = array()) {
			// Tab
		if( isset($params['tab']) ) {
			$tab	=  $params['tab'];
			TodoyuSysmanagerPreferences::saveActiveTab('rights', $tab);
		} else {
			$tab	= TodoyuSysmanagerPreferences::getActiveTab('rights');
		}

		switch($tab) {
			case 'roles':
				return self::renderRolesView($params);

			case 'rights':
			default:
				return self::renderRightsView($params);
		}
	}



	/**
	 * Render rights module tabs
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderModuleTabs(array $params = array()) {
		$name		= 'rights';
		$tabs		= TodoyuArray::assure($GLOBALS['CONFIG']['EXT']['sysmanager']['rightsTabs']);
		$jsHandler	= 'Todoyu.Ext.sysmanager.Rights.onTabClick.bind(Todoyu.Ext.sysmanager.Rights)';
		$activeTab	= TodoyuSysmanagerPreferences::getActiveTab('rights');

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $activeTab);
	}



	/**
	 * Render module view for rights editor
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderRightsView(array $params) {
		if( isset($params['extension']) ) {
			$ext	= $params['extension'];
			TodoyuSysmanagerPreferences::saveRightsExt($ext);
		} else {
			$ext	= TodoyuSysmanagerPreferences::getRightsExt();
		}

		$selectedRoles	= TodoyuSysmanagerPreferences::getRightsRoles();

		$tmpl	= 'ext/sysmanager/view/rights.tmpl';
		$data	= array(
			'form'		=> self::renderRightsEditorForm($selectedRoles, $ext),
			'matrix'	=> self::renderRightsMatrix($selectedRoles, $ext)
		);

		return render($tmpl, $data);
	}



	/**
	 * Render module view for role editor
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderRolesView(array $params) {
		$idRole	= intval($params['role']);

		if( $idRole === 0 ) {
			return TodoyuListingRenderer::render('sysmanager', 'roles');
		} else {
			return TodoyuRoleEditorRenderer::renderEdit($idRole);
		}
	}



	/**
	 * Render form for rights editor
	 * Includes the role and extensio selector
	 *
	 * @param	Array		$roles
	 * @param	String		$ext
	 * @return	String
	 */
	public static function renderRightsEditorForm(array $roles = array(), $ext = '') {
		$form	= TodoyuFormManager::getForm('ext/sysmanager/config/form/rightseditor.xml');

		$data	= array(
			'roles'		=> $roles,
			'extension'	=> $ext
		);

		$form->setFormData($data);
		$form->setUseRecordID(false);

		return $form->render();
	}



	/**
	 * Render extension rights editor
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function renderExtRightsEditor($extKey) {
		if( ! TodoyuRightsEditorManager::hasRightsConfig($extKey) ) {
			return self::renderNoRightsInfo($extKey);
		}

			// Usergroups
		$usergroups	= TodoyuRoleManager::getAllRoles();
		$reform		= array(
			'id'	=> 'value',
			'title'	=> 'label'
		);
//		$groupOptions = TodoyuArray::reform($usergroups, $reform);

		$tmpl	= 'ext/sysmanager/view/rightseditor.tmpl';
		$data	= array(
			'usergroups'=> $usergroups,
			'matrix'	=> self::renderRightsMatrix($extKey),
			'extKey'	=> $extKey
		);

		return render($tmpl, $data);
	}



	/**
	 * Render rights matrix for all all extension rights for the selected roles
	 *
	 * @param	Array		$roleIDs		Roles to display
	 * @param	String		$ext			Extension key
	 * @return	String
	 */
	public static function renderRightsMatrix(array $roleIDs, $ext) {
		if( ! TodoyuRightsEditorManager::hasRightsConfig($ext) ) {
			return self::renderNoRightsInfo($extKey);
		}


			// Read rights XML file
		$rights		= TodoyuRightsEditorManager::readExtRights($ext);

			// Get required chain
		$required	= TodoyuRightsEditorManager::extractRequiredInfos($rights);

			// Get current group infos
		$roles		= TodoyuRightsEditorManager::getRoles($roleIDs);

			// Get current checked rights (default or db)
		$activeRights = TodoyuRightsEditorManager::getCurrentActiveRights($rights, $ext);


		$tmpl	= 'ext/sysmanager/view/rightsmatrix.tmpl';
		$data	= array(
			'extension'		=> $ext,
			'rights'		=> $rights,
			'roles'			=> $roles,
			'activeRights'	=> $activeRights,
			'required'		=> $required
		);

		return render($tmpl, $data);
	}



	/**
	 * Render information that there is no rights.xml in the config dir
	 *
	 * @param	String		$ext
	 * @return	String
	 */
	public static function renderNoRightsInfo($ext) {
		$tmpl	= 'ext/sysmanager/view/rights-not-available.tmpl';
		$data	= array();

		return render($tmpl, $data);
	}

}

?>