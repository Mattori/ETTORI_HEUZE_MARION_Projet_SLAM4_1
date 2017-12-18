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
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>Connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "connexion/SiteController/submit"]);
            $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            $menu->setVertical();
            
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
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());
            
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>D�connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "deconnexion/SiteController/index"]);
            $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            $menu->setVertical();
            
            $mess=$semantic->htmlMessage("mess3","Vous �tes connect� mais vous n'avez pas les droits, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Attention !");
            $mess->setDismissable();
        }
        else{
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());
            
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>Liste des sites</h4>");
            $menu->addItem("<h4 class='ui header'>Ajouter un site</h4>");
            $menu->addItem("<h4 class='ui header'>D�connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "all/","addSite/", "deconnexion/"]);
            $menu->getOnClick("SiteController/","#divSites",["attr"=>"data-ajax"]);
            $menu->setVertical();
            
            $mess=$semantic->htmlMessage("mess3","Vous �tes connect�, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Bienvenue !");
            $mess->setDismissable();
        }
        // G�n�ration du JavaScript/JQuery en tant que variable à l'int�rieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation � la vue d'URL 'sites\index.html'
        $this->loadView("sites\index.html");
        //$this->loadView("sites\index.html",["jsMap"=>$this->_generateMap(49.201491, -0.380734)]);
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
        //$table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        
        // Envoi des champs de chaque élément de la table 'Site' à 'table'
        $table->setFields(["id", "nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $table->setCaptions(["id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]);
        
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