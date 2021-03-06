<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlBreadcrumb;
use Ubiquity\orm\DAO;
use Ubiquity\utils\RequestUtils;
use models;
use models\Moteur;

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
     * @see \controllers\ControllerBase::initialize()
     * 
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
    public function initialize(){
        $fond="";
        if(isset($_SESSION["user"])){
            $user=$_SESSION["user"];
            // condition vérifiant les droits de personalisation, 2 correspondants à l'option fond d'ecran
            if(strpos($user->getSite()->getOptions(),"2") != null)
            {
                $fond=$user->getFondEcran();
            }
            else
            {
                $fond=$user->getSite()->getFondEcran();
            }
        }
        if(!RequestUtils::isAjax()){
            $this->loadView("main/vHeader.html",["fond"=>$fond]);
        }
    }
    
    /**
     * Affiche le menu de la page si un administrateur de site est connecté
     * 
     * @see \Ubiquity\controllers\Controller::index()
     * 
     * @author Matteo ETTORI / Joffrey MARION
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
            $menu->setPropertyValues("data-ajax", ["", "connexion/AdminSiteController/submit"]);
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled("Google");
            
            $frm->setProperty("action","https://www.google.fr/search?q=");
        } else {
            // /!\ à faire: condition verifiant les droits avant de savoir quel moteur utiliser
            $moteur=DAO::getOne("models\Utilisateur","idMoteur=".$_SESSION["user"]->getMoteur());
            
            if($_SESSION["user"]->getStatut()->getId() >= 3) {
                $menu2=$semantic->htmlMenu("menu2");
                $menu2->addItem("<h4 class='ui header'>Administration globale</h4>");
                $menu2->addItem("<h4 class='ui header'>Préférences utilisateur</h4>");
                $menu2->setPropertyValues("href", ["SiteController/", "UserController/"]);
            }
            
            if($_SESSION["user"]->getStatut()->getId() == 2) {
                $menu2=$semantic->htmlMenu("menu2");
                $menu2->addItem("<h4 class='ui header'>Préférences utilisateur</h4>");
                $menu2->setPropertyValues("href", ["UserController/"]);
            }
            
            if($_SESSION["user"]->getStatut()->getId() >= 2) {     
                $menu->addItem("<h4 class='ui header'>Configuration du site</h4>");
                $menu->addItem("<h4 class='ui header'>Moteur de recherche</h4>");
                $menu->addItem("<h4 class='ui header'>Ordre des éléments</h4>");
                $menu->addItem("<h4 class='ui header'>Droits de personnalisation</h4>");
                $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
                $menu->setPropertyValues("data-ajax", ["", "configuration/", "moteur/", "ordreElement/", "optionsUtilisateur/", "deconnexion/AdminSiteController/index"]);
                
                $mess=$semantic->htmlMessage("mess3","Vous étes désormais connecté, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
                $mess->addHeader("Bienvenue !");
                $mess->setDismissable();
            } else {
                $frm=$this->jquery->semantic()->htmlForm("frm-search");
                $input=$frm->addInput("q");
                $input->labeled($moteur->getMoteur()->getNom());
                
                $frm->setProperty("action",$moteur->getMoteur()->getCode());

                $menu->addItem("<h4 class='ui header'>Déconnexion</h4>");
                $menu->setPropertyValues("data-ajax", ["", "deconnexion/AdminSiteController/index"]);
                
                $mess=$semantic->htmlMessage("mess3","Accés éla page d'administration du site interdit, ".$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom(). "!");
                $mess->addHeader("Erreur !");
                $mess->setDismissable();
            }
            $title=$semantic->htmlHeader("header5",4);
            $title->asImage("https://semantic-ui.com/images/avatar2/large/patrick.png",$_SESSION["user"]->getNom()." ".$_SESSION["user"]->getPrenom());
            
            $frm=$this->jquery->semantic()->htmlForm("frm-search");
            $input=$frm->addInput("q");
            $input->labeled($moteur->getMoteur()->getNom());
            
            $frm->setProperty("action",$moteur->getMoteur()->getCode());
        }
        
        $frm->setProperty("method","get");
        $frm->setProperty("target","_new");
        $bt=$input->addAction("Rechercher");
        echo $frm;
        
        $menu->getOnClick("AdminSiteController/","#divSite",["attr"=>"data-ajax"]);
        $menu->setVertical();
        
        $bc=new HtmlBreadcrumb("bc2", array("Accueil","Administrateur de sites"));
        $bc->setContentDivider(">");
        echo $bc;
        
        $this->jquery->compile($this->view);
        $this->loadView("AdminSite\index.html");
    }
    
    /**
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et {@literal <b> JavaDoc </b> }
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     * 
     * Affiche un formulaire de configuration des paramétres du site.
     * 
     * @see $_SESSION
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * @see $_SESSION
     *
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function optionsUtilisateur(){
        $semantic=$this->jquery->semantic();
        
        $options=DAO::getAll("models\Option");
        
        $form=$semantic->dataTable("tblOptionsU", "models\Option", $options);
        $form->setFields(["id","libelle"]);
        $form->setCaptions(["id","libelle",'personnalisable']);
        
        $optionSelect = DAO::getOne('models\Site',$_SESSION["user"]->getSite()->getOptions());
        $optionsSelect = explode(',',$_SESSION["user"]->getSite()->getOptions());
        
        // Ajout d'attribus aux boutons pour que l'on puisse identifier ce qui est déjé autorisé et interdit
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
     * @see $_SESSION
     *
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
                // Condition vérifiant si on est �la premi�re option
                if($newOptn == "")
                {
                    $newOptn = $siteOptions[$i]; // N'affiche pas la virgule
                }else
                {
                    $newOptn = $newOptn . "," . $siteOptions[$i];
                }
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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

        // Différenciation du moteur déjéselectionné des autres
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function selectionner()
    {
        $recupId = explode('/', $_GET['c']); // Récupération de l'identifiant du moteur é partir deél'URL
        
        $site=DAO::getOne("models\Site", $_SESSION["user"]->getSite()->getId()); // Instanciation du site et du moteur sélectionné
        $moteur=DAO::getOne("models\Moteur", "id=".$recupId[2]);
        
        $site->setMoteur($moteur); // Mise é jour é l'intérieur du modéle
        $_SESSION["user"]->getSite()->setMoteur($moteur);

        $site instanceof models\Site && DAO::update($site); // Mise é jour é l'intérieur de la BDD
        
        $this->forward("controllers\AdminSiteController","moteur");
    }
    
    /**
     * Appelle la méthode _frmMoteur créant un nouveau moteur.
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function newMoteur()
    {
        $this->_frmMoteur(null,"AdminSiteController/newMoteurConfirm/","Ajouter");
    }
    
    /**
     * Appelle la méthode _frmMoteur modifiant un moteur.
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
     */
    public function editMoteur()
    {
        $recupId = explode('/', $_GET['c']);
        $this->_frmMoteur($recupId[2],"AdminSiteController/editMoteurConfirm/","Modifier");
    }
    
    /**
     * Appelle la méthode _frmMoteur supprimant un moteur.
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
     * 
     * @author Joffrey MARION
     * @version 1.0
     * {@inheritDoc}
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
					radius: 200
				}
                // Affectation du cercle
				var cercle = new google.maps.Circle(optionsCercle);
				
                // Ajout d'un événement lorsque l'on clique sur la carte
                map.addListener('click',function(event){
                    // Affectation de la valeur de la div d'id 'frmSite-latitude' é la valeur de la latitude de l'événement
                    document.getElementById('frmSite-latitude').value=event.latLng.lat();
                    
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