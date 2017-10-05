<?php
namespace models;
class Utilisateur{
	/**
	 * @id
	*/
	private $id;

	private $login;

	private $password;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\Moteur","name"=>"id_moteur","nullable"=>false)
	*/
	private $moteur;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\Site","name"=>"id_site","nullable"=>false)
	*/
	private $site;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\Statut","name"=>"id_statut","nullable"=>false)
	*/
	private $statut;

	/**
	 * @oneToMany("mappedBy"=>"utilisateur","className"=>"models\Lienweb")
	*/
	private $lienwebs;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getLogin(){
		return $this->login;
	}

	 public function setLogin($login){
		$this->login=$login;
	}

	 public function getPassword(){
		return $this->password;
	}

	 public function setPassword($password){
		$this->password=$password;
	}

	 public function getMoteur(){
		return $this->moteur;
	}

	 public function setMoteur($moteur){
		$this->moteur=$moteur;
	}

	 public function getSite(){
		return $this->site;
	}

	 public function setSite($site){
		$this->site=$site;
	}

	 public function getStatut(){
		return $this->statut;
	}

	 public function setStatut($statut){
		$this->statut=$statut;
	}

	 public function getLienwebs(){
		return $this->lienwebs;
	}

	 public function setLienwebs($lienwebs){
		$this->lienwebs=$lienwebs;
	}

}