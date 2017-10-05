<?php
namespace models;
class Statut{
	/**
	 * @id
	*/
	private $id;

	private $libelle;

	private $elementsMasques;

	private $fondEcran;

	private $ordre;

	/**
	 * @oneToMany("mappedBy"=>"statut","className"=>"models\Utilisateur")
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

	 public function getElementsMasques(){
		return $this->elementsMasques;
	}

	 public function setElementsMasques($elementsMasques){
		$this->elementsMasques=$elementsMasques;
	}

	 public function getFondEcran(){
		return $this->fondEcran;
	}

	 public function setFondEcran($fondEcran){
		$this->fondEcran=$fondEcran;
	}

	 public function getOrdre(){
		return $this->ordre;
	}

	 public function setOrdre($ordre){
		$this->ordre=$ordre;
	}

	 public function getUtilisateurs(){
		return $this->utilisateurs;
	}

	 public function setUtilisateurs($utilisateurs){
		$this->utilisateurs=$utilisateurs;
	}

}