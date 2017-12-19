<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\admin\\traits',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'CacheManager' => 'Ubiquity\\cache\\CacheManager',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'CacheFile' => 'Ubiquity\\controllers\\admin\\popo\\CacheFile',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'HtmlForm' => 'Ajax\\semantic\\html\\collections\\form\\HtmlForm',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\admin\\traits\\CacheTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\admin\\traits\\CacheTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
);

