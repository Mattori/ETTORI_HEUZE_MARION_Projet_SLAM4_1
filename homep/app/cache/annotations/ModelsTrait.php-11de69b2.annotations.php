<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'OrmUtils' => 'Ubiquity\\orm\\OrmUtils',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'Reflexion' => 'Ubiquity\\orm\\parser\\Reflexion',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'JString' => 'Ajax\\service\\JString',
  'HtmlHeader' => 'Ajax\\semantic\\html\\elements\\HtmlHeader',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'HtmlCheckbox' => 'Ajax\\semantic\\html\\modules\\checkbox\\HtmlCheckbox',
  'DbCache' => 'Ubiquity\\cache\\database\\DbCache',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\ModelsTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\ModelsTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

