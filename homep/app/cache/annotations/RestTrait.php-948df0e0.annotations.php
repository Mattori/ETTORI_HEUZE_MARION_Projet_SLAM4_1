<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'JString' => 'Ajax\\service\\JString',
  'HtmlForm' => 'Ajax\\semantic\\html\\collections\\form\\HtmlForm',
  'HtmlLabel' => 'Ajax\\semantic\\html\\elements\\HtmlLabel',
  'StrUtils' => 'Ubiquity\\utils\\StrUtils',
  'JsUtils' => 'Ajax\\JsUtils',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'HtmlIconGroups' => 'Ajax\\semantic\\html\\elements\\HtmlIconGroups',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'FsUtils' => 'Ubiquity\\utils\\FsUtils',
  'RestServer' => 'Ubiquity\\controllers\\rest\\RestServer',
  'View' => 'Ubiquity\\views\\View',
  'DocParser' => 'Ubiquity\\annotations\\parser\\DocParser',
  'HtmlMessage' => 'Ajax\\semantic\\html\\collections\\HtmlMessage',
  'UbiquityException' => 'Ubiquity\\exceptions\\UbiquityException',
  'Constants' => 'Ubiquity\\controllers\\admin\\utils\\Constants',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\RestTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\RestTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'View', 'name' => 'view'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'Ubiquity\\controllers\\admin\\traits\\RestTrait::showSimpleMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'content'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'type'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'icon'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'int', 'name' => 'timeout'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'staticName'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'HtmlMessage')
  ),
);

