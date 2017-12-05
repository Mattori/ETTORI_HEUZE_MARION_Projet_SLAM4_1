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
		    $semantic->htmlHeader("header",1,"HOMEPAGE du site ".$_SESSION["user"]->getSite()->getNom());
		    if($_SESSION["user"]->getStatut()->getId() > 1)
		    {
		        $bt=$semantic->htmlButtonGroups("menu",["Administration du site","Administration globale","Deconnexion"]);
		        $bt->setPropertyValues("data-ajax", ["AdminSiteController/","SiteController/","deconnexion/"]);
		        $bt->getOnClick("","#menu",["attr"=>"data-ajax"]);
		    }
		    elseif($_SESSION["user"]->getStatut()->getId() > 2)
		    {
		        $bt=$semantic->htmlButtonGroups("menu",["Page AdminSite","Deconnexion"]);
		        $bt->setPropertyValues("data-ajax", ["AdminSiteController/","deconnexion/"]);
		        $bt->getOnClick("","#menu",["attr"=>"data-ajax"]);
		    }
		    else 
		    {
		        $bt=$semantic->htmlButtonGroups("menu",["Deconnexion"]);
		        $bt->setPropertyValues("data-ajax", ["deconnexion/"]);
		        $bt->getOnClick("Main/","#menu",["attr"=>"data-ajax"]);
		    }

		}else{
		    $bt=$semantic->htmlButtonGroups("menu",["Connexion"]);
		    $bt->setPropertyValues("data-ajax", ["connexion/"]);
		    $bt->getOnClick("Main/","#menu",["attr"=>"data-ajax"]);
		}
		$this->jquery->compile($this->view);
		$this->loadView("index.html");
	    
	}
}