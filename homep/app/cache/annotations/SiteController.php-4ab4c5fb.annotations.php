<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
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

