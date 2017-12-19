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
    array('#name' => 'id', '#type' => 'Ubiquity\\annotations\\IdAnnotation'),
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"id","nullable"=>"","dbType"=>"int(11)")
  ),
  'models\\Moteur::$nom' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"nom","nullable"=>1,"dbType"=>"varchar(45)")
  ),
  'models\\Moteur::$code' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"code","nullable"=>1,"dbType"=>"text")
  ),
  'models\\Moteur::$etablissements' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"moteur","className"=>"models\\Etablissement")
  ),
  'models\\Moteur::$sites' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"moteur","className"=>"models\\Site")
  ),
  'models\\Moteur::$utilisateurs' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"moteur","className"=>"models\\Utilisateur")
  ),
);

