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
    
    // Tableau de bord de la page
    public function index(){
        $semantic=$this->jquery->semantic();
        
        if(!isset($_SESSION["user"])) {
            $bt=$semantic->htmlButton("bts","Connexion");
            $bt->postOnClick("UserController/connexion/","{action:'UserController/submit'}","#divUsers",["attr"=>""]);
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled("Google");
            
            $frm->setProperty("action","https://www.google.fr/search?q=");
            $frm->setProperty("method","get");
            $frm->setProperty("target","_new");
            $bt=$input->addAction("Rechercher");
            echo $frm;
        } else {
            $bts=$semantic->htmlButtonGroups("bts",["Liste des liens web", "Préférences", "Choix du moteur", "Recherche", "Éléments masqués", "Déconnexion"]);
            $bts->setPropertyValues("data-ajax", ["listeFavoris/", "preferences/", "moteur/", "afficheMoteur/", "elementsMasques/", "deconnexion/UserController/index"]);
            $bts->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
        }
        
        $this->jquery->compile($this->view);
        $this->loadView("Utilisateur\index.html");
    }
    
    public function elementsMasques() {
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage français à la 'semantic'
        $semantic->setLanguage("fr");
        
        $btt1=$semantic->htmlButton("btt1","Activer l'image de fond");
        $btt1->onClick("$('body').css('background-image', 'url(". $_SESSION["user"]->getFondEcran() .")');");
        
        $btt2=$semantic->htmlButton("btt2","Désactiver l'image de fond");
        $btt2->onClick("$('body').css('background-image', 'none');");
        
        $btt3=$semantic->htmlButton("btt3","Activer la couleur de fond");
        $btt3->onClick("$('body').css('background-color', 'red');");
        
        $btt4=$semantic->htmlButton("btt4","Désactiver la couleur de fond");
        $btt4->onClick("$('body').css('background-color', 'white');");
        
        echo $btt1->compile($this->jquery);
        echo $btt2->compile($this->jquery);
        echo $btt3->compile($this->jquery);
        echo $btt4->compile($this->jquery);
        
        echo $this->jquery->compile();
    }
    
    public function afficheMoteur() {
        $moteur=DAO::getOne("models\Utilisateur","idMoteur=".$_SESSION["user"]->getMoteur());
        //var_dump($moteur->getMoteur()->getNom());
        
        $frm=$this->jquery->semantic()->htmlForm("frm-search");
        $input=$frm->addInput("q");
        $input->labeled($moteur->getMoteur()->getNom());
        
        $frm->setProperty("action",$moteur->getMoteur()->getCode());
        $frm->setProperty("method","get");
        $frm->setProperty("target","_new");
        $bt=$input->addAction("Rechercher");
        echo $frm;
    }
    
    private function _listeFavoris() {
        $semantic=$this->jquery->semantic();
        
        $liens=DAO::getAll("models\Lienweb","idUtilisateur=".$_SESSION["user"]->getId()." ORDER BY ordre ASC");
        
        $table=$semantic->dataTable("tblLiens", "models\Utilisateur", $liens);
        $table->setIdentifierFunction("getId");
        $table->setFields(["libelle","url", "ordre"]);
        $table->setCaptions(["Nom du lien","URL", "Ordre", "Action"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
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
        
        // Chargement de la page HTML 'index.html' de la vue
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
        $form->setFields(["login", "password\n", "elementsMasques", "fondEcran", "couleur\n", "ordre", "submit"]);
        
        // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
        $form->setCaptions(["Login","Mot de passe","Éléments masqués","Fond d'écran","Couleur", "Ordre","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit", "green", $action, "#divUsers");
        
        // Chargement de la page HTML 'index.html' de la vue
        $this->loadView("Utilisateur\index.html");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    
    public function preferences(){
        $id=$_SESSION["user"]->getId();
        $user=DAO::getOne("models\Utilisateur", $id);
        $this->_preferences($user, "UserController/updateUser/".$id."/Utilisateur", $user->getLogin(), $user->getPassword());
    }
    
    /*public function editUser($id){
        $user=DAO::getOne("models\Utilisateur", $id);
        $this->_preferences($user,"UserController/updateUser/".$id."/Utilisateur",$user->getLogin(),$user->getPassword());
    }*/
    
    public function updateUser($id){
        $user=DAO::getOne("models\Utilisateur", $id);
        RequestUtils::setValuesToObject($user,$_POST);
        if(DAO::update($user)){
            echo "L'utilisateur ".$user->getLogin()." a été modifié.";
            $_SESSION["user"] = $user;
            echo $this->jquery->compile($this->view);
            //var_dump($_SESSION["user"]);
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
        //var_dump($id);
        // Variable 'site' récupérant toutes les données d'un site selon son id et le modèle 'Site'
        $liens=DAO::getOne("models\Lienweb", "id=".$id);
        
        // Instanciation du modèle 'Site' sur le site récupéré et exécution de la requête de suppression
        $liens instanceof models\Lienweb && DAO::remove($liens);
        
        // Retour sur la page d'affichage de tous les sites
        $this->forward("controllers\UserController","listeFavoris");
    }
    
    public function editLink($id){
        $liens=DAO::getOne("models\Lienweb", $id);
        $this->_formFavoris($liens,"UserController/updateLink/".$id."/Lienweb",$liens->getLibelle(),$liens->getUrl(),$liens->getOrdre());
    }
    
    public function updateLink($id){
        $liens=DAO::getOne("models\Lienweb", $id);
        RequestUtils::setValuesToObject($liens,$_POST);
        if(DAO::update($liens)){
            echo "Le lien ".$liens->getLibelle()." a été modifié.";
        }
    }
    
    // module de la page
    public function moteur(){
        $semantic=$this->jquery->semantic();
        
        // ---------- LISTE DES MOTEURS ------------
        
        // recupération du moteur selectionnée:
        $moteurSelected=$_SESSION['user']->getMoteur();
        // recuperation de tout les moteurs:
        $moteurs=DAO::getAll("models\Moteur");
        // on met ces moteurs dans un tableau
        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs);
        // identifiant du moteur en identifieur
        $table->setIdentifierFunction("getId");
        
        $table->setFields(["id", "nom", "code"]);
        $table->setCaptions(["id", "nom", "code du moteur", "Selectioner"]);
        
        $table->setTargetSelector("#divUsers");
        // ----------- SELECTIONNER UN MOTEUR POUR NOTRE SITE -----------
        
        // on différencie le moteur déjà selectionné des autres
        $table->addFieldButton("Sélectionner",false,function(&$bt,$instance) use($moteurSelected){
            if($instance->getId()==$moteurSelected->getId()){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
            $this->jquery->getOnClick("._toSelect", "UserController/selectionner","#divUsers",["attr"=>"data-ajax"]);
            
            echo $table->compile($this->jquery);
            echo $this->jquery->compile();
    }
    
    // ----------- les actioins liés aux moteurs -------
    
    // Selection du moteur pour notre site
    public function selectionner()
    {
        // je récupère l'id du moteur que l'on veut selectionner avec un explode de l'url où il s'y trouve en tant que paramètre:
        $recupId = explode('/', $_GET['c']);
        // je recupère le moteur que je souhaite selectionner:
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        // je modifie le moteur du site:
        $_SESSION["user"]->setMoteur($moteur);
        // j'envoi la requete qui modifie le moteur selectioné pour mon site
        //RequestUtils::setValuesToObject($site);
        $_SESSION["user"] instanceof models\Utilisateur && DAO::update($_SESSION["user"]);
        $this->forward("controllers\UserController","moteur");
    }
}