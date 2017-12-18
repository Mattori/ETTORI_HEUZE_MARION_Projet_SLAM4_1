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
    
    /**
     * Affiche le menu du controlleur si l'utilisateur est connecté
     * {@inheritdoc}
     * @see \micro\controllers\Controller::index()
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        if(!isset($_SESSION["user"])) {
            $bt=$semantic->htmlButton("bts","Connexion");
            $bt->postOnClick("AdminSiteController/connexion/","{action:'AdminSiteController/submit'}","#divSite",["attr"=>""]);
        } else {
            if($_SESSION["user"]->getStatut()->getId() >=2) {
                $bts=$semantic->htmlButtonGroups("bts",["Configuration du site","Moteur de recherche","Ordre des elements","Droits de personalisation","Deconnexion"]);
                $bts->setPropertyValues("data-ajax", ["configuration/","moteur/","ordreElement/","optionsUtilisateur/","deconnexion/AdminSiteController/index"]);
                $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
            } else {
                echo "Accès à la page d'administration du site interdit";
                $bts=$semantic->htmlButtonGroups("bts",["Deconnexion"]);
                $bts->setPropertyValues("data-ajax", ["deconnexion/AdminSiteController/index"]);
                $bts->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
            }
        }
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\index.html");
    }
    
    /**
     * Module: Affiche un formulaire de modification des paramètres du site
     * {@inheritdoc}
     */
    public function configuration() {
        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        
        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId());
        
        $form=$semantic->dataForm("frmSite", $site);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","submit"]);
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur","Valider"]);
        $form->fieldAsSubmit("submit","green fluid","AdminSiteController/editSiteConfirm","#divSite");
        $form->fieldAsElement(5,'div','class="jscolor"');
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\configuration.html",["jsMap"=>$this->_generateMap($site->getLatitude(),$site->getLongitude())]);
    }
    
    /**
     * Module: Affiche un tableau des options personnalisable par les utilisateurs avec boutons (autoriser, interdire)
     * {@inheritdoc}
     */
    public function optionsUtilisateur(){
        $semantic=$this->jquery->semantic();
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsU", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getSite()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getSite()->getOptions());
        
        // Ajout d'attribus aux boutons pour que l'on puisse identifier ce qui est déjà autorisé et interdit
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
                
                echo $form->compile($this->jquery);
                echo $this->jquery->compile();
    }
    
    /**
     * Confirme l'interdiction pour les utilisateurs de ce site de personnaliser une option (BDD)
     * {@inheritdoc}
     */
    public function interdireOptnSite(){
        $recupId = explode('/', $_GET['c']);
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        $siteOptions = explode(",",$site->getOptions());
        
        $newOptn = "";
        $i = 0;
        
        // conditon recréant les options attribués avec la nouvelle interdiction:
        while($i<count($siteOptions))
        {
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
        
        $site->setOptions($newOptn);
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    
    /**
     * Confirme l'autorisation pour les utilisateurs de ce site de personnaliser une option (BDD)
     * {@inheritdoc}
     */
    public function autoriserOptnSite(){
        $recupId = explode('/', $_GET['c']);
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        
        if(!empty($site->getOptions()))
        {
            $newOptn = $site->getOptions(). "," . $recupId[2];
        }else{
            $newOptn = $recupId[2];
        }
        
        $site->setOptions($newOptn);
        $_SESSION["user"]->getSite()->setOptions($newOptn);
        
        $site instanceof models\Site && DAO::update($site);
        $this->forward("controllers\AdminSiteController","optionsUtilisateur");
    }
    
    /**
     * Confirme l'edit du site (BDD)
     * {@inheritdoc}
     */
    public function editSiteConfirm()
    {
        $recupId = explode('/', $_GET['c']);
        $site=DAO::getOne("models\Site", $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId()));
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            $_SESSION["user"]->setSite($site);
            echo "Le site ".$site->getId()."->".$site->getNom()." a été modifié.";
            $this->forward("controllers\AdminSiteController","configuration");
        }
    }
    
    /**
     * Module: Affiche un tableau des moteurs de la BDD avec un bouton de selection pour le Site
     * {@inheritdoc}
     */
    public function moteur(){
        $semantic=$this->jquery->semantic();
        
        // Tableau des Moteurs (BDD)
        $site=DAO::getOne("models\Site",$_SESSION["user"]->getSite()->getId());
        $moteurSelected=$site->getMoteur();
        $moteurs=DAO::getAll("models\Moteur");
        
        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs);
        
        $table->setIdentifierFunction("getId");
        $table->setFields(["id","nom","code"]);
        $table->setCaptions(["id","nom","code du moteur","action","Selectioner"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(['','AdminSiteController/editMoteur/','AdminSiteController/deleteMoteur/']);
        $table->setTargetSelector("#divSite");
        // Selection du moteur pour un site
        // différenciation du moteur déjà selectionné des autres
        $table->addFieldButton("Selectionner",false,function(&$bt,$instance) use($moteurSelected){
            if($instance->getId()==$moteurSelected->getId()){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
        $this->jquery->getOnClick("._toSelect", "AdminSiteController/selectionner","#divSite",["attr"=>"data-ajax"]);
        
        // bouton ajout d'un moteur
        $btAdd=$semantic->htmlButton('btAdd','ajouter un moteur');
        $btAdd->getOnClick("AdminSiteController/newMoteur","#divSite");
        
        echo $table->compile($this->jquery);
        echo $btAdd->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
     * Confirme la selection du moteur pour un site
     * {@inheritdoc}
     */
    public function selectionner()
    {
        // récupère l'id du moteur grace à l'url
        $recupId = explode('/', $_GET['c']);
        // instanciation de notre site et du moteur selectionné
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        // MAJ dans le model
        $site->setMoteur($moteur);
        $_SESSION["user"]->getSite()->setMoteur($moteur);
        // MAJ dans la BDD
        $site instanceof models\Site && DAO::update($site);
        
        $this->forward("controllers\AdminSiteController","moteur");
    }
    
    /**
     * Appel la méthode _frmMoteur: pour créer un nouveau moteur
     * {@inheritdoc}
     */
    public function newMoteur()
    {
        $this->_frmMoteur(null,"AdminSiteController/newMoteurConfirm/","Ajouter");
    }
    
    /**
     * Appel la méthode _frmMoteur: pour modifier un moteur
     * {@inheritdoc}
     */
    public function editMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        $this->_frmMoteur($recupId[2],"AdminSiteController/editMoteurConfirm/","Modifier");
    }
    
    /**
     * Appel la méthode _frmMoteur: pour supprimer un moteur
     * {@inheritdoc}
     */
    public function deleteMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        $this->_frmMoteur($recupId[2],"AdminSiteController/deleteMoteurConfirm/","Supprimer");
    }
    
    /**
     * Affiche le formulaire d'un Moteur
     * {@inheritdoc}
     * @param int $idM identifiant du moteur
     * @param string $action url de redirection
     * @param string $actionMsg nom du bouton en lien avec l'action
     */
    private function _frmMoteur($idM,$action,$actionMsg)
    {
        if($idM != null)
        {
            $moteur=DAO::getOne("models\Moteur", $idM);
        }
        else
        {
            $moteur=new Moteur();
        }
        
        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        
        $form=$semantic->dataForm("frmMoteur",$moteur);
        
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["id","nom","code","submit"]);
        $form->setCaptions(["id","Nom","Code",$actionMsg]);
        
        $form->fieldAsHidden("id");
        $form->fieldAsSubmit("submit","green",$action,"#divSite");
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
     * Confirme la création du nouveau moteur (BDD)
     * {@inheritdoc}
     */
    public function newMoteurConfirm()
    {
        $moteur= new Moteur();
        RequestUtils::setValuesToObject($moteur,$_POST);
        
        if(DAO::insert($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été ajouté.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    /**
     * Confirme la modification du moteur (BDD)
     * {@inheritdoc}
     */
    public function editMoteurConfirm()
    {
        $moteur=DAO::getOne("models\Moteur", $_POST['id']);
        RequestUtils::setValuesToObject($moteur,$_POST);
        
        if(DAO::update($moteur)){
            $_SESSION["user"]->setMoteur($moteur);
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été modifié.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    /**
     * Confirme la suppression du moteur (BDD)
     * {@inheritdoc}
     */
    public function deleteMoteurConfirm()
    {
        $idMoteur=$_POST['id'];
        $moteur=DAO::getOne("models\Moteur", 'id='.$idMoteur);
        
        if(DAO::remove($moteur)){
            echo "Le moteur ".$moteur->getId().": ".$moteur->getNom()." a été supprimé.";
            $this->forward("controllers\AdminSiteController","moteur");
        }
    }
    
    
    /**
     * Affiche une map Google
     * {@inheritdoc}
     * @param float $lat lattitude
     * @param float $long longitude
     * @return string Script de la GoogleMap
     */
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