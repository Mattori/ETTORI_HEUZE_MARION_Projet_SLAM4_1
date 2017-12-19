<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'models' => 'models',
  'Moteur' => 'models\\Moteur',
  'HtmlBreadcrumb' => 'Ajax\\semantic\\html\\collections\\HtmlBreadcrumb',
),
  '#traitMethodOverrides' => array (
  'controllers\\AdminSiteController' => 
  array (
  ),
),
  'controllers\\AdminSiteController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\AdminSiteController::_frmMoteur' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'int', 'name' => 'dM'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'ction'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'ctionMsg')
  ),
  'controllers\\AdminSiteController::_generateMap' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'float', 'name' => 'at'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'float', 'name' => 'ong'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
);

