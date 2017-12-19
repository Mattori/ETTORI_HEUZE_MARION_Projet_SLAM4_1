<?php
namespace controllers;
use Ajax\semantic\html\elements\HtmlButton;
use Ubiquity\orm\DAO;

 /**
 * Controller Connecte
 * @property JsUtils $jquery
 **/
class Formulaire extends ControllerBase{
    
    private $utilisateur;
    private $bloc;
    private $champ;
    private $titre;
    private $chemin;
    private $vue;

    public function initialize(){
        parent::initialize();
        $this->site=DAO::getOne("models\Site",0);
    }
    
    public function __construct($utilisateur="", $bloc="", $champ="", $titre="", $chemin="", $vue=""){
        $this->utilisateur=$utilisateur;
        $this->bloc=$bloc;
        $this->champ=$champ;
        $this->titre=$titre;
        $this->chemin=$chemin;
        $this->vue=$vue;
    }
    
	public function index(){
	    
	    $liensPerso=DAO::getAll("models\Lienweb","idUtilisateur = ".$this->utilisateur);
	    $liens=DAO::getAll("models\Lienweb");
	    
	    $semantic=$this->jquery->semantic();
	    
	    $bts=$this->jquery->semantic()->htmlButtonGroups("bts-liens");
	    $bts->fromDatabaseObjects($liens, function($lien){
	        $bt=new HtmlButton("",$lien->getLibelle(),"blue");
	        $bt->asLink($lien->getUrl(),"_new");
	        return $bt;
	    });
	    
	    $this->jquery->compile($this->view);
	    $this->loadView('Connecte.html');
	}
	
	public function construireForm(){
	    // Déclaration d'une nouvelle Semantic-UI
	    $semantic=$this->jquery->semantic();
	    
	    // Affectation du langage français à la 'semantic'
	    $semantic->setLanguage("fr");
	    
	    // Variable 'form' affectant la 'semantic' locale au formulaire d'id 'frmSite' au paramètre '$site'
	    $form=$semantic->dataForm($bloc, $utilisateur);
	    
	    // Envoi des paramètres du formulaire lors de sa validation
	    $form->setValidationParams(["on"=>"blur", "inline"=>true]);
	    
	    // Envoi des champs de chaque élément de la table 'Site' à 'form'
	    $form->setFields($champ);
	    
	    // Envoi des titres à chaque champ des éléments de la table 'Site' à 'table'
	    $form->setCaptions($titre);
	    
	    // Ajout d'un bouton de validation 'submit' de couleur verte 'green' récupérant l'action et l'id du bloc '#divSites'
	    $form->fieldAsSubmit("submit", "green", $chemin, "#divUsers");
	    
	    // Chargement de la page HTML 'index.html' de la vue
	    $this->loadView($vue);
	    
	    echo $form->compile($this->jquery);
	    echo $this->jquery->compile();
    }
}