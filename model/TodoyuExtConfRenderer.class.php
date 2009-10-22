<?php

class TodoyuExtConfRenderer {

	public static function renderConfig($extKey) {
		$tmpl	= 'ext/sysmanager/view/extension-config.tmpl';
		$data	= array(
			'hasConf'	=> false
		);


		if( TodoyuExtConfManager::hasExtConf($extKey) ) {
			$form	= TodoyuExtConfManager::getForm($extKey);
			$data['hasConf']	= true;
			$data['form']		=  $form->render();
		} else {

		}

		return render($tmpl, $data);
	}


}

?>