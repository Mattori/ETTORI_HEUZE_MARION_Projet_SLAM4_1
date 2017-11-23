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

// Déclaration de la classe SiteController héritant de ControllerBase
class SiteController extends ControllerBase
{
    public function initialize(){
        parent::initialize();
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            //echo $user->getLogin();
        }
    }
    
    
    // Fonction publique permettant de déterminer les éléments et les évènements de la page 'index.html'
    public function index(){
        $semantic=$this->jquery->semantic();
        //echo "ici, on administre le site qui a pour identifiant: ".$_SESSION["user"]->getSite()->getId();
        if(!isset($_SESSION["user"])) {
            $bts=$semantic->htmlButtonGroups("bts",["Connexion"]);
            $bts->setPropertyValues("data-ajax", ["connexion/"]);
            $bts->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            
            $this->jquery->compile($this->view);
            $this->loadView("sites\index.html");
        }
        elseif($_SESSION["user"]->getStatut()->getId() < 3)
        {
            echo "Vous êtes connectés mais vous n'avez pas les droits.";
            $bts=$semantic->htmlButtonGroups("bts",["Deconnexion"]);
            $bts->setPropertyValues("data-ajax", ["deconnexion/"]);
            $bts->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            
            $this->jquery->compile($this->view);
            $this->loadView("sites\index.html");
        }
        else{
            // Variable 'semantic' déclarant une nouvelle Semantic-UI
            $semantic=$this->jquery->semantic();
            
            // Variable 'bts' affectant la 'semantic' locale a un groupe de boutons
            $bts=$semantic->htmlButtonGroups("bts",["Liste des sites","Ajouter un site", "Déconnexion"]);
            
            // Attribution des propriétés 'all' et 'addSite' respectivement aux boutons de 'bts' :
            // 1) 'Liste des sites' => 'all/'
            // 2) 'Ajouter un site' => 'addSite/'
            $bts->setPropertyValues("data-ajax", ["all/","addSite/", "deconnexion/"]);
            
            // Récupération du clic fait dans 'SiteController' en renvoyant la réponse dans la div '#divSites'
            $bts->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            //$this->jquery->exec("initMap();",true);
            
            // Génération du JavaScript/JQuery en tant que variable à l'intérieur de la vue
            $this->jquery->compile($this->view);
            
            // Affiliation à la vue d'URL 'sites\index.html'
            $this->loadView("sites\index.html");
            //$this->loadView("sites\index.html",["jsMap"=>$this->_generateMap(49.201491, -0.380734)]);
        }
    }

    
    // Fonction privée permettant d'afficher le contenu de la table 'Site' de la BDD 'homepage'
    private function _all(){
        // Variable 'sites' récupérant toutes les données de la table 'Site' à partir du modèle d'URL 'models\Site'
        // sous forme de tableau
        $sites=DAO::getAll("models\Site");
        
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Variable 'table' affectant la 'semantic' locale aux tableaux de données 'table' de la table 'Site' avec :
        // 1) Identifiant : 'tblSites'
        // 2) Modèle : 'models\Site'
        // 3) Tableau de données : 'sites'
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        
        // Envoi de l'identifiant de la fonction récupérant chaque id sous forme d'objet au tableau de données 'table'
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        
        // Envoi des champs de chaque élément de la table 'Site' à 'table'
        $table->setFields(["id","nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $table->setCaptions(["Id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]);
        
        // Ajout d'un bouton d'édition et d'un bouton de suppression à chaque ligne renvoyé de 'table'
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        
        // Affectation d'un URL à chaque bouton précédent :
        // 1) edit=>'SiteController/edit'
        // 2) delete=>'SiteController/delete'
        $table->setUrls(["","SiteController/edit","SiteController/delete"]);
        
        // Envoi du tableau de données à l'intérieur de la div '#divSites' dans 'index.html'
        $table->setTargetSelector("#divSites");
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }

    
    // Fonction publique permettant l'exécution, la compilation et l'affichage de la fonction _all en publique
    public function all() {
        // Affectation de _all à la classe actuelle de variable 'this'
        $this->_all();
        
        // Génération du JavaScript/JQuery en tant que variable à l'intérieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation à la vue d'URL 'sites\index.html'
        $this->loadView("sites\index.html");
    }
    
    
    // Fonction publique permettant de prendre en compte la fonction _form
    public function addSite(){
        $this->_form(new Site(),"SiteController/newSite/",49.201491,-0.380734);
    }
    
    // Fonction privée permettant l'ajout des données des sites écrites dans le formulaire
    private function _form($site, $action,$lat,$long){
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
        /*$this->jquery->click("#map","
         console.log(event);
         var latlong = event.latLng;
         var lat = latlong.lat();
         var long = latlong.lng();
         alert(lat+' - '+lng);
         ");*/
        //$this->jquery->change("[name=latitude]","alert('lat change : '+event.target.value);");
        
        // Chargement de la page HTML 'index.html' de la vue 'sites' avec la génération de la carte Google
        // via la fonction privée '_generateMap'
        $this->loadView("sites\index.html",["jsMap"=>$this->_generateMap($lat,$long)]);
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    // Fonction publique permettant l'exécution de la requête d'ajout d'un nouveau site
    public function newSite(){
        
        // Variable 'site' récupérant toutes les données d'un nouveau site
        $site=new Site();
        
        // Exécution de la requête d'insertion de toutes les valeurs entrées dans le formulaire d'ajout d'un nouveau site
        RequestUtils::setValuesToObject($site,$_POST);
        
        // Condition si l'insertion d'un nouveau site est exécutée
        if(DAO::insert($site)){
            // Affichage du message suivant
            echo "Le site ".$site->getNom()." a été ajouté.";
        }
    }
    
    // Fonction publique permettant l'exécution de la requête de suppression d'un nouveau site
    public function delete($id){
        //  if(RequestUtils::isPost())
        //{
            //echo " - ".$id." - ";
            
            // Variable 'site' récupérant toutes les données d'un site selon son id et le modèle 'Site'
            $site=DAO::getOne("models\Site", $id);
            
            // Instanciation du modèle 'Site' sur le site récupéré et exécution de la requête de suppression
            $site instanceof models\Site && DAO::remove($site);
            
            // Retour sur la page d'affichage de tous les sites
            $this->forward("controllers\SiteController","all");
            //echo "le site {$site} est supprimé";
            /*if($site instanceof models\Site && DAO::remove($site))
             {
             echo "le site {$site} est supprimé";
             }else{ echo "impossible a supp";}*/
        //}
        //else{echo "accés interdit";}
    }
    
    // Fonction publique permettant 
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
    
    public function connexion () {
        $frm=$this->jquery->semantic()->defaultLogin("connect");
        $frm->fieldAsSubmit("submit","green","UserController/submit","#div-submit");
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
        $id=RequestUtils::get('id');
        $user=DAO::getOne("models\Utilisateur", "login='".$_POST["login"]."'");
        if(isset($user)){
            $_SESSION["user"] = $user;
            $this->jquery->get("UserController/index","body");
            echo $this->jquery->compile($this->view);
        }
    }
    
    public function testCo(){
        var_dump($_SESSION["user"]);
    }
    
    public function deconnexion() {
        session_unset();
        session_destroy();
        $this->jquery->get("UserController/index","body");
        echo $this->jquery->compile();
    }
    
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
					radius: 50
				}
                // Affectation du cercle
				var cercle = new google.maps.Circle(optionsCercle);
                
                // Ajout d'un évènement lorsque l'on clique sur la carte
                map.addListener('click',function(event){
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à la valeur de la latitude de l'évènement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();

                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à la valeur de la longitude de l'évènement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();

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