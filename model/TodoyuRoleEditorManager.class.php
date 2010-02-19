<?php

class TodoyuRoleEditorManager {

	public static function getRoleListingData($size, $offset = 0, $searchWord = '') {
		$roles		= TodoyuRoleManager::getAllRoles();

		$data	= array(
			'rows'	=> array(),
			'total'	=> sizeof($roles)
		);

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