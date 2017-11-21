<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
  'Site' => 'models\\Site',
  'models' => 'models',
  'ControllerBase' => 'controllers\\ControllerBase',
  'JArray' => 'Ajax\\service\\JArray',
  'Moteur' => 'models\\Moteur',
  'HtmlItem' => 'Ajax\\semantic\\html\\content\\view\\HtmlItem',
  'HtmlFormInput' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormInput',
  'HtmlFormDropdown' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormDropdown',
),
  '#traitMethodOverrides' => array (
  'controllers\\AdminSiteController' => 
  array (
  ),
),
  'controllers\\AdminSiteController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

