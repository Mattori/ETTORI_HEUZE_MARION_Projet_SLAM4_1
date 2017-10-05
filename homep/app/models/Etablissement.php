<?php
namespace models;
class Etablissement{
	/**
	 * @id
	*/
	private $id;

	private $fondEcran;

	private $couleur;

	private $ordre;

	private $options;

	/**
	 * @oneToMany("mappedBy"=>"etablissement","className"=>"models\Lienweb")
	*/
	private $lienwebs;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\Moteur","name"=>"id_moteur","nullable"=>false)
	*/
	private $moteur;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getFondEcran(){
		return $this->fondEcran;
	}

	 public function setFondEcran($fondEcran){
		$this->fondEcran=$fondEcran;
	}

	 public function getCouleur(){
		return $this->couleur;
	}

	 public function setCouleur($couleur){
		$this->couleur=$couleur;
	}

	 public function getOrdre(){
		return $this->ordre;
	}

	 public function setOrdre($ordre){
		$this->ordre=$ordre;
	}

	 public function getOptions(){
		return $this->options;
	}

	 public function setOptions($options){
		$this->options=$options;
	}

	 public function getLienwebs(){
		return $this->lienwebs;
	}

	 public function setLienwebs($lienwebs){
		$this->lienwebs=$lienwebs;
	}

	 public function getMoteur(){
		return $this->moteur;
	}

	 public function setMoteur($moteur){
		$this->moteur=$moteur;
	}

}