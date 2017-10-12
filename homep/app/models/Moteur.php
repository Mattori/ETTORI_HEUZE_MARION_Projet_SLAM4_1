<?php
namespace models;
class Moteur{
	/**
	 * @id
	*/
	private $id;

	private $libelle;

	private $code;

	/**
	 * @oneToMany("mappedBy"=>"moteur","className"=>"models\Etablissement")
	*/
	private $etablissements;

	/**
	 * @oneToMany("mappedBy"=>"moteur","className"=>"models\Utilisateur")
	*/
	private $utilisateurs;

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

	 public function getCode(){
		return $this->code;
	}

	 public function setCode($code){
		$this->code=$code;
	}

	 public function getEtablissements(){
		return $this->etablissements;
	}

	 public function setEtablissements($etablissements){
		$this->etablissements=$etablissements;
	}

	 public function getUtilisateurs(){
		return $this->utilisateurs;
	}

	 public function setUtilisateurs($utilisateurs){
		$this->utilisateurs=$utilisateurs;
	}
	
	public function __toString(){
	    return $this->libelle;
	}

}