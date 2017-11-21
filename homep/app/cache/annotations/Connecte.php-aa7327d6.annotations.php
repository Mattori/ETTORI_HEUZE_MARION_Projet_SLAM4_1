<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'JsUtils' => 'Ajax\\JsUtils',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
),
  '#traitMethodOverrides' => array (
  'controllers\\Connecte' => 
  array (
  ),
),
  'controllers\\Connecte' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\Connecte::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/users")
  ),
);

