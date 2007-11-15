<?php
/**
 * Provides login audit log
 * @author pbukowski@telaxus.com & jtylek@telaxus.com
 * @copyright pbukowski@telaxus.com & jtylek@telaxus.com
 * @license SPL
 * @version 0.1
 * @package epesi-base
 */
defined("_VALID_ACCESS") || die('Direct access forbidden');

class Base_LoginAuditInstall extends ModuleInstall {

	public function install() {
		Base_ThemeCommon::install_default_theme($this -> get_type());
	
	/*	
		$ret = DB::CreateTable('login_audit',"session_name C(32) NOTNULL, client_id I2,");
	if($ret===false)
		die('Invalid SQL query - Database module (login_audit table)');
	*/
		return true;
	}
	
	public function uninstall() {
		Base_ThemeCommon::uninstall_default_theme($this -> get_type());
		return true;
	}
	
	public function version() {
		return array("0.1");
	}
	
	public function requires($v) {
		return array(
			array('name'=>'Base/Theme','version'=>0),
			array('name'=>'Tools/WhoIsOnline','version'=>0));
	}
	
		public static function info() {
		return array('Author'=>'<a href="mailto:jtylek@telaxus.com">Janusz Tylek</a> and <a href="mailto:pbukowski@telaxus.com">Pawel Bukowski</a> (<a href="http://www.telaxus.com">Telaxus LLC</a>)', 'License'=>'TL', 'Description'=>'Provides login audit log.');
	}
		
	public static function simple_setup() {
		return true;
	}
	
}

?>