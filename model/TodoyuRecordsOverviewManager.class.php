<?php


class TodoyuRecordsOverviewManager {

	public static function getAllRecordInfos() {
		$info		= array();
		$extRecords	= TodoyuExtManager::getAllRecordsConfig();

//		TodoyuDebug::printHtml($extRecords);

		foreach($extRecords as $extKey => $records) {
			$info[$extKey]['title']		= Label($extKey . '.ext.title');
			$info[$extKey]['records'] 	= array();

			foreach($records as $type => $config) {
				$info[$extKey]['records'][$type]['type']	= $type;
				$info[$extKey]['records'][$type]['title']	= Label($config['label']);

				if( isset($config['table']) ) {
					$info[$extKey]['records'][$type]['count']	= TodoyuExtRecordManager::getRecordCount($config['table']);
				} else {
					$info[$extKey]['records'][$type]['count']	= '???';
				}
			}
		}

		return $info;

//		TodoyuDebug::printHtml($extRecords);
	}

}


?>