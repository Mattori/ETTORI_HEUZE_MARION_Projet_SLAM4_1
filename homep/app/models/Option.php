<?php
namespace models;
class Option{
	/**
	 * @id
	*/
	private $id;

	private $libelle;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getLibelle(){
		return $this->libelle;
	}

	 public function setLibelle($libelle){
		$this->libelle=$libelle;
	}

	 public function __toString(){
		return (isset($this->libelle))?$this->libelle:"Option@".\spl_object_hash($this);
	}

}