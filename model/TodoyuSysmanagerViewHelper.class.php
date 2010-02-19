<?php

class TodoyuSysmanagerViewHelper {

	public static function getRoleOptions(TodoyuFormElement $field) {
		$roles	= TodoyuRoleManager::getAllRoles();
		$reform		= array(
			'id'	=> 'value',
			'title'	=> 'label'
		);
		$roleOptions = TodoyuArray::reform($roles, $reform);

		return $roleOptions;
	}

	public static function getExtensionOptions(TodoyuFormElement $field) {
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		$options	= array();

		foreach($extKeys as $extKey) {
			$options[] = array(
				'value'	=> $extKey,
				'label'	=> TodoyuLanguage::getLabel($extKey . '.ext.title')
			);
		}

		return $options;
	}

}

?>