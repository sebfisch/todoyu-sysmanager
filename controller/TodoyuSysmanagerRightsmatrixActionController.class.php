<?php

class TodoyuSysmanagerRightsmatrixActionController extends TodoyuActionController {

	public function updateAction(array $params) {
		$groups	= $params['groups'];
		$groups	= TodoyuDiv::intvalArray($groups, true, true);
		$extKey	= $params['extension'];

		TodoyuRightsEditorManager::saveCurrentExtension($extKey);

		return TodoyuRightsEditorRendererrenderRightsMatrix($extKey, $groups);	
	}
	
}

?>