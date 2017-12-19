<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;

use models;
use models\Lienweb;

use Ajax\semantic\html\collections\HtmlBreadcrumb;


/**
 * Controller UserController
 * @property JsUtils $jquery
 **/

class UserController extends ControllerBase
{
    /**
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et {@literal <b> JavaDoc </b> }
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     * 
     * Initialise l'utilisateur connecté ainsi que son fond d'écran (dont l'URL est enregistré dans la BDD)
     * 
     * @see getFondEcran
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     * @see \controllers\ControllerBase::initialize()
     */
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
     * Affiche le menu de la page si un utilisateur normal est connecté
     * {@inheritDoc}
     * @see \micro\controllers\Controller::index()
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        
        if(!isset($_SESSION["user"])) {
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>Connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "connexion/UserController/submit"]);
            $menu->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            $menu->setVertical();
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled("Google");
            
            $frm->setProperty("action","https://www.google.fr/search?q=");
            $frm->setProperty("method","get");
            $frm->setProperty("target","_new");
            $bt=$input->addAction("Rechercher");
            echo $frm;
        } else {
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
            
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());
            
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>Informations</h4>");
            $menu->addItem("<h4 class='ui header'>Favoris</h4>");
            $menu->addItem("<h4 class='ui header'>Moteur</h4>");
            $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "preferences/", "listeFavoris/", "moteur/", "deconnexion/UserController/index"]);
            $menu->getOnClick("UserController/","#divUsers",["attr"=>"data-ajax"]);
            $menu->setVertical();
            
            $bc=new HtmlBreadcrumb("bc2", array("Accueil","Utilisateur"));
            $bc->setContentDivider(">");
            echo $bc;
            
            $mess=$semantic->htmlMessage("mess3","Vous àªtes désormais connecté, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
            $mess->addHeader("Bienvenue !");
            $mess->setDismissable();
        }
        
        $this->jquery->compile($this->view);
        $this->loadView("Utilisateur\index.html");
    }
    
    /**
     * Affiche/Masque les éléments du site à  partir de boutons
     */
    public function elementsMasques() {
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage franà§ais à  la 'semantic'
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
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Affiche le moteur de recherche sélectionné par l'utilisateur.
     *
     * @see moteur
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
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
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Crée une liste des liens web liés à  l'utilisateur sous forme de tableau.
     *
     * @see listeFavoris
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    private function _listeFavoris() {
        $semantic=$this->jquery->semantic();
        
        $liens=DAO::getAll("models\Lienweb","idUtilisateur=".$_SESSION["user"]->getId()." ORDER BY ordre ASC");
        
        $table=$semantic->dataTable("tblLiens", "models\Utilisateur", $liens);
        $table->setIdentifierFunction("getId");
        $table->setFields(["libelle","url"]);
        $table->setCaptions(["Nom du lien","URL", "Action"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","UserController/editLink","UserController/deleteLink"]);
        $table->setTargetSelector("#divUsers");
        
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();   
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Affiche la liste des liens web liés à  l'utilisateur sous forme de tableau.
     *
     * @see _listeFavoris
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function listeFavoris() {
        // Affectation de _all à  la classe actuelle de variable 'this'
        $this->_listeFavoris();
        
        // Génération du JavaScript/JQuery en tant que variable à  l'intérieur de la vue
        $this->jquery->compile($this->view);
        
        // Affiliation à  la vue d'URL 'sites\index.html'
        $this->loadView("Utilisateur\index.html");
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Affiche le formulaire d'ajout des données des sites.
     *
     * @see formFavoris
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    private function _formFavoris($liens, $action, $libelle, $url, $ordre){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage franà§ais à  la 'semantic'
        $semantic->setLanguage("fr");
        
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramà¨tre '$site'
        $form=$semantic->dataForm("frmLink", $liens);
        
        // Envoi des paramà¨tres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque élément de la table 'Site' à  'form'
        $form->setFields(["libelle","url","ordre","submit"]);
        
        // Envoi des titres à  chaque champ des éléments de la table 'Site' à  'table'
        $form->setCaptions(["Libelle","URL","Ordre","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit","green",$action,"#divUsers");
        
        // Chargement de la page HTML 'index.html' de la vue
        $this->loadView("Utilisateur\index.html");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Crée le formulaire des préférences utilisateurs.
     * 
     * @param user     : Utilisateur connecté
     * @param action   : Action du bouton de validation
     * @param login    : Login récupéré de l'utilisateur connecté
     * @param password : Mot de passe récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    private function _preferences($user, $action, $login, $password){
        // Déclaration d'une nouvelle Semantic-UI
        $semantic=$this->jquery->semantic();
        
        // Affectation du langage franà§ais à  la 'semantic'
        $semantic->setLanguage("fr");
        
        // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramà¨tre '$site'
        $form=$semantic->dataForm("frmUser", $user);
        
        // Envoi des paramà¨tres du formulaire lors de sa validation
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        
        // Envoi des champs de chaque élément de la table 'Site' à  'form'
        $form->setFields(["login", "password\n", "elementsMasques", "fondEcran", "couleur\n", "ordre", "submit"]);
        
        // Envoi des titres à  chaque champ des éléments de la table 'Site' à  'table'
        $form->setCaptions(["Login","Mot de passe","à‰léments masqués","Fond d'écran","Couleur", "Ordre","Valider"]);
        
        // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
        $form->fieldAsSubmit("submit", "green", $action, "#divUsers");
        
        // Chargement de la page HTML 'index.html' de la vue
        $this->loadView("Utilisateur\index.html");
        
        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Affiche le formulaire des préférences utilisateurs.
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function preferences(){
        $id=$_SESSION["user"]->getId();
        $user=DAO::getOne("models\Utilisateur", $id);
        $this->_preferences($user, "UserController/updateUser/".$id."/Utilisateur", $user->getLogin(), $user->getPassword());
    }

    /**
     * <h1>Description de la méthode</h1>
     *
     * Met à  jour les données de l'utilisateur connecté.
     * 
     * @param id : Identifiant récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
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
    
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Exécute de l'ajout d'un nouveau lien.
     *
     * @param id : Identifiant récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function newLink(){
        
        // Variable 'site' récupérant toutes les données d'un nouveau site
        $lien=new Lienweb();
        
        // Exécution de la requàªte d'insertion de toutes les valeurs entrées dans le formulaire d'ajout d'un nouveau lien web
        RequestUtils::setValuesToObject($lien,$_POST);
        
        // Condition si l'insertion d'un nouveau site est exécutée
        if(DAO::insert($lien)){
            // Affichage du message suivant
            echo "Le lien ".$user->getNom()." a été ajouté.";
        }
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Exécute de la suppression d'un lien.
     *
     * @param id : Identifiant récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function deleteLink($id){
        // Variable $liens récupérant toutes les données d'un site selon son id et le modà¨le 'Site'
        $liens=DAO::getOne("models\Lienweb", "id=".$id);
        
        // Instanciation du modà¨le 'Site' sur le site récupéré et exécution de la requàªte de suppression
        $liens instanceof models\Lienweb && DAO::remove($liens);
        
        // Retour sur la page d'affichage de tous les sites
        $this->forward("controllers\UserController","listeFavoris");
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Exécute de la modification d'un lien.
     *
     * @param id : Identifiant récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function editLink($id){
        $liens=DAO::getOne("models\Lienweb", $id);
        $this->_formFavoris($liens,"UserController/updateLink/".$id."/Lienweb",$liens->getLibelle(),$liens->getUrl(),$liens->getOrdre());
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Exécute de la mise à  jour d'un lien.
     *
     * @param id : Identifiant récupéré de l'utilisateur connecté
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function updateLink($id){
        $liens=DAO::getOne("models\Lienweb", $id);
        RequestUtils::setValuesToObject($liens,$_POST);
        if(DAO::update($liens)){
            echo "Le lien ".$liens->getLibelle()." a été modifié.";
        }
    }
    
    /**
     * <h1>Description de la méthode</h1>
     *
     * Affiche la liste des moteurs disponibles sous forme de tableau.
     *
     * @author Matteo ETTORI
     * @version 1.0
     */
    public function moteur(){
        $semantic=$this->jquery->semantic();

        $moteurSelected=$_SESSION['user']->getMoteur(); // Récupération du moteur selectionné
        $moteurs=DAO::getAll("models\Moteur"); // Récuperation de tout les moteurs

        $table=$semantic->dataTable("tblMoteurs", "models\Moteur", $moteurs); // Stockage des moteurs dans un tableau

        $table->setIdentifierFunction("getId"); // Récupération de l'identifiant du moteur
        $table->setFields(["nom", "code"]); // Champs de la table 'moteur' à  afficher
        $table->setCaptions(["Nom", "Code", "Sélectionner"]); // Titre des champs du moteur
        $table->setTargetSelector("#divUsers");
        
        // Différenciation du moteur déjà  selectionné par rapport aux autres
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
    

    /**
     * <h1>Description de la méthode</h1>
     *
     * Sélectionne le moteur pour le site concerné.
     *
     * @author Joffrey MARION
     * @version 1.0
     */
    public function selectionner()
    {
        $recupId = explode('/', $_GET['c']); // Récupération de l'identifiant du moteur à  sélectionner avec un explode de l'URL

        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]); // Récupération du moteur à  sélectionner
        
        $_SESSION["user"]->setMoteur($moteur); // Modification du moteur du site
        
        $_SESSION["user"] instanceof models\Utilisateur && DAO::update($_SESSION["user"]); // Envoi de la requete modifiant le moteur sélectionné pour le site
        $this->forward("controllers\UserController","moteur"); // Retour vers l'index du controlleur
    }
}