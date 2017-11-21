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
    // (simulation) id du site auquel on est admin
    private $idDuSite = 1;
    
    public function initialize(){
        parent::initialize();
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            echo $user->getLogin();
        }
    }
    
    // dashboard de la page
    public function index(){
        
        // /!\ il manque la gestion de l'accès si on est connecté ou pas /!\
        
        $semantic=$this->jquery->semantic();
        echo "ici, on administre le site qui a pour identifiant: ".$this->idDuSite;
        
        // création de 3 boutons (edits des models (site, moteur) et la connexion: 
        $bts=$semantic->htmlButtonGroups("bts",["Configuration","Moteur de recherche","Se connecter"]);
        // on leurs associe une donnée renvoyant à des méthodes du controller:  
        $bts->setPropertyValues("data-ajax", ["editSite/","moteur/","SeConnecter"]);
        // au clic des boutons, est associé la redirection vers la methode indiqué en data-ajax: 
        $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
        // on compile les informations de cette fonction puis on affiche nos boutons: 
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
        
        // ---------- LISTE DES MOTEURS ------------
        // on cherche le moteur que l'on a selectionnée afin de l'indiqué:
        $site=DAO::getOne("models\Site",$this->idDuSite);
        // recupération du moteur selectionnée: 
        $moteurSelected=$site->getMoteur();
        // recuperation de tout les moteurs: 
        $moteurs=DAO::getAll("models\Moteur");
        // on met ces moteurs dans un tableau
        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs);
        // identifiant du moteur en identifieur
        $table->setIdentifierFunction("getId");
        
        $table->setFields(["id","nom","code"]);
        $table->setCaptions(["id","nom","code du moteur","action","Selectioner"]);
        
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        //$table->setUrls('','AdminSiteController/edit/','AdminSiteController/delete/');
        
        // ----------- SELECTIONNER UN MOTEUR POUR NOTRE SITE -----------
        
        // on différencie le moteur déjà selectionné des autres
        $table->addFieldButton("Selectionner",false,function(&$bt,$instance) use($moteurSelected){
            if($instance->getId()==$moteurSelected->getId()){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
        // on attribue une couleur à notre moteur et la possibilité de selectionner un des autres moteurs à la place
        $table->onNewRow(function($row,$instance) use($moteurSelected){
            if($instance->getId()===$moteurSelected->getId()){
                $row->setProperty("style","background-color:#949da5;");
            }
        });
        $this->jquery->getOnClick("._toSelect", "AdminSiteController/selectionner","#divSite",["attr"=>"data-ajax"]);
        
        // ---------- AJOUTER MOTEUR  ------------
        
        $bts=$semantic->htmlButtonGroups("bts",["Ajouter un moteur"]);
        $bts->setPropertyValues("data-ajax", ["addMoteur/"]);
        
        // ---------- POSSIBILITÉ OU NON QUE UTILISATEUR MODIFIE -------
        
        // à faire
        
        echo $table->compile($this->jquery);
        echo $bts->compile($this->jquery);
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
        // var_dump($_SESSION["user"]);
    }
    
    // module de la page
    public function seDeconnecter(){
        if(isset($_SESSION["user"])){
            unset($_SESSION["user"]);
            $this->loadView("AdminSite\index.html");
        }else{ echo "erreur: vous netes pas sensé pouvoir vous deconnecter sans  etre connecté";}
    }
    
    // ----------- les actioins liés au site -------
    
    public function editSite()
    {   
        $site=DAO::getOne("models\Site", $this->idDuSite);
        $this->_form($site,"SiteController/update/".$id,$site->getLatitude(),$site->getLongitude());
    }
    
    private function _frmSite($site, $action,$lat,$long){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
        $form=$semantic->dataForm("frmSite", $site);
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options","submit"]);
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divSites");
        // Chargement de _generateMap
        $this->loadView("sites\index.html",["jsMap"=>$this->_generateMap($lat,$long)]);
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function editSite_End()
    {
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getId()."->".$site->getNom()." a été modifié.";
        }
    }
    
    // ----------- les actioins liés aux moteurs -------
    
    // Selection du moteur pour notre site
    public function selectionner()
    {
        // je récupère l'id du moteur que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $this->idDuSite);
        // je recupère le moteur que je souhaite selectionner:
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        // je modifie le moteur du site:
        $site->setMoteur($moteur);
        // j'envoi la requete qui modifie le moteur selectioné pour mon site
        //RequestUtils::setValuesToObject($site);
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","moteur");
    }
    
    public function addMoteur()
    {
        
        $this->_form(new Moteur(),"SiteController/newMoteur_End/");
    }
    
    public function editMoteur($idM)
    {
        $this->_form(new Moteur(),"SiteController/editMoteur_End/");
    }
    
    public function deleteMoteur($idM)
    {
        $this->_form(new Moteur(),"SiteController/deleteMoteur_End/");
    }
    
    private function _frmMoteur($action)
    {
        $semantic=$this->jquery->semantic();
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmMoteur' au paramètre instance de moteur
        $form=$semantic->dataForm("frmMoteur", new Moteur());
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom","code","submit"]);
        $form->setCaptions(["Nom","Code","Valider"]);
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divSites");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function addMoteur_End()
    {
        
    }
    
    public function editMoteur_End()
    {
        $moteur=DAO::getOne("models\Moteur", $id);
        RequestUtils::setValuesToObject($moteur,$_POST);
        if(DAO::update($moteur)){
            echo "Le moteur ".$moteur->getId()."->".$moteur->getNom()." a été modifié.";
        }
    }
    
    public function deleteMoteur_End()
    {
        $site=DAO::getOne("models\Moteur", $id);
        $site instanceof models\Moteur && DAO::remove($site);
        $this->forward("controllers\AdminSiteController","all");
    }
    
    // ----------- MAP -----------
     
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

