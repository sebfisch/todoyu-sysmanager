<?php

class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	/**
	 * Default request to load a tab in the extension manager
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function tabviewAction(array $params) {
		$extKey	= $params['extension'];
		$tab	= $params['tab'];

		return TodoyuExtManagerRenderer::renderTabView($extKey, $tab, $params);
	}

}

?>