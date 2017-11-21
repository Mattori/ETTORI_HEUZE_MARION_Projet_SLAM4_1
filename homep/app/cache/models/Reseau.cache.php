<?php
<<<<<<< HEAD
return array("#tableName"=>"Reseau","#primaryKeys"=>array("id"),"#manyToOne"=>array("site"),"#fieldNames"=>array("id"=>"id","ip"=>"ip","site"=>"idSite"),"#fieldTypes"=>array("id"=>"int(11)","ip"=>"varchar(15)","site"=>""),"#nullable"=>array("ip"),"#notSerializable"=>array("site"),"#joinColumn"=>array("site"=>array("className"=>"models\\Site","name"=>"idSite")),"#invertedJoinColumn"=>array("idSite"=>array("member"=>"site","className"=>"models\\Site")));
=======
return array("#tableName"=>"Reseau","#primaryKeys"=>array("id"),"#manyToOne"=>array("site"),"#fieldNames"=>array("id"=>"id","ip"=>"ip","site"=>"idSite"),"#fieldTypes"=>array("id"=>"mixed","ip"=>"mixed","site"=>""),"#nullable"=>array(),"#notSerializable"=>array("site"),"#joinColumn"=>array("site"=>array("className"=>"models\\Site","name"=>"idSite")),"#invertedJoinColumn"=>array("idSite"=>array("member"=>"site","className"=>"models\\Site")));
>>>>>>> f8a208912f0fd37adf5c0d42480a10f9ba07acaa
