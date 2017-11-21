<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'JsUtils' => 'Ajax\\JsUtils',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
),
  '#traitMethodOverrides' => array (
  'controllers\\NonConnecte2' => 
  array (
  ),
),
  'controllers\\NonConnecte2::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/users")
  ),
);

