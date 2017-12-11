<?php
namespace controllers;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use micro\controllers\Controller;
 /**
 * ControllerBase
 * @property JsUtils $jquery
 **/
abstract class ControllerBase extends Controller{

	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html");
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
	
	public function connexion() {
	    $action=$_POST["action"];
	    $frm=$this->jquery->semantic()->defaultLogin("connect");
	    $frm->fieldAsSubmit("submit","green",$action,"body");
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
	
	public function submit(){
	    $id=RequestUtils::get('id');
	    $user=DAO::getOne("models\Utilisateur", "login='".$_POST["login"]."'");
	    if(isset($user)){
	        $_SESSION["user"] = $user;
	        $this->jquery->exec("$('body').attr('style','background: url(".$user->getFondEcran().")');",true);
	    }
	    $this->index();
	}
	
	public function testCo(){
	    var_dump($_SESSION["user"]);
	}
	
	public function deconnexion($action) {
	    session_unset();
	    session_destroy();
	    $this->jquery->get($action,"body");
	    $this->jquery->exec("$('body').attr('style','');",true);
	    $this->index();
	}
}
