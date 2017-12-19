<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'StrUtils' => 'Ubiquity\\utils\\StrUtils',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'ClassUtils' => 'Ubiquity\\cache\\ClassUtils',
  'InfoMessage' => 'Ubiquity\\controllers\\admin\\popo\\InfoMessage',
  'Database' => 'Ubiquity\\db\\Database',
  'HtmlSemDoubleElement' => 'Ajax\\semantic\\html\\base\\HtmlSemDoubleElement',
  'JsUtils' => 'Ajax\\JsUtils',
  'HtmlIcon' => 'Ajax\\semantic\\html\\elements\\HtmlIcon',
  'FsUtils' => 'Ubiquity\\utils\\FsUtils',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\CheckTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\CheckTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'array', 'name' => 'steps'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'int', 'name' => 'activeStep'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'string', 'name' => 'engineering'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'Ubiquity\\controllers\\admin\\traits\\CheckTrait::_getAdminFiles' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminFiles')
  ),
);

