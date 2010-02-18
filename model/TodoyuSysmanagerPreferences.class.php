<?php

class TodoyuSysmanagerPreferences {

	/**
	 * Save a preference for contact
	 *
	 * @param	String		$preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Boolean		$idPerson
	 * @param	Integer		$idUser
	 */
	public static function savePref($preference, $value, $idItem = 0, $unique = false, $idArea = 0, $idPerson = 0) {
		TodoyuPreferenceManager::savePreference(EXTID_SYSMANAGER, $preference, $value, $idItem, $unique, $idArea, $idPerson);
	}



	/**
	 * Get a contact preference
	 *
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idPerson
	 * @return	String
	 */
	public static function getPref($preference, $idItem = 0, $idArea = 0, $unserialize = false, $idPerson = 0) {
		return TodoyuPreferenceManager::getPreference(EXTID_SYSMANAGER, $preference, $idItem, $idArea, $unserialize, $idPerson);
	}



	/**
	 * Get contact preferences
	 *
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idPerson
	 * @return	Array
	 */
	public static function getPrefs($preference, $idItem = 0, $idArea = 0, $idPerson = 0) {
		return TodoyuPreferenceManager::getPreferences(EXTID_SYSMANAGER, $preference, $idItem, $idArea, $idPerson);
	}



	/**
	 * Delete contact preference
	 *
	 * @param	String		$preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idPerson
	 */
	public static function deletePref($preference, $value = null, $idItem = 0, $idArea = 0, $idPerson = 0) {
		TodoyuPreferenceManager::deletePreference(EXTID_SYSMANAGER, $preference, $value, $idItem, $idArea, $idPerson);
	}

	public static function getActiveTab($type) {
		$tab	= self::getPref($type . '-tab');

		if( $tab === false ) {
			$tab = 'rights';
		}

		return $tab;
	}


	public static function saveActiveTab($type, $tab) {
		self::savePref($type . '-tab', $tab, 0, true);
	}


}

?>