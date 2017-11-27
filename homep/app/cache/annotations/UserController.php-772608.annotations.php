<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
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

