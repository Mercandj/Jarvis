<?php
namespace lib\Models;

class ConfigManager{
	protected static $instance;
	private $_parsed_json;
	private $_id_user;

	public static function getInstance(){
		if (!isset(static::$instance)){
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __construct(){
		$this->parse_json();
	}

	private function parse_json(){
		$json = file_get_contents(__DIR__.'/../../config/config.json');
		$this->_parsed_json = json_decode($json);
	}

	public function get($key){
		return $this->_parsed_json->{$key};
	}

	public function getId_user() {
		return $this->_id_user;
	}

	public function setId_user($id_user) {
		$this->_id_user = $id_user;
	}
}