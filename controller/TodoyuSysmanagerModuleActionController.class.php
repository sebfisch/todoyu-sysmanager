<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Sysmanager module action controller
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerModuleActionController extends TodoyuActionController {

	/**
	 * Restrict access to admins
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		Todoyu::restrict('sysmanager', 'general:use');
	}



	/**
	 * Load and display module
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function loadAction(array $params) {
		$module	= trim($params['module']);

			// Save current module
		TodoyuSysmanagerPreferences::saveActiveModule($module);

		return TodoyuSysmanagerRenderer::renderModule($module, $params);
	}

}

?>