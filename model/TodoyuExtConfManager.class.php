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
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Extension config manager. Manages writing /config/extensions.php file with current config
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuExtConfManager {

	/**
	 * Get path to extension config form
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function getXmlPath($extKey) {
		return 'ext/' . strtolower($extKey) . '/config/form/extconf.xml';
	}



	/**
	 * Check if extension config form exists
	 *
	 * @param	String		$extKey
	 * @return	Bool
	 */
	public static function hasExtConf($extKey) {
		$xmlPath = self::getXmlPath($extKey);

		return TodoyuFileManager::isFile($xmlPath);
	}



	/**
	 * Get extConf form
	 *
	 * @param	String		$extKey
	 * @return	TodoyuForm
	 */
	public static function getForm($extKey) {
		$xmlPath	= self::getXmlPath($extKey);

		$form	= TodoyuFormManager::getForm($xmlPath);

		$data	= self::getExtConf($extKey);
		$data	= TodoyuFormHook::callLoadData($xmlPath, $data, 0);

		$form->setUseRecordID(false);
		$form->setFormData($data);

			// Modify form fields
		$formAction	= TodoyuString::buildUrl(array(
			'ext'		=> 'sysmanager',
			'controller'=> 'extconf'
		));

		$form->setAttribute('onsubmit', 'return Todoyu.Ext.sysmanager.ExtConf.onSave(this)');
		$form->setAttribute('action', $formAction);
		$form->setAttribute('name', 'config');

		$form->addHiddenField('extension', $extKey, true);


			// Add save and cancel buttons
		$xmlPathSave= 'ext/sysmanager/config/form/extconf-save.xml';
		$saveForm	= TodoyuFormManager::getForm($xmlPathSave);
		$buttons	= $saveForm->getFieldset('save');

		$form->addFieldset('save', $buttons);

		return $form;
	}



	/**
	 * Save current configuration (installed extensions and their config)
	 *
	 */
	private static function writeExtconfFile() {
		$file	= PATH_LOCALCONF . '/extconf.php';
		$tmpl	= 'ext/sysmanager/view/extconf.php.tmpl';
		$data	= array(
			'extConf'	=> array()
		);

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$extConf	= self::getExtConf($extKey);

			$data['extConf'][$extKey] = addslashes(serialize($extConf));
		}

			// Save file
		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}



	/**
	 * Update an extension configuration
	 *
	 * @param	String		$extKey
	 * @param	Array		$data
	 */
	public static function updateExtConf($extKey, array $data) {
		self::setExtConf($extKey, $data);

		self::writeExtconfFile();
	}



	/**
	 * Set extension configuration array in
	 *
	 * @param	String		$extKey
	 * @param	Array		$data
	 */
	public static function setExtConf($extKey, array $data) {
		Todoyu::$CONFIG['EXT'][$extKey]['extConf'] = $data;
	}



	/**
	 * Get config array for an extension
	 *
	 * @param	String		$extKey
	 * @return	Array
	 */
	public static function getExtConf($extKey) {
		return TodoyuArray::assure(Todoyu::$CONFIG['EXT'][$extKey]['extConf']);
	}

}


?>