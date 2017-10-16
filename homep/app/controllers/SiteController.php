<?php
namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Site;

/**
 * Controller SiteController
 * @property JsUtils $jquery
 **/
class SiteController extends ControllerBase
{
    
    public function index(){
        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("bts",["Liste des sites","Ajouter un site..."]);
        $bts->setPropertyValues("data-ajax", ["all/","addSite/"]);
        $bts->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
        //$this->jquery->exec("initMap();",true);
        $this->jquery->compile($this->view);
        $this->loadView("sites\index.html");
        //$this->loadView("sites\index.html",["jsMap"=>$this->_generateMap(49.201491, -0.380734)]);
    }
    
    public function all(){
        $sites=DAO::getAll("models\Site");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        $table->setCaptions(["Id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","SiteController/edit","SiteController/delete"]);
        $table->setTargetSelector("#divSites");
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function addSite(){
        $this->_form(new Site(),"SiteController/newSite/",49.201491,-0.380734);
    }
    
    private function _form($site, $action,$lat,$long){
        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        $form=$semantic->dataForm("frmSite", $site);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options","submit"]);
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
        $form->fieldAsSubmit("submit","green",$action,"#divSites");
        /*$this->jquery->click("#map","
         console.log(event);
         var latlong = event.latLng;
         var lat = latlong.lat();
         var long = latlong.lng();
         alert(lat+' - '+lng);
         ");*/
        //$this->jquery->change("[name=latitude]","alert('lat change : '+event.target.value);");
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
            $this->forward("controllers\SiteController","all");
            //echo "le site {$site} est supprimé";
            /*if($site instanceof models\Site && DAO::remove($site))
             {
             echo "le site {$site} est supprimé";
             }else{ echo "impossible a supp";}*/
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
        /*else
         {
         return false;
         }*/
    }
    
    public function edit($id){
        //if($site=$this->_getSiteInGet()){
        $site=DAO::getOne("models\Site", $id);
        $this->_form($site,"SiteController/update/".$id,$site->getLatitude(),$site->getLongitude());
        //$site instanceof models\Site && DAO::update($site);
        //$this->jquery->postFormOnClick("#btValider","SiteController/update", "frmEdit","#divSites");
        //$this->jquery->compile($this->view);
        
        //        $this->loadView("SiteController/edit.html");
        //}else{echo 'accés interdit';}
    }
    
    public function update($id){
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getNom()." a été modifié.";
        }
    }
    
    private function _generateMap($lat,$long){
        return "
        <script>
            var map={};
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: {$lat}, lng: {$long}},
                    zoom: 17
                });
                map.addListener('click',function(event){
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();
                })
                frmSite-latitude.addListener('change', function(event){
                    event.target.value=map.latLng.lat();
                })
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }
}