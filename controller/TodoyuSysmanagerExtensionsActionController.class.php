<?php

class TodoyuSysmanagerExtensionsActionController extends TodoyuActionController {

	public function tabviewAction(array $params) {
		$extKey	= $params['extension'];
		$tab	= $params['tab'];
		
		$data	= array(
			'htmlContent' => TodoyuExtRenderer::renderTabView($extKey, $tab)
		);

		return render('ext/sysmanager/view/inner.tmpl', $data);
	}
	
}

?>