<?php
/**
 * Simple RSS Feed applet
 * @author jtylek@gmail.com
 * @copyright jtylek@gmail.com
 * @license SPL
 * @version 0.2
 * @package applets-RSS_Feed
 */
defined("_VALID_ACCESS") || die('Direct access forbidden');

class Applets_rssfeedInstall extends ModuleInstall {

	public function install() {
		return true;
	}
	
	public function uninstall() {
		return true;
	}
	
	public function version() {
		return array("0.2");
	}
	
	public function requires($v) {
		return array(
			array('name'=>'Base/Dashboard','version'=>0));
	}
	
	public static function info() {
		return array(
			'Description'=>'RSS Feed',
			'Author'=>'jtylek@gmail.com',
			'License'=>'SPL');
	}
	
	public static function simple_setup() {
		return true;
	}
	
}

?>