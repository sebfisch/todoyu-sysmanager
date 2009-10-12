<?php

class TodoyuSysmanagerExtconfActionController extends TodoyuActionController {

	public function saveAction(array $params) {
		$data	= $params['config'];
		$extKey	= $data['extension'];

		$xmlPath= TodoyuExtConfManager::getXmlPath($extKey);
		$form	= TodoyuExtConfManager::getForm($extKey);
		$form	= TodoyuFormHook::callBuildForm($xmlPath, $form, 0);

		$form->setFormData($data);

		if( $form->isValid() ) {
			$config	= $form->getStorageData();

			TodoyuExtConfManager::updateExtConf($extKey, $config);
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();
		}

		return $form->render();
	}

}

?>