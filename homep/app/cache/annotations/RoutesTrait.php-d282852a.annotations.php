<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'StrUtils' => 'Ubiquity\\utils\\StrUtils',
  'ControllerAction' => 'Ubiquity\\controllers\\admin\\popo\\ControllerAction',
  'Router' => 'Ubiquity\\controllers\\Router',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'Route' => 'Ubiquity\\controllers\\admin\\popo\\Route',
  'Startup' => 'Ubiquity\\controllers\\Startup',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\RoutesTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\RoutesTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

