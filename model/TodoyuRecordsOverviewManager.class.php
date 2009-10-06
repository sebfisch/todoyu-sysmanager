<?php


class TodoyuRecordsOverviewManager {

	public static function getAllRecordConfigs() {
		$extRecords	= TodoyuExtManager::getAllRecordsConfig();

//		foreach($extRecords as $extKey => $records) {
//			$extRecords[$extKey]['_title']	= Label($extKey . '.title');
//
//			foreach($records as $type => $config) {
//
//			}
//
//		}

		return $extRecords;

//		TodoyuDebug::printHtml($extRecords);
	}

}


?>