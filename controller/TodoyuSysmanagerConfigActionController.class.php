<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

class TodoyuSysmanagerConfigActionController extends TodoyuActionController {

	/**
	 * Set extKey and type on request start because its used by all functions
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		//restrictAdmin();
	}


	
	/**
	 * Save uploaded logo if valid
	 * 
	 * @param	Array		$params
	 * @return	String
	 */
	public function logoAction(array $params) {
		$logoData	= TodoyuRequest::getUploadFile('image', 'logo');
		
		$success	= TodoyuSystemConfigManager::saveLogo($logoData);
		
		$commands	= 'window.parent.Todoyu.Ext.sysmanager.Config.Logo.onUploadFinished(' . ($success?'true':'false') . ');';
		
		return TodoyuRenderer::renderUploadIFrameJsContent($commands);
	}
}

?>