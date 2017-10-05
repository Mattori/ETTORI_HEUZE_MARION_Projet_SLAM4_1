<?php
return array("#tableName"=>"Utilisateur","#primaryKeys"=>array("id"),"#manyToOne"=>array("moteur","site","statut"),"#fieldNames"=>array("id"=>"id","login"=>"login","password"=>"password","moteur"=>"id_moteur","site"=>"id_site","statut"=>"id_statut","lienwebs"=>"lienwebs"),"#nullable"=>array(),"#notSerializable"=>array("moteur","site","statut","lienwebs"),"#oneToMany"=>array("lienwebs"=>array("mappedBy"=>"utilisateur","className"=>"models\Lienweb")),"#joinColumn"=>array("moteur"=>array("className"=>"models\Moteur","name"=>"id_moteur","nullable"=>false),"site"=>array("className"=>"models\Site","name"=>"id_site","nullable"=>false),"statut"=>array("className"=>"models\Statut","name"=>"id_statut","nullable"=>false)),"#invertedJoinColumn"=>array("id_moteur"=>array("member"=>"moteur","className"=>"models\Moteur"),"id_site"=>array("member"=>"site","className"=>"models\Site"),"id_statut"=>array("member"=>"statut","className"=>"models\Statut")));
