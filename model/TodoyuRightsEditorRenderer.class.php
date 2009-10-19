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
	 * Render extension rights editor
	 *
	 * @param	String	$extKey
	 * @return	String	HTML
	 */
	public static function renderExtRightsEditor($extKey) {
		$data	= array(
			'groupselector'	=> self::renderGroupSelector(),
			'matrix'		=> self::renderRightsMatrix($extKey),
			'extKey'		=> $extKey
		);

		return render('ext/sysmanager/view/rightseditor.tmpl', $data);
	}



	/**
	 * Render group selector to define the displayed groups to edit
	 *
	 * @return	String
	 */
	private static function renderGroupSelector() {
		$usergroups	= TodoyuUsergroupManager::getAllUsergroups();
		$options	= TodoyuArray::reform($usergroups, array('id'=>'value','title'=>'label'));

		foreach($options as $key => $option) {
			$options[$key]['selected'] = true;
		}

		$data	= array(
			'name'		=> 'groups[]',
			'id'		=> 'groups',
			'size'		=> 8,
			'attributes'=> 'multiple="multiple"',
			'options'	=> $options
		);

		return render('ext/sysmanager/view/selector.tmpl', $data);
	}



	/**
	 * Render rights matrix for all all extension rights for the selected groups
	 *
	 * @param	String		$ext			Extension key
	 * @param	Array		$groups			Groups to display
	 * @param	Bool		$useDefaults	Use the default values defined in the XML (current rights won't override the defaults)
	 * @return	String
	 */
	public static function renderRightsMatrix($ext, array $groups = array(), $useDefaults = false) {

		if( ! TodoyuRightsEditorManager::hasRightsConfig($ext) ) {
			return self::renderNoRightsInfo($ext);
		}

			// Read rights XML file
		$rights		= TodoyuRightsEditorManager::readExtRights($ext);

			// Get current group infos
		$groupInfos	= TodoyuRightsEditorManager::getGroupInfos($groups);

			// Get current checked rights (default or db)
		if( $useDefaults === true ) {
			$activeRights = TodoyuRightsEditorManager::getDefaultActiveRights($rights);
		} else {
			$activeRights = TodoyuRightsEditorManager::getCurrentActiveRights($rights, $ext);
		}

		$data	= array(
			'extension'		=> $ext,
			'rights'		=> $rights,
			'groups'		=> $groupInfos,
			'activeRights'	=> $activeRights
		);

		return render('ext/sysmanager/view/rightsmatrix.tmpl', $data);
	}



	/**
	 * Render information that there is no rights.xml in the config dir
	 *
	 * @param	String		$ext
	 * @return	String
	 */
	private static function renderNoRightsInfo($ext) {

		return '<p>No rights defined in config/rights.xml</p>';
	}

}

?>