<?php
return array(
		"siteUrl"=>"http://127.0.0.1/Homepage/homep/",
		"database"=>[
		        "type"=>"mysql",// ajout pr admin
				"dbName"=>"homepage",
				"serverName"=>"127.0.0.1",
				"port"=>"3306",
				"user"=>"root",
				"password"=>"",
		        "options"=>[],
				"cache"=>false
		],
		"templateEngine"=>'Ubiquity\views\engine\Twig',
		"templateEngineOptions"=>array("cache"=>false),
		"test"=>false,
		"debug"=>false,
		"di"=>["jquery"=>function(){
							$jquery=new Ajax\php\Ubiquity\JsUtils(["defer"=>true]);
							$jquery->semantic(new Ajax\Semantic());
							$jquery->setAjaxLoader('<div class="ui active inverted dimmer"><div class="ui text loader">Loading</div></div><p></p>');
							return $jquery;
						}],
		"cache"=>["directory"=>"cache/","system"=>"Ubiquity\\cache\\system\\ArrayCache","params"=>[]],
		"mvcNS"=>["models"=>"models","controllers"=>"controllers"],
		"isRest"=>function(){
		      return Ubiquity\utils\RequestUtils::getUrlParts()[0]==="rest";
		}
);
