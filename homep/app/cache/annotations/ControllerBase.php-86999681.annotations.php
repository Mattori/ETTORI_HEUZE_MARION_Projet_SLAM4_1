<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'Controller' => 'Ubiquity\\controllers\\Controller',
),
  '#traitMethodOverrides' => array (
  'controllers\\ControllerBase' => 
  array (
  ),
),
  'controllers\\ControllerBase' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\ControllerBase::connexion' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'ctrl', 'name' => ''),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'action', 'name' => '')
  ),
  'controllers\\ControllerBase::deconnexion' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'action', 'name' => '')
  ),
);

