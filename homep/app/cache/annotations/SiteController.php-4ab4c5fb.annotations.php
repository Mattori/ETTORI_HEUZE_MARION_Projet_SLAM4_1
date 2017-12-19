<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'models' => 'models',
  'Site' => 'models\\Site',
),
  '#traitMethodOverrides' => array (
  'controllers\\SiteController' => 
  array (
  ),
),
  'controllers\\SiteController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

