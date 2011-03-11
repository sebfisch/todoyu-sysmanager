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


	public static function getLocaleOptions(TodoyuFormElement $field) {
		return TodoyuSysmanagerSystemConfigManager::getLocaleOptions();
	}


	public static function getTimezoneOptionsGrouped(TodoyuFormElement $field) {
		$timezones	= TodoyuContactViewHelper::getTimezoneOptionsGrouped($field);

		foreach($timezones as $group => $options) {
			foreach($options as $index => $option) {
				$timezones[$group][$index]['value'] = $option['label'];
			}
		}

		return $timezones;
	}

}

?>