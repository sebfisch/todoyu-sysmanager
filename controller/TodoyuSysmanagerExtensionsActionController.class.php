<?php

class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	public function tabviewAction(array $params) {
		$extKey	= $params['extension'];
		$tab	= $params['tab'];

		return TodoyuExtManagerRenderer::renderTabView($extKey, $tab, $params);
	}

}

?>