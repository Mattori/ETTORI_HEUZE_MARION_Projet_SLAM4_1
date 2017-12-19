<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'models' => 'models',
  'Lienweb' => 'models\\Lienweb',
  'HtmlBreadcrumb' => 'Ajax\\semantic\\html\\collections\\HtmlBreadcrumb',
),
  '#traitMethodOverrides' => array (
  'controllers\\UserController' => 
  array (
  ),
),
  'controllers\\UserController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\UserController::_preferences' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'user', 'name' => false),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'action', 'name' => false),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'login', 'name' => false),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'password', 'name' => '')
  ),
  'controllers\\UserController::updateUser' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'id', 'name' => '')
  ),
  'controllers\\UserController::newLink' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'id', 'name' => '')
  ),
  'controllers\\UserController::deleteLink' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'id', 'name' => '')
  ),
  'controllers\\UserController::editLink' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'id', 'name' => '')
  ),
  'controllers\\UserController::updateLink' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'id', 'name' => '')
  ),
);

