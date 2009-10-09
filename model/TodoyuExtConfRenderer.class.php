<?php

class TodoyuExtConfRenderer {

	public static function renderForm($extKey) {
		if( TodoyuExtConfManager::hasExtConf($extKey) ) {
			$form	= TodoyuExtConfManager::getForm($extKey);

			return $form->render();
		} else {
			return 'No config available';
		}
	}


}

?>