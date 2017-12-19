<?php
namespace controllers;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Site;

/**
 * Controller SiteController
 * @property JsUtils $jquery
 **/

// D�claration de la classe SiteController h�ritant de ControllerBase
class SiteController extends ControllerBase
{
    /**
     * <h1>Description de la m�thode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc
     * Pour plus de d�tails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     *
     * Initialise l'utilisateur connect� ainsi que son fond d'�cran (dont l'URL est enregistr� dans la BDD).
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
     * Affiche le menu de la page si un utilisateur normal est connect�.
     * 
     * @see \micro\controllers\Controller::index()
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

            $menu->addItem("<h4 class='ui header'>D�connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "deconnexion/SiteController/index"]);
            
            $mess=$semantic->htmlMessage("mess3","Vous �tes connect� mais vous n'avez pas les droits, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Attention !");
            $mess->setDismissable();
        }
        else{
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());

            $menu->addItem("<h4 class='ui header'>Liste des sites</h4>");
            $menu->addItem("<h4 class='ui header'>Ajouter un site</h4>");
            $menu->addItem("<h4 class='ui header'>D�connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "all/","addSite/", "deconnexion/"]);
            
            $mess=$semantic->htmlMessage("mess3","Vous �tes connect�, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Bienvenue !");
            $mess->setDismissable();
        }
        $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
        $menu->setVertical();
        
        // G�n�ration du JavaScript/JQuery en tant que variable � l'int�rieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation � la vue d'URL 'sites\index.html'
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
        // Variable 'sites' r�cup�rant toutes les donn�es de la table 'Site' � partir du mod�le d'URL 'models\Site'
        // sous forme de tableau
        $sites=DAO::getAll("models\Site");
        
        // D�claration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Variable 'table' affectant la 'semantic' locale aux tableaux de donn�es 'table' de la table 'Site' avec :
        // 1) Identifiant : 'tblSites'
        // 2) Mod�le : 'models\Site'
        // 3) Tableau de donn�es : 'sites'
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        
        // Envoi des champs de chaque �l�ment de la table 'Site' � 'table'
        $table->setFields(["id", "nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        
        // Envoi des titres � chaque champ des �l�ments de la table 'Site' � 'table'
        $table->setCaptions(["id","Nom","Latitude","Longitude","Ecart","Fond d'�cran","Couleur", "Ordre", "Options", "Actions"]);
        
        // Ajout d'un bouton d'�dition et d'un bouton de suppression � chaque ligne renvoy� de 'table'
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        
        // Affectation d'un URL � chaque bouton pr�c�dent :
        // 1) edit=>'SiteController/edit'
        // 2) delete=>'SiteController/delete'
        $table->setUrls(["","SiteController/edit","SiteController/delete"]);
        
        // Envoi du tableau de donn�es � l'int�rieur de la div '#divSites' dans 'index.html'
        $table->setTargetSelector("#divSites");
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }

    /**
     * Ex�cute, compile et affiche le contenu de la m�thode _all en publique dans la vue 'index.html'.
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    public function all() {
        // Affectation de _all � la classe actuelle de variable 'this'
        $this->_all();
        
        // G�n�ration du JavaScript/JQuery en tant que variable � l'int�rieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation � la vue d'URL 'sites\index.html'
        $this->loadView("sites\index.html");
    }
    
    /**
     * Ajoute un nouveau site en prenant en compte la m�thode _form
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function addSite(){
        $this->_form(new Site(),"SiteController/newSite/",49.201491,-0.380734);
    }
    
    /**
     * Ajoute des donn�es des sites �crites dans le formulaire.
     * 
     * @param array site : Donn�es d'un site sous forme de tableau
     * @param string action : Action de redirection
     * @param float lat : Latitude du site
     * @param float long : Longitude du site
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    private function _form($site, $action, $lat, $long){
        // D�claration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage fran�ais � la 'semantic'
        $semantic->setLanguage("fr");
        
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au param�tre '$site'
        $form=$semantic->dataForm("frmSite", $site);
        
        // Envoi des param�tres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque �l�ment de la table 'Site' � 'form'
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran","couleur\n","ordre","options","submit"]);
        
        // Envoi des titres � chaque champ des �l�ments de la table 'Site' � 'table'
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'�cran","Couleur", "Ordre", "Options","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' r�cup�rant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divSites");
        
        // Chargement de la page HTML 'index.html' de la vue 'sites' avec la g�n�ration de la carte Google
        // via la fonction priv�e '_generateMap'
        $this->loadView("sites\index.html",["jsMap"=>$this->_generateMap($lat,$long)]);
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
     * Ex�cute la requ�te d'ajout d'un nouveau site.
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function newSite(){
        
        // Variable 'site' r�cup�rant toutes les donn�es d'un nouveau site
        $site=new Site();
        
        // Ex�cution de la requ�te d'insertion de toutes les valeurs entr�es dans le formulaire d'ajout d'un nouveau site
        RequestUtils::setValuesToObject($site,$_POST);
        
        // Condition si l'insertion d'un nouveau site est ex�cut�e
        if(DAO::insert($site)){
            // Affichage du message suivant
            echo "Le site ".$site->getNom()." a �t� ajout�.";
        }
    }
    
    /**
     * Ex�cute la requ�te de suppression d'un nouveau site.
     * 
     * @param int id : Identifiant du site
     * 
     * @author Matteo ETTORI / Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function delete($id){
        // Variable 'site' r�cup�rant toutes les donn�es d'un site selon son id et le mod�le 'Site'
        $site=DAO::getOne("models\Site", $id);
        
        // Instanciation du mod�le 'Site' sur le site r�cup�r� et ex�cution de la requ�te de suppression
        $site instanceof models\Site && DAO::remove($site);
        
        // Retour sur la page d'affichage de tous les sites
        $this->forward("controllers\SiteController","all");
    }
    
    /**
     * R�cup�re le formulaire de pr�f�rences du site avec renvoi vers la fonction 'update'.
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
     * Ex�cute la requ�te de modification d'un site.
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
            echo "Le site ".$site->getNom()." a �t� modifi�.";
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
            // D�claration de la carte Google Maps
            var map={};
            
            // Fonction d'initialisation de la carte, de ses �l�ments et de ses �v�nements
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
				
                // Ajout d'un �v�nement lorsque l'on clique sur la carte
                map.addListener('click',function(event){
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' � la valeur de la latitude de l'�v�nement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' � la valeur de la longitude de l'�v�nement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();

                    // Affectation de la valeur de la div d'id 'frmSite-latitude' � la valeur de la longitude de l'�v�nement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();
                })
                
                // Ajout d'un �v�nement lorsque l'on change la latitude de la div d'id 'frmSite-latitude'
                frmSite-latitude.addListener('change', function(event){
                    // Affectation de la valeur de la cible de l'�v�nement � la valeur de la latitude de la carte
                    event.target.value=map.latLng.lat();
                })
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }
}