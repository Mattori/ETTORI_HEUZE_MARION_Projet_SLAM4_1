<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
  'models' => 'models',
  'Moteur' => 'models\\Moteur',
),
  '#traitMethodOverrides' => array (
  'controllers\\AdminSiteController' => 
  array (
  ),
),
  'controllers\\AdminSiteController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

