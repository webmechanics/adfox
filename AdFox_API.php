<?php

// простой класс для работы с AdFox API
// для работы требуется curl, собранный с поддержкой SSL + соотв. PHP расширение

class Adfox_API {
	
	private $url = "https://login.adfox.ru/API.php";
	private $result = "";
	
	function __construct($data = array()) {
		$this->options = $data;
	}

	function setUrl($url = "https://login.adfox.ru/API.php") {
		$this->url = $url;
	}
	
	function setLogin($login) {
		$this->options['loginAccount'] = $login;		
	}
	
	function setPassword($password) {
		$this->options['loginPassword'] = $password;				
	}	
	
	function setObject($object) {
		$this->options['object'] = $object;				
	}	
	
	function setObjectId($object_id) {
		$this->options['objectID'] = $object_id;			
	}	
	
	function setAction($action) {
		$this->options['action'] = $action;	
	}	
	
	function setActionObject($action_object) {
		$this->options['actionObject'] = $action_object;	
	}
	
	function setCustomVar($var, $value) {
		$this->options[$var] = mb_convert_encoding($value, "windows-1251", "UTF-8");		
	}
	
	function setBinaryVar($var, $value) {
		$this->options[$var] = $value;		
	}
	
	function setXmlPath($path) {
		$this->options['xml'] = $path;
	}	
	
	function get() {
		$url = $this->url."?".http_build_query($this->options);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$this->result = curl_exec($curl);
		curl_close($curl);
	}
	
	function post() {
	
		$data = http_build_query($this->options);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$this->result = curl_exec($curl);
		curl_close($curl);
	}
	
	function debug(){
	
		$url = $this->url."?".http_build_query($this->options);
		
		print_r($url);
		print_r($this->result);
	}
	
	function show_request(){
	
		$url = $this->url."?".http_build_query($this->options);
		
		print_r($url);
	}
	
	function getResults() {
		
		$this->get();
		
		// Данные приходят в windows-1251, но! simplexml_load_string их как то сам конвертит в utf-8

		$this->result = simplexml_load_string($this->result);
		
		return $this->result;
	}
	
	function getPostResults() {
		
		$this->post();
		
		$this->result = simplexml_load_string($this->result);	
		
		return $this->result;
	}
	
	function getBinary() {
		
		$this->get();

		return $this->result;
	}
}