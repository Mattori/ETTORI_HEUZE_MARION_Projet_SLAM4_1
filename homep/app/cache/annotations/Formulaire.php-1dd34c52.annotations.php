<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
  'DAO' => 'micro\\orm\\DAO',
),
  '#traitMethodOverrides' => array (
  'controllers\\Formulaire' => 
  array (
  ),
),
  'controllers\\Formulaire' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\Formulaire::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/users")
  ),
);

