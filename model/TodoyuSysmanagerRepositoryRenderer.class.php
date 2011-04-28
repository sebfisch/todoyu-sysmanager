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
 * Render updates screens
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerRepositoryRenderer {

	/**
	 * Fetch available updates from TER server and render listing
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public static function renderSearch(array $params = array()) {
		if( ! TodoyuSysmanagerRepositoryManager::isRepositoryReachable() ) {
			$tmpl	= 'ext/sysmanager/view/repository-noconnection.tmpl';
			return render($tmpl);
		}

		if( array_key_exists('query', $params) ) {
			$query	= trim($params['query']);
		} else {
			$query	= TodoyuSysmanagerRepositoryManager::getLastSearchKeyword();
			$params['query'] = $query;
		}

		$xmlPath	= 'ext/sysmanager/config/form/repository-search.xml';
		$form		= TodoyuFormManager::getForm($xmlPath);
		$form->setFormData($params);
		$form->setUseRecordID(false);

		$tmpl	= 'ext/sysmanager/view/repository-search.tmpl';
		$data	= array(
			'query'		=> $query,
			'form'		=> $form->render(),
			'results'	=> self::renderSearchResults($query)
		);

		return render($tmpl, $data);
	}



	/**
	 * Search available extensions updates
	 *
	 * @param	String	$query
	 * @return	String
	 */
	public static function renderSearchResults($query) {
		$repository	= new TodoyuSysmanagerRepository();

		$tmpl	= 'ext/sysmanager/view/repository-search-list.tmpl';
		$data	= $repository->searchExtensions($query);

		TodoyuDebug::printInFireBug($data['extensions'], 'extensions');

		return render($tmpl, $data);
	}



	/**
	 * Find available updates for current client and render updates list
	 *
	 * @return	String
	 */
	public static function renderUpdate() {
		$repository	= new TodoyuSysmanagerRepository();
		$updates	= $repository->searchUpdates();

		$tmpl	= 'ext/sysmanager/view/repository-update.tmpl';
		$data	= array(
			'updates'	=> $updates
		);

		return render($tmpl, $data);
	}


	public static function renderExtensionUpdateDialog($ext) {
		$data	= array(
			'info'		=> TodoyuSysmanagerRepositoryManager::getRepoInfo($ext),
			'title'		=> 'Install Extension Update',
			'okAction'	=> 'Todoyu.Ext.sysmanager.Repository.Update.installExtensionUpdate(\'' . $ext . '\')'
		);

		return self::renderExtensionDialog($data);
	}

	public static function renderExtensionInstallDialog($ext) {
		$data	= array(
			'info'		=> TodoyuSysmanagerRepositoryManager::getRepoInfo($ext),
			'title'		=> 'Install New Extension',
			'okAction'	=> 'Todoyu.Ext.sysmanager.Repository.Search.installExtension(\'' . $ext . '\')'
		);

		return self::renderExtensionDialog($data);

	}


	private static function renderExtensionDialog($data) {
		$tmpl	= 'ext/sysmanager/view/repository-dialog-ext.tmpl';

		return render($tmpl, $data);
	}


	public static function renderCoreUpdateDialog() {
		$tmpl	= 'ext/sysmanager/view/repository-dialog-core.tmpl';

		$coreUpdate	= TodoyuSysmanagerRepositoryManager::getRepoInfo('core');

		TodoyuDebug::printInFireBug($coreUpdate, '$coreUpdate');

		$data	= array(
			'update'	=> $coreUpdate
		);

		return render($tmpl, $data);
	}

}

?>