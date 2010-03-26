<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Sysmanager extconf action controller
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerExtconfActionController extends TodoyuActionController {

	/**
	 * Save extension configuration
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function saveAction(array $params) {
		$data	= $params['config'];
		$extKey	= $data['extension'];

		$xmlPath= TodoyuExtConfManager::getXmlPath($extKey);
		$form	= TodoyuExtConfManager::getForm($extKey);

		$form->setFormData($data);

		if( $form->isValid() ) {
			$config	= $form->getStorageData();

			TodoyuExtConfManager::updateExtConf($extKey, $config);
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}

}

?>