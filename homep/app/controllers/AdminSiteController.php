<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Moteur;
use Ajax\JsUtils;

/**
 * Controller AdminSiteController
 * @property JsUtils $jquery
 **/

class AdminSiteController extends ControllerBase
{
    /* Méthode d'initialisation de l'utilisateur connecté et de son fond d'écran */
    public function initialize(){
        $fond=""; // Initialisation d'une chaîne de caractère 'fond' vide
        if(isset($_SESSION["user"])){ // Condition vérifiant si l'utilisateur est connecté
            $user=$_SESSION["user"]; // Affectation de l'utilisateur connecté dans une variable '$user'
            $fond=$user->getFondEcran(); // Affectation du fond choisi par l'utilisateur connecté à la variable '$fond'
        }
        if(!RequestUtils::isAjax()){ // Condition vérifiant si la requête n'est pas en Ajax
            $this->loadView("main/vHeader.html",["fond"=>$fond]); // Chargement de la vue 'vHeader.html avec le fond
        }
    }
    
    
    /* Méthode affichant l'index de la page d'administration du site */
    public function index(){
        $semantic=$this->jquery->semantic();
        if(!isset($_SESSION["user"])) {
            $bt=$semantic->htmlButton("bts","Connexion");
            $bt->postOnClick("AdminSiteController/connexion/","{action:'AdminSiteController/submit'}","#divSite",["attr"=>""]);
        } else {
            if($_SESSION["user"]->getStatut()->getId() >=2) {
                $bts=$semantic->htmlButtonGroups("bts",["Configuration","Moteur de recherche","Options utilisateur","Options établissement","Deconnexion"]);
                $bts->setPropertyValues("data-ajax", ["configuration/","moteur/","optionsUtilisateur/","optionsEtablissement/","deconnexion/AdminSiteController/index"]);
                $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
            } else {
                echo "t'es connecté mais tu n'a pas accès à la page d'administration du site";
                $bts=$semantic->htmlButtonGroups("bts",["Deconnexion"]);
                $bts->setPropertyValues("data-ajax", ["deconnexion/AdminSiteController/index"]);
                $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
            }
        }
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\index.html");
    }
    
    
    /* Méthode affichant la configuration du site de l'utilisateur connecté */
    public function configuration() {
        
        
        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur
        $semantic->setLanguage("fr"); // Affectation du langage français à la 'semantic'

        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId()); // Récupération des informations du site depuis l'id de l'utilisateur connecté
        
        $form=$semantic->dataForm("frmSite", $site); // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' à la variable '$site'
        $form->setValidationParams(["on"=>"blur", "inline"=>true]); // Envoi des paramètres du formulaire lors de sa validation
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options\n","submit"]); // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]); // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->fieldAsSubmit("submit","green fluid","AdminSiteController/editSiteConfirm","#divSite"); // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSite'
        
