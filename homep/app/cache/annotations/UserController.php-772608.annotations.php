<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'models' => 'models',
  'Lienweb' => 'models\\Lienweb',
),
  '#traitMethodOverrides' => array (
  'controllers\\UserController' => 
  array (
  ),
),
  'controllers\\UserController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

