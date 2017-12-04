<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;
 /**
 * Controller Main
 **/
class Main extends ControllerBase{

    public function initialize(){
        $fond="";
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            $fond=$user->getFondEcran();
            //echo $user->getLogin();
        }
        if(!RequestUtils::isAjax()){
            $this->loadView("main/vHeader.html",["fond"=>$fond]);
        }
    }
    
	public function index(){
		$semantic=$this->jquery->semantic();

		if(isset($_SESSION["user"])){
		    echo "test cnx";
		    
		    $semantic->htmlHeader("header",1,"HOMEPAGE");
		    $bt=$semantic->htmlButtonGroups("menu",["Deconnexion"]);
		    $bt->setPropertyValues("data-ajax", ["deconnexion/"]);
		    $bt->getOnClick("Main/","#menu",["attr"=>"data-ajax"]);
		    if($_SESSION["user"]->getStatut()->getId() > 1)
		    {
		        
		    }
		    if($_SESSION["user"]->getStatut()->getId() > 2)
		    {
		        
		    }

		}else{
		    $semantic->htmlHeader("header",1,"HOMEPAGE");
		    $bt=$semantic->htmlButtonGroups("menu",["Connexion"]);
		    $bt->setPropertyValues("data-ajax", ["connexion/"]);
		    $bt->getOnClick("Main/","#menu",["attr"=>"data-ajax"]);
		}
		$this->jquery->compile($this->view);
		$this->loadView("index.html");
	    
	}
}