<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * SMTP account object
 *
 * @package		Todoyu
 * @subpackage	Sysmanager
 */
class TodoyuSysmanagerSmtpAccount extends TodoyuBaseObject {

	/**
	 * Constructor of the class
	 * 
	 * @param	Integer	$idAccount
	 */
	public function __construct($idAccount) {
		parent::__construct($idAccount, 'ext_sysmanager_smtpaccount');
	}



	/**
	 * Get account host name
	 *
	 * @return	String
	 */
	public function getHost() {
		return $this->data['host'];
	}



	/**
	 * Get account username
	 *
	 * @return	String
	 */
	public function getUsername() {
		return $this->data['username'];
	}



	/**
	 * Get decrypted account password
	 *
	 * @return	String
	 */
	public function getPassword() {
		return TodoyuCrypto::decrypt($this->data['password']);
	}



	/**
	 * Decrypt the account password
	 */
	public function decryptPassword() {
		$this->data['password']	= $this->getPassword();
	}



	/**
	 * @return	Integer
	 */
	public function getAuthentication() {
		return $this->getInt('authentication') ? 1:0;
	}



	/**
	 * Get account port
	 *
	 * @return	Integer
	 */
	public function getPort() {
		return $this->getInt('port');
	}



	/**
	 * Get account label (host:username)
	 *
	 * @return	String
	 */
	public function getLabel() {
		return $this->getHost() . ': ' . $this->getUsername();
	}



	/**
	 * Get template data
	 * decrypt password
	 *
	 * @return	Array
	 */
	public function getTemplateData() {
		$data	= parent::getTemplateData();

		$data['password'] = $this->getPassword();

		return $data;
	}

}

?>