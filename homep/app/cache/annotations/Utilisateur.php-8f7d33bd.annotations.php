<?php

return array(
  '#namespace' => 'models',
  '#uses' => array (
),
  '#traitMethodOverrides' => array (
  'models\\Utilisateur' => 
  array (
  ),
),
  'models\\Utilisateur::$id' => array(
    array('#name' => 'id', '#type' => 'Ubiquity\\annotations\\IdAnnotation'),
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"id","nullable"=>"","dbType"=>"int(11)")
  ),
  'models\\Utilisateur::$nom' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"nom","nullable"=>1,"dbType"=>"varchar(25)")
  ),
  'models\\Utilisateur::$prenom' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"prenom","nullable"=>1,"dbType"=>"varchar(25)")
  ),
  'models\\Utilisateur::$login' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"login","nullable"=>1,"dbType"=>"varchar(45)")
  ),
  'models\\Utilisateur::$password' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"password","nullable"=>1,"dbType"=>"varchar(45)")
  ),
  'models\\Utilisateur::$elementsMasques' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"elementsMasques","nullable"=>1,"dbType"=>"varchar(255)")
  ),
  'models\\Utilisateur::$fondEcran' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"fondEcran","nullable"=>1,"dbType"=>"varchar(255)")
  ),
  'models\\Utilisateur::$couleur' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"couleur","nullable"=>1,"dbType"=>"varchar(10)")
  ),
  'models\\Utilisateur::$ordre' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"ordre","nullable"=>1,"dbType"=>"varchar(255)")
  ),
  'models\\Utilisateur::$moteur' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\ManyToOneAnnotation'),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\JoinColumnAnnotation', "className"=>"models\\Moteur","name"=>"idMoteur","nullable"=>"")
  ),
  'models\\Utilisateur::$site' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\ManyToOneAnnotation'),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\JoinColumnAnnotation', "className"=>"models\\Site","name"=>"idSite","nullable"=>"")
  ),
  'models\\Utilisateur::$statut' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\ManyToOneAnnotation'),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\JoinColumnAnnotation', "className"=>"models\\Statut","name"=>"idStatut","nullable"=>"")
  ),
  'models\\Utilisateur::$lienwebs' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"utilisateur","className"=>"models\\Lienweb")
  ),
);

