<?php
namespace controllers;
use Ubiquity\orm\DAO;
use Ubiquity\utils\RequestUtils;
use models;
use models\Site;

/**
 * Controller SiteController
 * @property JsUtils $jquery
 **/

// Déclaration de la classe SiteController héritant de ControllerBase
class SiteController extends ControllerBase
{
    /**
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     *
     * Initialise l'utilisateur connecté ainsi que son fond d'écran (dont l'URL est enregistré dans la BDD).
     *
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     * @see \controllers\ControllerBase::initialize()
     */
    public function initialize(){
        parent::initialize();
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
        }
    }
    
    /**
     * Affiche le menu de la page si un utilisateur normal est connecté.
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
            $menu->setPropertyValues("data-ajax", ["", "connexion/SiteController/submit"]);
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled("Google");
            
            $frm->setProperty("action","https://www.google.fr/search?q=");
            $frm->setProperty("method","get");
            $frm->setProperty("target","_new");
            $bt=$input->addAction("Rechercher");
            echo $frm;
        }
        elseif($_SESSION["user"]->getStatut()->getId() < 3)
        {
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());

            $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "deconnexion/SiteController/index"]);
            
            $mess=$semantic->htmlMessage("mess3","Vous étes connecté mais vous n'avez pas les droits, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Attention !");
            $mess->setDismissable();
        }
        else{
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());

            $menu->addItem("<h4 class='ui header'>Liste des sites</h4>");
            $menu->addItem("<h4 class='ui header'>Ajouter un site</h4>");
            $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "all/","addSite/", "deconnexion/"]);
            
            $mess=$semantic->htmlMessage("mess3","Vous étes connecté, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Bienvenue !");
            $mess->setDismissable();
        }
        $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
        $menu->setVertical();
        
        // Génération du JavaScript/JQuery en tant que variable é l'intérieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation é la vue d'URL 'sites\index.html'
        $this->loadView("sites\index.html");
    }
    
    /**
     * Affiche le contenu de la table 'Site' de la BDD 'homepage'.
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    private function _all(){
        $sites=DAO::getAll("models\Site"); // Variable 'sites' récupérant toutes les données de la table 'Site' é partir du modéle d'URL 'models\Site' sous forme de tableau

        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur

        $table=$semantic->dataTable("tblSites", "models\Site", $sites); // Variable 'table' affectant l'accesseur locale aux tableaux de données 'table' de la table 'Site'
        $table->setFields(["id", "nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]); // Envoi des champs de chaque élément de la table 'Site' é 'table'
        $table->setCaptions(["id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]); // Envoi des titres é chaque champ des éléments de la table 'Site' é'table'
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]); // Ajout d'un bouton d'édition et d'un bouton de suppression é chaque ligne renvoyé de 'table'
        $table->setUrls(["","SiteController/edit","SiteController/delete"]); // Affectation d'un URL échaque bouton
        $table->setTargetSelector("#divSites"); // Envoi du tableau de données é l'intérieur de la div '#divSites' dans 'index.html'
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }

    /**
     * Exécute, compile et affiche le contenu de la méthode _all en publique dans la vue 'index.html'.
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    public function all() {
        $this->_all(); // Affectation de _all é la classe actuelle de variable 'this'
        $this->jquery->compile($this->view); // Génération du JavaScript/JQuery en tant que variable é l'intérieur de la vue
        $this->loadView("sites\index.html"); // Affiliation é la vue d'URL 'sites\index.html'
    }
    
    /**
     * Ajoute un nouveau site en prenant en compte la méthode _form
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function addSite(){
        $this->_form(new Site(),"SiteController/newSite/",49.201491,-0.380734);
    }
    
    /**
     * Ajoute des données des sites écrites dans le formulaire.
     * 
     * @param array site : Données d'un site sous forme de tableau
     * @param string action : Action de redirection
     * @param float lat : Latitude du site
     * @param float long : Longitude du site
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    private function _form($site, $action, $lat, $long){
        $semantic=$this->jquery->semantic(); // Déclaration d'un nouvel accesseur
        $semantic->setLanguage("fr"); // Affectation du langage franéais él'accesseur

        $form=$semantic->dataForm("frmSite", $site); // Variable 'form' affectant l'accesseur locale au formulaire d'id 'frmSite' au paramétre '$site'
        $form->setValidationParams(["on"=>"blur", "inline"=>true]); // Envoi des paramétres du formulaire lors de sa validation
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options","submit"]); // Envoi des champs de chaque élément de la table 'Site' é'form'
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]); // Envoi des titres é chaque champ des éléments de la table 'Site' é'table'
        $form->fieldAsSubmit("submit","green",$action,"#divSites"); // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'

        $this->loadView("sites\index.html",["jsMap"=>$this->_generateMap($lat,$long)]); // Chargement de la page HTML 'index.html' de la vue 'sites' avec la génération de la carte Google via la fonction privée '_generateMap'
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
     * Exécute la requéte d'ajout d'un nouveau site.
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function newSite(){
        $site=new Site(); // Variable 'site' récupérant toutes les données d'un nouveau site
        RequestUtils::setValuesToObject($site,$_POST); // Exécution de la requéte d'insertion de toutes les valeurs entrées dans le formulaire d'ajout d'un nouveau site
        if(DAO::insert($site)){ // Condition vérifiant si l'insertion d'un nouveau site est exécutée
            echo "Le site ".$site->getNom()." a été ajouté."; // Affichage d'un message
        }
    }
    
    /**
     * Exécute la requéte de suppression d'un nouveau site.
     * 
     * @param int id : Identifiant du site
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function delete($id){
        $site=DAO::getOne("models\Site", $id); // Variable 'site' récupérant toutes les données d'un site selon son id et le modéle 'Site'
        $site instanceof models\Site && DAO::remove($site); // Instanciation du modéle 'Site' sur le site récupéré et exécution de la requéte de suppression
        $this->forward("controllers\SiteController","all"); // Retour sur la page d'affichage de tous les sites
    }
    
    /**
     * Récupére le formulaire de préférences du site avec renvoi vers la fonction 'update'.
     * 
     * @param int id : Identifiant du site
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function edit($id){
        $site=DAO::getOne("models\Site", $id);
        $this->_form($site,"SiteController/update/".$id,$site->getLatitude(),$site->getLongitude());
    }
    
    /**
     * Exécute la requéte de modification d'un site.
     * 
     * @param int id : Identifiant du site
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function update($id){
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getNom()." a été modifié.";
        }
    }
    
    /**
     * Affiche une carte Google Maps.
     * {@inheritdoc}
     * @param float lat : Latitude du site
     * @param float long : Longitude du site
     * @return string : Script de la carte Google Maps
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    private function _generateMap($lat,$long){
        return "
        <script>
            // Déclaration de la carte Google Maps
            var map={};
            
            // Fonction d'initialisation de la carte, de ses éléments et de ses événements
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
					radius: 50
				}
                // Affectation du cercle
				var cercle = new google.maps.Circle(optionsCercle);
				
                // Ajout d'un événement lorsque l'on clique sur la carte
                map.addListener('click',function(event){
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' é la valeur de la latitude de l'événement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' é la valeur de la longitude de l'événement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();

                    // Affectation de la valeur de la div d'id 'frmSite-latitude' é la valeur de la longitude de l'événement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();
                })
                
                // Ajout d'un événement lorsque l'on change la latitude de la div d'id 'frmSite-latitude'
                frmSite-latitude.addListener('change', function(event){
                    // Affectation de la valeur de la cible de l'événement é la valeur de la latitude de la carte
                    event.target.value=map.latLng.lat();
                })
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }
}