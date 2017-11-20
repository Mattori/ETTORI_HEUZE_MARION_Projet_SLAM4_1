<?php
namespace controllers;

use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models\Site;
use models;
use controllers\ControllerBase;
use Ajax\service\JArray;
use models\Moteur;
use Ajax\semantic\html\content\view\HtmlItem;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;

/**
 * Controller AdminSiteController
 * @property JsUtils $jquery
 **/

class AdminSiteController extends ControllerBase
{
    public function initialize(){
        parent::initialize();
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            echo $user->getLogin();
        }
    }
    // (simulation) id du site auquel on est admin
    private $idDuSite = 1;
    
    // dashboard de la page
    public function index(){
        $semantic=$this->jquery->semantic();
        
        $bts=$semantic->htmlButtonGroups("bts",["Géolocalisation","Éléments web","Moteur de recherche","Positionnement","Fond d'écran","Se connecter"]);
        $bts->setPropertyValues("data-ajax", ["geolocalisation/","elementsWeb/","moteur/","Positionnement/","fondEcran/","seConnecter/"]);
        
        $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
        $this->jquery->compile($this->view);
        
        $this->loadView("AdminSite\index.html");
        
    }
    
    // module de la page
    public function geolocalisation(){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Récupération des informations du site
        $site=DAO::getOne("models\Site",$this->idDuSite);
        
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");

        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
        $form=$semantic->dataForm("frmSite", $site);
        
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setFields(["latitude","longitude","ecart\n","submit"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Latitude","Longitude","Ecart","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSite'
        $form->fieldAsSubmit("submit","green","update","#divSite");
        
        // Chargement de la page HTML 'index.html' de la vue 'sites' avec la génération de la carte Google
        // via la fonction privée '_generateMap'
        $this->loadView("AdminSite\index.html",["jsMap"=>$this->_generateMap($site->getLatitude(),$site->getLongitude())]);
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    // module de la page
    public function elementsWeb(){
        
    }
    
    // module de la page
    public function moteur(){
        $semantic=$this->jquery->semantic();
        $moteurs=DAO::getAll("models\Moteur","idSite=".$this->idDuSite);
        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","nom","code"]);
        $table->setCaptions(["id","nom","code du moteur","action"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        //$table->addFieldButton("action",false,function(&$bt,$instance){$bt->addClass("select")->addIcon("archive",true,true);});
        $table->setUrls('','AdminSiteController/selectionner');
        $table->setTargetSelector("#divSite");
        
        $this->jquery->getOnClick(".select", "AdminSiteController/select","#divSite",["attr"=>"data-ajax"]);
        
        $bts=$semantic->htmlButtonGroups("bts",["Liste des sites","Ajouter un site"]);
        
        $bts->setPropertyValues("data-ajax", ["addMoteur/"]);
        $bts->getOnClick("AdminSiteController/","#divMoteurs",["attr"=>"data-ajax"]);
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    // module de la page
    public function Positionnement(){
        
    }
    
    // module de la page
    public function fondEcran(){
        $moteurs=DAO::getOne("models\Site",$this->idDuSite);
        $semantic=$this->jquery->semantic();        
    }

    public function seConnecter(){
        $frm=$this->jquery->semantic()->defaultLogin("connect");
        $frm->fieldAsSubmit("submit","green","AdminSiteController/submit","#div-submit");
        $frm->removeField("Connection");
        $frm->setCaption("login", "Identifiant");
        $frm->setCaption("password", "Mot de passe");
        $frm->setCaption("remember", "Se souvenir de moi");
        $frm->setCaption("forget", "Mot de passe oublié ?");
        $frm->setCaption("submit", "Connexion");
        echo $frm->asModal();
        $this->jquery->exec("$('#modal-connect').modal('show');",true);
        echo $this->jquery->compile($this->view);
        
    }
    
    public function submit(){
        // ["login"=>$_POST[login]]
        var_dump($_POST);
        $id=RequestUtils::get('id');
        $user=DAO::getOne("models\Utilisateur", "login='".$_POST["login"]."'");
        if(isset($user)){
            echo "Bonjour ".$user->getLogin()." !";
            $_SESSION["user"] = $user;
        }
        
    }
    
    public function testCo(){
        var_dump($_SESSION["user"]);
    }
    
    // module de la page
    public function seDeconnecter(){
        
    }
    
    // ACTIONS
    
    public function edit($id,$model){
        if($model == "Moteur")
        {
            $moteur=DAO::getOne("models\Moteur", $id);
            $this->_form($moteur,"SiteController/update/".$id."/Moteur",$moteur->getLatitude(),$moteur->getLongitude());
        }
        elseif($model == "Site")
        {
            $site=DAO::getOne("models\Site", $id);
            $this->_form($site,"SiteController/update/".$id."/Site",$site->getLatitude(),$site->getLongitude());
        }
    }
    
    public function update($id,$model){        
        if($model == "Moteur")
        {
            $moteur=DAO::getOne("models\Moteur", $id);
            RequestUtils::setValuesToObject($moteur,$_POST);
            if(DAO::update($moteur)){
                echo "Le moteur ".$moteur->getId()."->".$moteur->getNom()." a été modifié.";
            }
        }
        elseif($model == "Site")
        {
            $site=DAO::getOne("models\Site", $id);
            RequestUtils::setValuesToObject($site,$_POST);
            if(DAO::update($site)){
                echo "Le site ".$site->getId()."->".$site->getNom()." a été modifié.";
            }
        }
    }
    
    public function delete($id,$model){
        if($model == "Moteur")
        {
            $site=DAO::getOne("models\Moteur", $id);
            $site instanceof models\Moteur && DAO::remove($site);
            $this->forward("controllers\AdminSiteController","all");
        }
        elseif($model == "Site")
        {
            $site=DAO::getOne("models\Site", $id);
            $site instanceof models\Site && DAO::remove($site);
            $this->forward("controllers\AdminSiteController","all");
        }        
    }
    
     // MAP
     
     private function _generateMap($lat,$long){
         return "
        <script>
            // Déclaration de la carte Google Maps
            var map={};
            
            // Fonction d'initialisation de la carte, de ses éléments et de ses évènements
            function initMap() {
                // Options de la carte
                var optionsMap = {
					zoom: 17,
					center: {lat: {$lat}, lng: {$long}},
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
                // Affectation de la carte
                map = new google.maps.Map(document.getElementById('map'), optionsMap);
                
                // Options du cercle
                var optionsCercle = {
					map: map,
					center: map.getCenter(),
					radius: 200
				}
                // Affectation du cercle
				var cercle = new google.maps.Circle(optionsCercle);
				
                // Ajout d'un évènement lorsque l'on clique sur la carte
                map.addListener('click',function(event){
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à la valeur de la latitude de l'évènement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à la valeur de la longitude de l'évènement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();
                })
                
                // Ajout d'un évènement lorsque l'on change la latitude de la div d'id 'frmSite-latitude'
                frmSite-latitude.addListener('change', function(event){
                    // Affectation de la valeur de la cible de l'évènement à la valeur de la latitude de la carte
                    event.target.value=map.latLng.lat();
                })
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
     }
}

