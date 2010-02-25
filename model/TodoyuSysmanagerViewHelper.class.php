<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * System Manager View Helper
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerViewHelper {

	/**
	 * Get options for extension selector
	 *
	 * @param	TodoyuFormElement		$field
	 * @return	Array
	 */
	public static function getExtensionOptions(TodoyuFormElement $field) {
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		$options	= array();

		foreach($extKeys as $extKey) {
			$options[] = array(
				'value'	=> $extKey,
				'label'	=> TodoyuLanguage::getLabel($extKey . '.ext.title') . ' (' . $extKey . ')'
			);
		}

		return $options;
	}

}

?>