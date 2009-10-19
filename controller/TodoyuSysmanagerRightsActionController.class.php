<?php

class TodoyuSysmanagerRightsActionController extends TodoyuActionController {

	public function saveAction(array $params) {
		$extKey	= $params['extension'];
		$rights	= TodoyuArray::assure($params['rights']);

		TodoyuRightsEditorManager::saveGroupRights($extKey, $rights);
	}

	public function updateMatrixAction(array $params) {
		$groups	= $params['groups'];
		$groups	= TodoyuArray::intval($groups, true, true);
		$extKey	= $params['extension'];

		TodoyuRightsEditorManager::saveCurrentExtension($extKey);

		return TodoyuRightsEditorRenderer::renderRightsMatrix($extKey, $groups);
	}

}

?>