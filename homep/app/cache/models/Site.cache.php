<?php
return array("#tableName"=>"Site","#primaryKeys"=>array("id"),"#manyToOne"=>array(),"#fieldNames"=>array("id"=>"id","nom"=>"nom","latitude"=>"latitude","longitude"=>"longitude","ecart"=>"ecart","fondEcran"=>"fondEcran","couleur"=>"couleur","ordre"=>"ordre","options"=>"options","moteur"=>"moteur","lienwebs"=>"lienwebs","reseaus"=>"reseaus","utilisateurs"=>"utilisateurs"),"#fieldTypes"=>array("id"=>"mixed","nom"=>"mixed","latitude"=>"mixed","longitude"=>"mixed","ecart"=>"mixed","fondEcran"=>"mixed","couleur"=>"mixed","ordre"=>"mixed","options"=>"mixed","moteur"=>"mixed","lienwebs"=>"mixed","reseaus"=>"mixed","utilisateurs"=>"mixed"),"#nullable"=>array(),"#notSerializable"=>array("lienwebs","reseaus","utilisateurs"),"#oneToMany"=>array("lienwebs"=>array("mappedBy"=>"site","className"=>"models\\Lienweb"),"reseaus"=>array("mappedBy"=>"site","className"=>"models\\Reseau"),"utilisateurs"=>array("mappedBy"=>"site","className"=>"models\\Utilisateur")));
