<?php
namespace controllers;

use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models\Site;
use models;
use controllers\ControllerBase;
use Ajax\semantic\html\content\view\HtmlItem;
use Ajax\service\JArray;
use models\Moteur;

/**
 * Controller AdminSiteController
 * @property JsUtils $jquery
 **/

class AdminSiteController extends ControllerBase
{
    public function index(){
        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("bts",["Géolocalisation","Éléments web","Moteur de recherche","Positionnement","Fond d'écran","Se déconnecter"]);
        $bts->setPropertyValues("data-ajax", ["geolocalisation/","elementsWeb/","moteur/","Positionnement/","fondEcran/","seDeconnecter/"]);
        $bts->getOnClick("AdminSiteController/","#divSites",["attr"=>"data-ajax"]);
        $this->jquery->compile($this->view);
        $this->loadView("sites\index.html");
    }
    
    public function geolocalisation(){
        echo "geo";
    }
    
    public function elementsWeb(){
        
    }
    
    public function moteur(){
        $moteurs=DAO::getAll("models\Moteur");
        $semantic=$this->jquery->semantic();
        $list=$semantic->htmlList("tblMoteurs",JArray::modelArray($moteurs,"getId","getLibelle"));
        /*$list->_addEvent($event, $jsCode);
        $list->*/
        
        
        
        //$table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs);
        //$table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        //$table->setFields(["id","libelle","code"]);
        //$table->setCaptions(["Id","Nom","code associé","Actions"]);
        //$table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        //$table->setUrls(["","AdminSiteController/edit","AdminSiteController/delete"]);
        //$table->setTargetSelector("#divMoteurs");
        echo $list->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function Positionnement(){
        
    }
    
    public function fondEcran(){
        
    }
    
    public function seDeconnecter(){
        
    }
    
    /*
    public function all(){
        $sites=DAO::getAll("models\Site");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        $table->setCaptions(["Id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","AdminSiteController/edit","AdminSiteController/delete"]);
        $table->setTargetSelector("#divSites");
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function addSite(){
        $this->_form(new Site(),"AdminSiteController/newSite/",49.201491,-0.380734);
    }
    
    private function _form($site, $action,$lat,$long){
        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        $form=$semantic->dataForm("frmSite", $site);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options","submit"]);
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
        $form->fieldAsSubmit("submit","green",$action,"#divSites");
        
        $this->loadView("sites\index.html",["jsMap"=>$this->_generateMap($lat,$long)]);
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function newSite(){
        $site=new Site();
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::insert($site)){
            echo "Le site ".$site->getNom()." a été ajouté.";
        }
    }
    
    public function delete($id){
        
        //  if(RequestUtils::isPost())
        {
            //echo " - ".$id." - ";
            $site=DAO::getOne("models\Site", $id);
            $site instanceof models\Site && DAO::remove($site);
            $this->forward("controllers\AdminSiteController","all");
            //echo "le site {$site} est supprimé";
            //if($site instanceof models\Site && DAO::remove($site))
             //{
             //echo "le site {$site} est supprimé";
             //}else{ echo "impossible a supp";}
        }
        //else{echo "accés interdit";}
    }
    
    public function _getSiteInGet(){
        //if(RequestUtils::isPost())
        {
            $id=RequestUtils::get('id');
            $site=DAO::getOne("models\Site", $id);
            if($site instanceof models\Site)
                return $site;
                return false;
        }
        //else
     //    {
       //  return false;
         //}
    }
    
    public function edit($id){
        //if($site=$this->_getSiteInGet()){
        $site=DAO::getOne("models\Site", $id);
        $this->_form($site,"AdminSiteController/update/".$id,$site->getLatitude(),$site->getLongitude());
        //$site instanceof models\Site && DAO::update($site);
        //$this->jquery->postFormOnClick("#btValider","AdminSiteController/update", "frmEdit","#divSites");
        //$this->jquery->compile($this->view);
        
        //        $this->loadView("AdminSiteController/edit.html");
        //}else{echo 'accés interdit';}
    }
    
    public function update($id){
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getNom()." a été modifié.";
        }
    }*/
    
    // GOOGLE MAP
    
    private function _generateMap($lat,$long){
        return "
        <script>
            var map;
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: {$lat}, lng: {$long}},
                    zoom: 17
                });
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }
}

