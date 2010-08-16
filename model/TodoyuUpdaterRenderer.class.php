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

/**
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuUpdaterRenderer {

	public static function renderBrowse(array $params = array()) {
		if( ! TodoyuUpdater::isUpdateServerReachable() ) {
			$tmpl	= 'ext/sysmanager/view/update-noconnection.tmpl';
			return render($tmpl);
		}

		$extQuery	= trim($params['extQuery']);

		$tmpl	= 'ext/sysmanager/view/browse.tmpl';
		$data	= array(
			'extQuery'	=> 'test 123',
			'results'	=> self::renderBrowseResultList($extQuery)
		);

		return render($tmpl, $data);
	}

	public static function renderBrowseResultList($query) {
		$results = TodoyuUpdater::searchExtensions($query);

		TodoyuDebug::printInFireBug($results, 'res');
//
//		foreach($results->Extensions->Extension->children() as $ext) {
//			TodoyuDebug::printInFireBug($ext->title, 'title');
//		}

		$tmpl	= 'ext/sysmanager/view/browse-results.tmpl';
		$data	= TodoyuArray::toArray($results, true);

		return render($tmpl, $data);
	}

	public static function renderUpdate() {
		return 'updater list';
	}

}

?>
