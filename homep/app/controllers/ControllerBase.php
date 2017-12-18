<?php
namespace controllers;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use micro\controllers\Controller;
 /**
 * ControllerBase
 * @property JsUtils $jquery
 **/
abstract class ControllerBase extends Controller
{
    /**
     * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc
     * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
     *
     * Initialise l'utilisateur connecté ainsi que son fond d'écran (dont l'URL est enregistré dans la BDD) en commençant par le Header de la vue concernée.
     *
     * @author Matteo ETTORI
     * @version 1.0
     * {@inheritDoc}
     */
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html");
		}
	}
    
	/**
	 * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc
	 * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
	 *
	 * Initialise l'utilisateur connecté ainsi que son fond d'écran (dont l'URL est enregistré dans la BDD) en terminant le Footer de la vue concernée.
	 *
	 * @author Matteo ETTORI
	 * @version 1.0
	 * {@inheritDoc}
	 */
	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
	
	/**
	 * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc </b>
	 * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
	 *
	 * Crée et génère le formulaire de connexion de l'utilisateur.
	 *
	 * @param ctrl : Contrôleur sur lequel effectuer l'action de connexion
	 * @param action : Action de retour/renvoi pour la connexion
	 *
	 * @author Matteo ETTORI
	 * @version 1.0
	 * {@inheritDoc}
	 */
	public function connexion($ctrl,$action) {
	    $frm=$this->jquery->semantic()->defaultLogin("connect");
	    $frm->fieldAsSubmit("submit","green",$ctrl."/".$action,"body");
	    $frm->removeField("Connexion");
	    $frm->setCaption("login", "Identifiant");
	    $frm->setCaption("password", "Mot de passe");
	    $frm->setCaption("remember", "Se souvenir de moi");
	    $frm->setCaption("forget", "Mot de passe oublié ?");
	    $frm->setCaption("submit", "Connexion");
	    echo $frm->asModal();
	    $this->jquery->exec("$('#modal-connect').modal('show');",true);
	    echo $this->jquery->compile($this->view);
	}
	
	/**
	 * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc </b>
	 * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
	 *
	 * Valide la connexion de l'utilisateur et exécute l'affichage du fond d'écran le concernant.
	 *
	 * @author Matteo ETTORI
	 * @version 1.0
	 * {@inheritDoc}
	 */
	public function submit(){
	    $id=RequestUtils::get('id');
	    $user=DAO::getOne("models\Utilisateur", "login='".$_POST["login"]."'");
	    if(isset($user)){
	        $_SESSION["user"] = $user;
	        $this->jquery->exec("$('body').attr('style','background: url(".$user->getFondEcran().")');",true);
	    }
	    $this->index();
	}

	/**
	 * <h1>Description de la méthode</h1> Utilisant <b>les Tags HTML</b> et JavaDoc </b>
	 * Pour plus de détails, voir : {@link http://www.dvteclipse.com/documentation/sv/Export_HTML_Documentation.html DVT Documentation}
	 *
	 * Met fin à la connexion (déconnexion) d'un utilisateur connecté et retourne à l'index du contrôleur concerné.
	 * 
	 * @param action : Action de retour/renvoi par le nom du contrôleur concerné
	 *
	 * @author Matteo ETTORI
	 * @version 1.0
	 * {@inheritDoc}
	 */
	public function deconnexion($action) {
	    session_unset();
	    session_destroy();
	    $this->jquery->get($action,"body");
	    $this->jquery->exec("$('body').attr('style','');",true);
	    $this->index();
	}
}
