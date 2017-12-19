<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin',
  '#uses' => array (
  'JString' => 'Ajax\\service\\JString',
  'HtmlHeader' => 'Ajax\\semantic\\html\\elements\\HtmlHeader',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'OrmUtils' => 'Ubiquity\\orm\\OrmUtils',
  'Reflexion' => 'Ubiquity\\orm\\parser\\Reflexion',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'Autoloader' => 'Ubiquity\\controllers\\Autoloader',
  'UbiquityMyAdminData' => 'Ubiquity\\controllers\\admin\\UbiquityMyAdminData',
  'ControllerBase' => 'controllers\\ControllerBase',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'HtmlItem' => 'Ajax\\semantic\\html\\content\\view\\HtmlItem',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'Route' => 'Ubiquity\\controllers\\admin\\popo\\Route',
  'Router' => 'Ubiquity\\controllers\\Router',
  'StrUtils' => 'Ubiquity\\utils\\StrUtils',
  'CacheFile' => 'Ubiquity\\controllers\\admin\\popo\\CacheFile',
  'HtmlFormFields' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormFields',
  'ControllerAction' => 'Ubiquity\\controllers\\admin\\popo\\ControllerAction',
  'HtmlForm' => 'Ajax\\semantic\\html\\collections\\form\\HtmlForm',
  'ModelsCreator' => 'Ubiquity\\orm\\creator\\ModelsCreator',
  'ModelsConfigTrait' => 'Ubiquity\\controllers\\admin\\traits\\ModelsConfigTrait',
  'FsUtils' => 'Ubiquity\\utils\\FsUtils',
  'ClassToYuml' => 'Ubiquity\\utils\\yuml\\ClassToYuml',
  'Yuml' => 'Ubiquity\\utils\\yuml\\Yuml',
  'ClassesToYuml' => 'Ubiquity\\utils\\yuml\\ClassesToYuml',
  'HtmlCheckbox' => 'Ajax\\semantic\\html\\modules\\checkbox\\HtmlCheckbox',
  'HtmlList' => 'Ajax\\semantic\\html\\elements\\HtmlList',
  'HtmlDropdown' => 'Ajax\\semantic\\html\\modules\\HtmlDropdown',
  'HtmlMenu' => 'Ajax\\semantic\\html\\collections\\menus\\HtmlMenu',
  'JsUtils' => 'Ajax\\JsUtils',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::$adminData' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminData')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::$adminViewer' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminViewer')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::$adminFiles' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminFiles')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::_diagramMenu' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'url'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'params'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'responseElement'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'type'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'HtmlMenu')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminData' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminData')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminViewer' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminViewer')
  ),
  'Ubiquity\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminFiles' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminFiles')
  ),
);

