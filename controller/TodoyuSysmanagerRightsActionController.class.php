<?php

class TodoyuSysmanagerRightsActionController extends TodoyuActionController {

	public function saveAction(array $params) {
		$extKey	= $params['extension'];
		$rights	= $params['rights'];

		TodoyuRightsEditorManager::saveGroupRights($extKey, $rights);	
	}
	
}

?>