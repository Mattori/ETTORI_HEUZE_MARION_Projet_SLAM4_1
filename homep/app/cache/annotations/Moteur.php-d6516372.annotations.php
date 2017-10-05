<?php

return array(
  '#namespace' => 'models',
  '#uses' => array (
),
  '#traitMethodOverrides' => array (
  'models\\Moteur' => 
  array (
  ),
),
  'models\\Moteur::$id' => array(
    array('#name' => 'id', '#type' => 'micro\\annotations\\IdAnnotation')
  ),
  'models\\Moteur::$etablissements' => array(
    array('#name' => 'oneToMany', '#type' => 'micro\\annotations\\OneToManyAnnotation', "mappedBy"=>"moteur","className"=>"models\Etablissement")
  ),
  'models\\Moteur::$utilisateurs' => array(
    array('#name' => 'oneToMany', '#type' => 'micro\\annotations\\OneToManyAnnotation', "mappedBy"=>"moteur","className"=>"models\Utilisateur")
  ),
);

