<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;

use models;
use models\Lienweb;


/**
 * Controller UserController
 * @property JsUtils $jquery
 **/

class UserController extends ControllerBase
{
    public function initialize(){
        parent::initialize();
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            //echo $user->getLogin();
        }
    }
    
    // Tableau de bord de la page
    public function index(){
        $semantic=$this->jquery->semantic();
        if(!isset($_SESSION["user"])) {
            $bts=$semantic->htmlButtonGroups("bts",["Connexion"]);
            $bts->setPropertyValues("data-ajax", ["connexion/"]);
            $bts->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            //$bts->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            
            $this->jquery->compile($this->view);
            $this->loadView("Utilisateur\index.html");
        } else {
            $bts=$semantic->htmlButtonGroups("bts",["Liste des liens web", "Préférences", "Choix du site", "Déconnexion"]);
            $bts->setPropertyValues("data-ajax", ["listeFavoris/", "preferences/", "choixSite/", "deconnexion/"]);
            $bts->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            //$bts->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            
            $this->jquery->compile($this->view);
            $this->loadView("Utilisateur\index.html");
        }
    }
    
    private function _listeFavoris() {
        $semantic=$this->jquery->semantic();
        
        $liens=DAO::getAll("models\Lienweb","idUtilisateur=".$_SESSION["user"]->getId());
        
        $table=$semantic->dataTable("tblLiens", "models\Utilisateur", $liens);
        $table->setIdentifierFunction("getId");
        $table->setFields(["id","libelle","url"]);
        $table->setCaptions(["ID","Nom du lien","URL","Action"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        //$table->addFieldButton("action",false,function(&$bt,$instance){$bt->addClass("select")->addIcon("archive",true,true);});
        //$table->setTargetSelector("#divUsers");
        $table->setUrls(["","UserController/editLink","UserController/deleteLink"]);
        $table->setTargetSelector("#divUsers");
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();   
    }
    
    // Fonction publique permettant l'exécution, la compilation et l'affichage de la fonction _all en publique
    public function listeFavoris() {
        // Affectation de _all à la classe actuelle de variable 'this'
        $this->_listeFavoris();
        
        // Génération du JavaScript/JQuery en tant que variable à l'intérieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation à la vue d'URL 'sites\index.html'
        $this->loadView("Utilisateur\index.html");
    }
    
    // Fonction privée permettant l'ajout des données des sites écrites dans le formulaire
    private function _formFavoris($liens, $action, $libelle, $url, $ordre){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
        $form=$semantic->dataForm("frmLink", $liens);
        
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setFields(["libelle","url","ordre","submit"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Libelle","URL","Ordre","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divUsers");
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
        $this->loadView("Utilisateur\index.html");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    
    
    // Fonction privée permettant l'ajout des données des sites écrites dans le formulaire
    private function _preferences($user, $action, $login, $password){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
        $form=$semantic->dataForm("frmUser", $user);
        
        // Envoi des paramètres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque élément de la table 'Site' à 'form'
        $form->setFields(["login", "password\n", "elementsMasques", "fondEcran", "couleur\n", "ordre", "options", "submit"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Login","Mot de passe","Éléments masqués","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit", "green", $action, "#divUsers");
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
        $this->loadView("Utilisateur\index.html");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    // Fonction publique permettant l'exécution, la compilation et l'affichage de la fonction _all en publique
    /*public function formUser() {
        // Affectation de _all à la classe actuelle de variable 'this'
        $this->_formUser();
        
        // Génération du JavaScript/JQuery en tant que variable à l'intérieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation à la vue d'URL 'sites\index.html'
        $this->loadView("Utilisateur\index.html");
    }*/
    
    
    public function preferences(){
        //if($site=$this->_getSiteInGet()){
        //$_SESSION["user"]=DAO::getOne("models\Utilisateur",1);
        $this->_preferences($_SESSION["user"], "UserController/updateUser/", $_SESSION["user"]->getLogin(), $_SESSION["user"]->getPassword());
        //$site instanceof models\Site && DAO::update($site);
        //$this->jquery->postFormOnClick("#btValider","SiteController/update", "frmEdit","#divSites");
        //$this->jquery->compile($this->view);
        
        //        $this->loadView("SiteController/edit.html");
        //}else{echo 'accés interdit';}
    }
    
    public function editUser($id){
        //if($site=$this->_getSiteInGet()){
        $user=DAO::getOne("models\Utilisateur", $id);
        $this->_preferences($liens,"UserController/updateUser/".$id."/Utilisateur",$user->getLogin(),$liens->getPassword());
    }
    
    public function updateUser($id){
        $user=DAO::getOne("models\Utilisateur", $id);
        RequestUtils::setValuesToObject($user,$_POST);
        if(DAO::update($user)){
            echo "L'utilisateur ".$user->getLogin()." a été modifié.";
        }
    }
    
    
    
    
    // Fonction publique permettant l'exécution de la requête d'ajout d'un nouveau site
    public function newLink(){
        
        // Variable 'site' récupérant toutes les données d'un nouveau site
        $lien=new Lienweb();
        
        // Exécution de la requête d'insertion de toutes les valeurs entrées dans le formulaire d'ajout d'un nouveau site
        RequestUtils::setValuesToObject($lien,$_POST);
        
        // Condition si l'insertion d'un nouveau site est exécutée
        if(DAO::insert($lien)){
            // Affichage du message suivant
            echo "Le lien ".$user->getNom()." a été ajouté.";
        }
    }
    
    // Fonction publique permettant l'exécution de la requête de suppression d'un nouveau site
    public function deleteLink($id){
        //var_dump($_SESSION["user"]);
        var_dump($id);
        // Variable 'site' récupérant toutes les données d'un site selon son id et le modèle 'Site'
        $liens=DAO::getOne("models\Lienweb", "id=".$id);
        
        // Instanciation du modèle 'Site' sur le site récupéré et exécution de la requête de suppression
        $liens instanceof models\Lienweb && DAO::remove($liens);
        
        // Retour sur la page d'affichage de tous les sites
        $this->forward("controllers\UserController","listeFavoris");
    }
    
    public function editLink($id){
        //if($site=$this->_getSiteInGet()){
        $liens=DAO::getOne("models\Lienweb", $id);
        $this->_formFavoris($liens,"UserController/updateLink/".$id."/Lienweb",$liens->getLibelle(),$liens->getUrl(),$liens->getOrdre());
        //$site instanceof models\Site && DAO::update($site);
        //$this->jquery->postFormOnClick("#btValider","SiteController/update", "frmEdit","#divSites");
        //$this->jquery->compile($this->view);
        
        //        $this->loadView("SiteController/edit.html");
        //}else{echo 'accés interdit';}
    }
    
    public function updateLink($id){
        $liens=DAO::getOne("models\Lienweb", $id);
        RequestUtils::setValuesToObject($liens,$_POST);
        if(DAO::update($liens)){
            echo "Le lien ".$liens->getLibelle()." a été modifié.";
        }
    }
    
    public function choixSite() {
        
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
}