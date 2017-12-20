<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlBreadcrumb;
use Ubiquity\orm\DAO;
use Ubiquity\utils\RequestUtils;


/**
 * Controller UserController
 * @property JsUtils $jquery
 **/
class Connexion extends ControllerBase
{
    /**
     * Affiche le menu de la page si un utilisateur normal est connecté
     * 
     * @see \Ubiquity\controllers\Controller::index()
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        
        $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
        $menu=$semantic->htmlMenu("menu9");
        $menu->addItem("<h4 class='ui header'>Accueil</h4>");
        
        if(!isset($_SESSION["user"])) {
            $menu->addItem("<h4 class='ui header'>Connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "connexion/UserController/submit"]);
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled("Google");
            
            $frm->setProperty("action","https://www.google.fr/search?q=");
        } else {
            if($_SESSION["user"]->getStatut()->getId() ==3) {
                $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax","hasLoader"=>false]);
                $menu->setVertical();
                
                $this->jquery->compile($this->view);
                $this->loadView("sites\index.html");
            }
            if($_SESSION["user"]->getStatut()->getId() ==2) {
                $menu->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax","hasLoader"=>false]);
                $menu->setVertical();
                
                $this->jquery->compile($this->view);
                $this->loadView("AdminSite\index.html");
            }
            if($_SESSION["user"]->getStatut()->getId() ==1) {
                $menu->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax","hasLoader"=>false]);
                $menu->setVertical();
            }
        }
        
        $bc=new HtmlBreadcrumb("bc2", array("Accueil","Utilisateur"));
        $bc->setContentDivider(">");
        echo $bc;
        
        $frm->setProperty("method","get");
        $frm->setProperty("target","_new");
        $bt=$input->addAction("Rechercher");
        echo $frm;
        
        $this->jquery->compile($this->view);
        $this->loadView("Utilisateur\index.html");
    }
}