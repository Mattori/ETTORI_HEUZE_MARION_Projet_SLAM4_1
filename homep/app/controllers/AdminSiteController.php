<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Moteur;
use Ajax\semantic\html\collections\HtmlBreadcrumb;

/**
 * Controller AdminSiteController
 * @property JsUtils $jquery
 **/
class AdminSiteController extends ControllerBase
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
     * Affiche le menu de la page si un administrateur de site est connecté
     * {@inheritdoc}
     * @see \micro\controllers\Controller::index()
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        if(!isset($_SESSION["user"])) {
            $img=$semantic->htmlImage("imgtest","assets/img/homepage_symbol.jpg","Image d'accueil","small");
            $menu=$semantic->htmlMenu("menu9");
            $menu->addItem("<h4 class='ui header'>Accueil</h4>");
            $menu->addItem("<h4 class='ui header'>Connexion</h4>");
            $menu->setPropertyValues("data-ajax", ["", "connexion/AdminSiteController/submit"]);
            $menu->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
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
            if($_SESSION["user"]->getStatut()->getId() >=2) {
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
                $menu->addItem("<h4 class='ui header'>Configuration du site</h4>");
                $menu->addItem("<h4 class='ui header'>Moteur de recherche</h4>");
                $menu->addItem("<h4 class='ui header'>Ordre des éléments</h4>");
                $menu->addItem("<h4 class='ui header'>Droits de personnalisation</h4>");
                $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
                $menu->setPropertyValues("data-ajax", ["", "configuration/", "moteur/", "ordreElement/", "optionsUtilisateur/", "deconnexion/AdminSiteController/index"]);
                $menu->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
                $menu->setVertical();
                
                $bc=new HtmlBreadcrumb("bc2", array("Accueil","Administrateur de sites"));
                $bc->setContentDivider(">");
                echo $bc;
                
                $mess=$semantic->htmlMessage("mess3","Vous àªtes désormais connecté, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
                $mess->addHeader("Bienvenue !");
                $mess->setDismissable();
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
                $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
                $menu->setPropertyValues("data-ajax", ["", "deconnexion/AdminSiteController/index"]);
                $menu->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
                $menu->setVertical();
                
                $bc=new HtmlBreadcrumb("bc2", array("Accueil","Administrateur de sites"));
                $bc->setContentDivider(">");
                echo $bc;
                
                $mess=$semantic->htmlMessage("mess3","Accès à la page d'administration du site interdit, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
                $mess->addHeader("Erreur !");
                $mess->setDismissable();
            }
        }
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\index.html");
    }
    
    /**
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et {@literal <b> JavaDoc </b> }
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     * 
     * Affiche un formulaire de configuration des paramètres du site.
     * 
     * @see initialize
     * @see $_SESSION
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     * @see \controllers\ControllerBase::initialize()
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
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et {@literal <b> JavaDoc </b> }
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     *
     * Affiche un tableau des options personnalisable par les utilisateurs avec les boutons 'Autoriser' et 'Interdire'.
     *
     * @see initialize
     * @see $_SESSION
     *
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     * @see \controllers\ControllerBase::initialize()
     */
    public function optionsUtilisateur(){
        $semantic=$this->jquery->semantic();
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsU", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getSite()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getSite()->getOptions());
        
        // Ajout d'attribus aux boutons pour que l'on puisse identifier ce qui est déjà  autorisé et interdit
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
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et {@literal <b> JavaDoc </b> }
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     *
     * Confirme l'interdiction pour les utilisateurs du site de personnaliser une option.
     *
     * @see initialize
     * @see $_SESSION
     *
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     * @see \controllers\ControllerBase::initialize()
     */
    public function interdireOptnSite(){
        $recupId = explode('/', $_GET['c']);
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId());
        $siteOptions = explode(",",$site->getOptions());
        
        $newOptn = "";
        $i = 0;
        
        // Conditon vérifiant si cela recrée les options attribués avec la nouvelle interdiction
        while($i<count($siteOptions))
        {
            if($siteOptions[$i]!=$recupId[2])
            {
                // Condition vérifiant si on est à la première option
                if($i == 0)
                {
                    $newOptn = $siteOptions[$i]; // N'afficha pas la virgule
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
     * Confirme l'autorisation pour les utilisateurs du site de personnaliser une option.
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
     * Confirme la modification du site dans la BDD.
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
     * Affiche un tableau des moteurs de la BDD avec un bouton de sélection pour le site.
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

        // Différenciation du moteur déjà selectionné des autres
        $table->addFieldButton("Selectionner",false,function(&$bt,$instance) use($moteurSelected){
            if($instance->getId()==$moteurSelected->getId()){
                $bt->addClass("disabled");
            }else{
                $bt->addClass("_toSelect");
            }
        });
        $this->jquery->getOnClick("._toSelect", "AdminSiteController/selectionner","#divSite",["attr"=>"data-ajax"]);
        
        // Bouton d'ajout d'un moteur
        $btAdd=$semantic->htmlButton('btAdd','ajouter un moteur');
        $btAdd->getOnClick("AdminSiteController/newMoteur","#divSite");
        
        echo $table->compile($this->jquery);
        echo $btAdd->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
     * Confirme la sélection du moteur pour un site.
     * {@inheritdoc}
     */
    public function selectionner()
    {
        $recupId = explode('/', $_GET['c']); // Récupération de l'identifiant du moteur à partir de l'URL
        
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId()); // Instanciation du site et du moteur sélectionné
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        
        $site->setMoteur($moteur); // Mise à jour à l'intérieur du modèle
        $_SESSION["user"]->getSite()->setMoteur($moteur);

        $site instanceof models\Site && DAO::update($site); // Mise à jour à l'intérieur de la BDD
        
        $this->forward("controllers\AdminSiteController","moteur");
    }
    
    /**
     * Appelle la méthode _frmMoteur créant un nouveau moteur.
     * {@inheritdoc}
     */
    public function newMoteur()
    {
        $this->_frmMoteur(null,"AdminSiteController/newMoteurConfirm/","Ajouter");
    }
    
    /**
     * Appelle la méthode _frmMoteur modifiant un moteur.
     * {@inheritdoc}
     */
    public function editMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        $this->_frmMoteur($recupId[2],"AdminSiteController/editMoteurConfirm/","Modifier");
    }
    
    /**
     * Appelle la méthode _frmMoteur supprimant un moteur.
     * {@inheritdoc}
     */
    public function deleteMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        $this->_frmMoteur($recupId[2],"AdminSiteController/deleteMoteurConfirm/","Supprimer");
    }
    
    /**
     * Affiche le formulaire d'un moteur.
     * {@inheritdoc}
     * @param int idM : Identifiant du moteur
     * @param string action : URL de redirection
     * @param string actionMsg : Nom du bouton en lien avec l'action
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
     * Confirme la création du nouveau moteur dans la BDD.
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
     * Confirme la modification du moteur dans la BDD.
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
     * Confirme la suppression du moteur dans la BDD.
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
     * Affiche une carte Google Maps.
     * {@inheritdoc}
     * @param float lat : Latitude du site
     * @param float long : Longitude du site
     * @return string : Script de la carte Google Maps
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
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à  la valeur de la latitude de l'évènement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' à  la valeur de la longitude de l'évènement
                    document.getElementById('frmSite-longitude').value=event.latLng.lng();
                })
                
                // Ajout d'un évènement lorsque l'on change la latitude de la div d'id 'frmSite-latitude'
                frmSite-latitude.addListener('change', function(event){
                    // Affectation de la valeur de la cible de l'évènement à  la valeur de la latitude de la carte
                    event.target.value=map.latLng.lat();
                })
            }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }
}