        $this->jquery->compile($this->view); // Chargement de la page HTML 'index.html' de la vue 'configuration.html' avec la génération de la carte Google via la fonction privée 'generateMap'
        $this->loadView("AdminSite\configuration.html",["jsMap"=>$this->_generateMap($site->getLatitude(),$site->getLongitude())]);
    }
    
    
    /* Méthode affichant les options données à l'utilisateur connecté par l'administrateur du site */
    public function optionsUtilisateur(){
        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsU", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getSite()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getSite()->getOptions());

        $form->addFieldButtons(['personnalisable','non-perso'],true,'');
        
        echo $form->compile($this->jquery);
    }
    
    
    /* Méthode de tests des GET et des POST */
    public function checked(){
        var_dump($_POST);
        var_dump($_GET);
    }
    
    
    public function optionsEtablissement(){
        
    }
    
    

    /* Méthode permettant de confirmer l'édition des actions liés au site */
    public function editSiteConfirm()
    {
        $recupId = explode('/', $_GET['c']);
        $site=DAO::getOne("models\Site", $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId()));
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getId()."->".$site->getNom()." a été modifié.";
            $this->forward("controllers\AdminSiteController","configuration");
        }
    }
    
    
    /* Méthode permettant d'afficher la liste des moteurs */
    public function moteur(){
        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur
        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId()); // Récupération de l'id du site à partir de l'utilisateur connecté
        
        $moteurSelected=$site->getMoteur(); // Recupération du moteur du site selectionné
        $moteurs=DAO::getAll("models\Moteur"); // Recuperation de tous les moteurs
        
        
        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs); // Ajout de tous les moteurs dans un nouveau tableau
        $table->setIdentifierFunction("getId"); // Identification du moteur en tant qu'identifiant
        $table->setFields(["id","nom","code"]); // Détermination des champs du moteur à afficher
        $table->setCaptions(["Id","Nom","Code du moteur","action","Sélectioner"]); // Détermination des noms de colonnes correspondants aux champs affichés
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]); // Ajout des boutons d'éditions et de suppression à chaque moteur
        $table->setUrls(['','AdminSiteController/editMoteur/','AdminSiteController/deleteMoteur/']); // Assignation des méthodes aux boutons d'édition et de suppression
        $table->setTargetSelector("#divSite"); // Assignation du bloc/de la div dans laquelle renvoyer 
        
        $table->addFieldButton("Selectionner",false,function(&$bt,$instance) use($moteurSelected) { // Différenciation du moteur déjà selectionné par rapport aux autres
            if($instance->getId()==$moteurSelected){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
        
        $this->jquery->getOnClick("._toSelect", "AdminSiteController/selectionner","#divSite",["attr"=>"data-ajax"]); // Affectation du clic sur le bouton Sélectionner
        
        $btAdd=$semantic->htmlButton('btAdd','Ajouter un moteur'); // Initialisation d'une variable du bouton "Ajouter un moteur" d'id 'btAdd'
        $btAdd->getOnClick("AdminSiteController/newMoteur","#divSite"); // Affectation du clic sur la variable du bouton '$btAdd'

        echo $table->compile($this->jquery);
        echo $btAdd->compile($this->jquery);
        
        echo $this->jquery->compile();
    }

    
    // Sélection du moteur pour le site
    public function selectionner()
    {
        $recupId = explode('/', $_GET['c']); // Récupération de l'id du moteur à sélectionner avec un explode de l'URL en tant que paramètre
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId()); // Récupération du site à administrer
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]); // Récupération du moteur à sélectionner
        $site->setMoteur($moteur); // Modification du moteur du site
        $site instanceof models\Site && DAO::update($site); // Envoi de la requete modifiant le moteur selectioné pour le site
        $this->forward("controllers\AdminSiteController","moteur"); // Retour arrière vers le controlleur AdminSiteController
    }
    
    public function newMoteur()
    {
        $this->_frmMoteur(null,"AdminSiteController/newMoteurConfirm/");
    }
    
    public function editMoteur()
    {
        // je récupère l'id du moteur que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // $recupId[2] représente l'identifiant du moteur
        $this->_frmMoteur($recupId[2],"AdminSiteController/editMoteurConfirm/");
    }
    
    public function deleteMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        // $recupId[2] représente l'identifiant du moteur
        $this->_frmMoteur($recupId[2],"AdminSiteController/deleteMoteurConfirm/");
    }
    
    private function _frmMoteur($idM,$action)
    {
        if($idM != null){
            $moteur=DAO::getOne("models\Moteur", $idM);
        }
        else
        {
            $moteur=new Moteur();
        }
        
        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur
        $semantic->setLanguage("fr"); // Affectation du langage français à la 'semantic'
        
        $form=$semantic->dataForm("frmMoteur",$moteur); // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmMoteur' au paramètre instance de moteur
        $form->setValidationParams(["on"=>"blur", "inline"=>true]); // Envoi des paramètres du formulaire lors de sa validation
        $form->setFields(["id","nom","code","submit"]);
        $form->setCaptions(["id","Nom","Code","Valider"]);
        $form->fieldAsHidden("id");
        $form->fieldAsSubmit("submit","green",$action,"#divSite"); // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function newMoteurConfirm()
    {
        $moteur= new Moteur();
        RequestUtils::setValuesToObject($moteur,$_POST);
        if(DAO::insert($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été ajouté.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    public function editMoteurConfirm()
    {
        $moteur=DAO::getOne("models\Moteur", $_POST['id']);
        RequestUtils::setValuesToObject($moteur,$_POST);
        if(DAO::update($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été modifié.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    public function deleteMoteurConfirm()
    {
        $idMoteur=$_POST['id'];
        $moteur=DAO::getOne("models\Moteur", 'id='.$idMoteur);
        if(DAO::remove($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été supprimé.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    // Méthode permettant d'afficher une carte GoogleMaps
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

