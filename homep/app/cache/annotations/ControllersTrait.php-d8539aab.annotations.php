<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'View' => 'Ubiquity\\views\\View',
  'FsUtils' => 'Ubiquity\\utils\\FsUtils',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'ClassUtils' => 'Ubiquity\\cache\\ClassUtils',
  'Introspection' => 'Ubiquity\\utils\\Introspection',
  'CodeUtils' => 'Ubiquity\\controllers\\admin\\utils\\CodeUtils',
  'Constants' => 'Ubiquity\\controllers\\admin\\utils\\Constants',
  'Rule' => 'Ajax\\semantic\\components\\validation\\Rule',
  'Router' => 'Ubiquity\\controllers\\Router',
  'StrUtils' => 'Ubiquity\\utils\\StrUtils',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\ControllersTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\ControllersTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'View', 'name' => 'view')
  ),
);

