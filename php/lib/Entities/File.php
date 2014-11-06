<?php
namespace lib\Entities;

class File extends \lib\Entity{

	const INVALID_URL = 1,
		INVALID_SIZE = 2,
		INVALID_VISIBILITY = 3;

	protected $_id,
		$_url,
		$_size,
		$_visibility;

	public function getId(){
		return $this->_id;
	}

	public function getUrl(){
		return $this->_url;
	}

	public function getSize(){
		return $this->_size;
	}

	public function getVisibility(){
		return $this->_visibility;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setUrl($url){
		if(!empty($url)){
			$this->_url = $url;
		}else{
			$this->_errors[] = self::INVALID_URL;
		}
	}

	public function setSize($size){
		if(!empty($size)){
			$this->_size = $size;
		}else{
			$this->_errors[] = self::INVALID_SIZE;
		}
	}

	public function setVisibility($visibility){
		if(!empty($visibility)){
			$this->_visibility = $visibility;
		}else{
			$this->_errors[] = self::INVALID_VISIBILITY;
		}
	}

	public function isValid(){
		return !empty($this->_id) && !empty($this->_url);
	}

    public function toArray(){
		$json = [];
		$json['id'] = $this->getId();
		$json['url'] = $this->getUrl();
		$json['size'] = $this->getSize();
        return $json;
    }
}