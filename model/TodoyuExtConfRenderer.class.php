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
 * Extensions configuration renderer
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuExtConfRenderer {

	/**
	 * @todo	comment
	 * 
	 * @param	String		$extKey
	 * @return	String
	 */
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