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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerSystemConfigViewHelper {

	/**
	 * Get options for locale selector
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getLocaleOptions(TodoyuFormElement $field) {
		return TodoyuSysmanagerSystemConfigManager::getLocaleOptions();
	}



	/**
	 * Get grouped options for timezone selector
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getTimezoneOptionsGrouped(TodoyuFormElement $field) {
		$timezones	= TodoyuContactViewHelper::getTimezoneOptionsGrouped($field);

		foreach($timezones as $group => $options) {
			foreach($options as $index => $option) {
				$timezones[$group][$index]['value'] = $option['label'];
			}
		}

		return $timezones;
	}



	/**
	 * Get options for password minLength selector
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getPasswordMinLengthOptions(TodoyuFormElement $field) {
		$options	= array();

		for($i=0; $i<=12; $i++) {
			$options[] = array(
				'value'	=> $i,
				'label'	=> $i . ' ' . Label('sysmanager.ext.config.tab.passwordstrength.minLenth.characters')
			);
		}

		return $options;
	}

}

?>