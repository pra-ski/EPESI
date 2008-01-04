<?php
/**
 * Example event module
 * @author pbukowski@telaxus.com
 * @copyright pbukowski@telaxus.com
 * @license SPL
 * @version 0.1
 * @package tests-calendar-meeting
 */
defined("_VALID_ACCESS") || die('Direct access forbidden');

class CRM_Calendar_MeetingCommon extends Utils_Calendar_EventCommon {
	public static $filter = null;
	
	public static function get($id) {
		if(self::$filter)
			$fil = ' AND created_by in '.self::$filter;
		else
			$fil = '';
		$row = DB::Execute('SELECT start,end,title,description,id,timeless,priority,created_by,created_on,edited_by,edited_on FROM crm_calendar_meeting_event WHERE id=%d'.$fil,array($id))->FetchRow();
		$result = array();
		if ($row) {
			foreach (array('start','id','title','description','timeless') as $v)
				$result[$v] = $row[$v];
			$result['duration'] = $row['end']-$row['start'];
			switch ($row['priority']) {
				case 2: $result['color'] = 'red'; break;
				case 1: $result['color'] = 'yellow'; break;
				default: $result['color'] = 'green';
			}
			$result['additional_info'] = 	Base_LangCommon::ts('CRM_Calendar_Meeting','Created by').' '.Base_UserCommon::get_user_login($row['created_by']). '<br>'.
											Base_LangCommon::ts('CRM_Calendar_Meeting','Created on').' '.$row['created_on']. '<br>'.
											(($row['edited_by'])?(
											Base_LangCommon::ts('CRM_Calendar_Meeting','Edited by').' '.Base_UserCommon::get_user_login($row['edited_by']). '<br>'.
											Base_LangCommon::ts('CRM_Calendar_Meeting','Edited on').' '.$row['edited_on']. '<br>'):'');
		}
		return $result;	
	}
	public static function get_all($start,$end) {
		if(self::$filter)
			$fil = ' AND created_by in '.self::$filter;
		else
			$fil = '';
//		print('SELECT start,end,title,description,id,timeless,priority,created_by,created_on,edited_by,edited_on FROM crm_calendar_meeting_event WHERE ((start>=%d AND start<%d) OR (end>=%d AND end<%d)) '.$fil);
		$ret = DB::Execute('SELECT start,end,title,description,id,timeless,priority,created_by,created_on,edited_by,edited_on FROM crm_calendar_meeting_event WHERE ((start>=%d AND start<%d) OR (end>=%d AND end<%d)) '.$fil,array($start,$end,$start,$end));
		$result = array();
		while ($row = $ret->FetchRow()) {
			$next_result = array();
			foreach (array('start','id','title','description','timeless') as $v)
				$next_result[$v] = $row[$v];
			$next_result['duration'] = $row['end']-$row['start'];
			switch ($row['priority']) {
				case 2: $next_result['color'] = 'red'; break;
				case 1: $next_result['color'] = 'yellow'; break;
				default: $next_result['color'] = 'green';
			}
			$next_result['additional_info'] = 	Base_LangCommon::ts('CRM_Calendar_Meeting','Created by').' '.Base_UserCommon::get_user_login($row['created_by']). '<br>'.
												Base_LangCommon::ts('CRM_Calendar_Meeting','Created on').' '.$row['created_on']. '<br>'.
												(($row['edited_by'])?(
												Base_LangCommon::ts('CRM_Calendar_Meeting','Edited by').' '.Base_UserCommon::get_user_login($row['edited_by']). '<br>'.
												Base_LangCommon::ts('CRM_Calendar_Meeting','Edited on').' '.$row['edited_on']. '<br>'):'');
			$result[] = $next_result;
		}
		return $result;
	}

	public static function delete($id) { //make sure that event owner is Acl::get_user....
		DB::Execute('DELETE FROM crm_calendar_meeting_event WHERE id=%d',array($id));
	}

	public static function update($id,$start,$duration,$timeless) { //make sure that event owner is Acl::get_user....
		DB::Execute('UPDATE crm_calendar_meeting_event SET start=%d, end=%d, timeless=%b WHERE id=%d',array($start,$start+$duration,$timeless,$id));
	}

	public static function decode_contact($id) {
		$contact = CRM_ContactsCommon::get_contact($id);
		return $contact['first_name']." ".$contact['last_name'];
		//return '['.$contact['Company Name'][0].'] '.$contact['First Name']." ".$contact['Last Name'];
	}
}

?>