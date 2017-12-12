<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use models;
use models\Moteur;
use Ajax\JsUtils;

/**
 * Controller AdminSiteController
 * @property JsUtils $jquery
 **/
class AdminSiteController extends ControllerBase
{
    
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
    
    // --------- INDEX DE LA PAGE ADMINISTRATION DU SITE ----------
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
    
    
    // ------- METHODES PRINCIPALES DU CONTROLLER -------
    
    public function configuration() {
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        // Récupération des informations du site
        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId());
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
        $form=$semantic->dataForm("frmSite", $site);
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options\n","submit"]);
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSite'
        $form->fieldAsSubmit("submit","green fluid","AdminSiteController/editSiteConfirm","#divSite");
        // Chargement de la page HTML 'index.html' de la vue 'sites' avec la génération de la carte Google
        // via la fonction privée 'generateMap'
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\configuration.html",["jsMap"=>$this->_generateMap($site->getLatitude(),$site->getLongitude())]);
    }
    
    // -----------Methode Options Utilisateur(Site)------------
    public function optionsUtilisateur(){
        $semantic=$this->jquery->semantic();
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsU", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getSite()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getSite()->getOptions());
        
        $form->addFieldButton("autorise",false,function(&$bt,$instance,$index) use($optionsSelect){
            foreach($optionsSelect as &$optn)
            {
                if(array_search($instance->getId(),$optionsSelect)!==false){
                    $bt->addClass("disabled");
                }elseif(array_search($instance->getId(),$optionsSelect)==false){
                    $bt->addClass("_toCheck");
                }
            }
        });
            $form->addFieldButton("interdit",false,function(&$bt,$instance,$index) use($optionsSelect){
                foreach($optionsSelect as &$optn)
                {
                    if(array_search($instance->getId(),$optionsSelect)!==false){
                        $bt->addClass("_toUncheck");
                    }elseif(array_search($instance->getId(),$optionsSelect)==false){
                        $bt->addClass("disabled");
                    }
                }
            });
                
        $this->jquery->getOnClick("._toUncheck", "AdminSiteController/interdireOptnSite","#divSite",["attr"=>"data-ajax"]);
        $this->jquery->getOnClick("._toCheck", "AdminSiteController/autoriserOptnSite","#divSite",["attr"=>"data-ajax"]);
        ////////////////////////
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function interdireOptnSite(){
        // je récupère l'id du site que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        // je modifie les options du site:
        $siteOptions = explode(",",$site->getOptions());
        
        // variable utile pour la conditions qu suit
        $newOptn = "";
        $i = 0;
        
        // conditon recréant les options attribués avec la nouvelle interdiction: 
        while($i<count($siteOptions))
        {
            // si les options du site ne comporte pas l'option selectioné: 
            if($siteOptions[$i]!=$recupId[2])
            {
                // si on est à la première option on n'afficha pas la virgule
                if($i == 0)
                {
                    $newOptn = $siteOptions[$i];
                }
                $newOptn = $newOptn . "," . $siteOptions[$i];
            }
            $i = $i + 1;
        }
        
        // on modifie le site: 
        $site->setOptions($newOptn);
        // on modifie aussi la variable de session
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    
    public function autoriserOptnSite(){
        // je récupère l'id du site que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        // je modifie les options du site:
        if(!empty($site->getOptions()))
        {
            $newOptn = $site->getOptions(). "," . $recupId[2];
        }else{
            $newOptn = $recupId[2];
            }
        
        $site->setOptions($newOptn);
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        // j'envoi la requete qui modifie les options selectionés pour mon site
        //RequestUtils::setValuesToObject($site);
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    
    // -----------Methode Options Etablissement-----------
    /*
    public function optionsEtablissement(){
        $semantic=$this->jquery->semantic();
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsE", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getEtablissement()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getEtablissement()->getOptions());
        
        $form->addFieldButton("autorise",false,function(&$bt,$instance,$index) use($optionsSelect){
            foreach($optionsSelect as &$optn)
            {
                if(array_search($instance->getId(),$optionsSelect)!==false){
                    $bt->addClass("disabled");
                }elseif(array_search($instance->getId(),$optionsSelect)==false){
                    $bt->addClass("_toCheck");
                }
            }
        });
            $form->addFieldButton("interdit",false,function(&$bt,$instance,$index) use($optionsSelect){
                foreach($optionsSelect as &$optn)
                {
                    if(array_search($instance->getId(),$optionsSelect)!==false){
                        $bt->addClass("_toUncheck");
                    }elseif(array_search($instance->getId(),$optionsSelect)==false){
                        $bt->addClass("disabled");
                    }
                }
            });
                
                $this->jquery->getOnClick("._toUncheck", "AdminSiteController/interdireOptnEtablissement","#divSite",["attr"=>"data-ajax"]);
                $this->jquery->getOnClick("._toCheck", "AdminSiteController/autoriserOptnEtablissement","#divSite",["attr"=>"data-ajax"]);
                ////////////////////////
                echo $form->compile($this->jquery);
                echo $this->jquery->compile();
    }
    public function interdireOptnEtablissement(){
        // je récupère l'id du site que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        // je modifie les options du site:
        $siteOptions = explode(",",$site->getOptions());
        
        // variable utile pour la conditions qu suit
        $newOptn = "";
        $i = 0;
        
        // conditon recréant les options attribués avec la nouvelle interdiction:
        while($i<count($siteOptions)-1)
        {
            // si les options du site ne comporte pas l'option selectioné:
            if($siteOptions[$i]!=$recupId[2])
            {
                // si on est à la première option on n'afficha pas la virgule
                if($i == 0)
                {
                    $newOptn = $siteOptions[$i];
                }
                $newOptn = $newOptn . "," . $siteOptions[$i];
            }
            $i = $i + 1;
        }
        
        // on modifie le site:
        $site->setOptions($newOptn);
        // on modifie aussi la variable de session
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    
    public function autoriserOptnEtablissement(){
        // je récupère l'id du site que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        // je modifie les options du site:
        if(!empty($site->getOptions()))
        {
            $newOptn = $site->getOptions(). "," . $recupId[2];
        }else{
            $newOptn = $recupId[2];
        }
        
        $site->setOptions($newOptn);
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        // j'envoi la requete qui modifie les options selectionés pour mon site
        //RequestUtils::setValuesToObject($site);
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    */
    // ----------- les actions liés au site -------
    
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
    
    // module de la page
    public function moteur(){
        $semantic=$this->jquery->semantic();
        
        // ---------- LISTE DES MOTEURS ------------
        
        // on cherche le moteur que l'on a selectionnée afin de l'indiqué:
        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId());
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
        $table->setUrls(['','AdminSiteController/editMoteur/','AdminSiteController/deleteMoteur/']);
        $table->setTargetSelector("#divSite");
        // ----------- SELECTIONNER UN MOTEUR POUR NOTRE SITE -----------
        
        // on différencie le moteur déjà selectionné des autres
        $table->addFieldButton("Selectionner",false,function(&$bt,$instance) use($moteurSelected){
            if($instance->getId()==$moteurSelected->getId()){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
            $this->jquery->getOnClick("._toSelect", "AdminSiteController/selectionner","#divSite",["attr"=>"data-ajax"]);
            
            // ---------- AJOUTER MOTEUR  ------------
            
            $btAdd=$semantic->htmlButton('btAdd','ajouter un moteur');
            $btAdd->getOnClick("AdminSiteController/newMoteur","#divSite");
            
            // ------------
            
            echo $table->compile($this->jquery);
            echo $btAdd->compile($this->jquery);
            
            echo $this->jquery->compile();
    }
    
    // ----------- les actioins liés aux moteurs -------
    
    // Selection du moteur pour notre site
    public function selectionner()
    {
        // je récupère l'id du moteur que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le site que j'administre:
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        // je recupère le moteur que je souhaite selectionner:
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        // je modifie le moteur du site:
        $site->setMoteur($moteur);
        // j'envoi la requete qui modifie le moteur selectioné pour mon site
        //RequestUtils::setValuesToObject($site);
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","moteur");
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
        
        $semantic=$this->jquery->semantic();
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmMoteur' au paramètre instance de moteur
        $form=$semantic->dataForm("frmMoteur",$moteur);
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["id","nom","code","submit"]);
        $form->setCaptions(["id","Nom","Code","Valider"]);
        $form->fieldAsHidden("id");
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divSite");
        
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
        /*
         $etablissement = DAO::getAll("models\Etablissement", $moteur);;
         $site = DAO::getAll("models\Site", $moteur);
         $utilisateur = DAO::getAll("models\Utilisateur", $moteur);
         
         $moteurVide= new Moteur();
         
         RequestUtils::setValuesToObject($etablissement,$moteurVide);
         RequestUtils::setValuesToObject($site,$moteurVide);
         RequestUtils::setValuesToObject($utilisateur,$moteurVide);
         
         if(DAO::update($etablissement) && DAO::update($site) && DAO::update($utilisateur)){
         echo "Le moteur ".$idMoteur." n'est plus accocié aux établissement, sites et utilisateurs";
         }
         */
        if(DAO::remove($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été supprimé.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
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