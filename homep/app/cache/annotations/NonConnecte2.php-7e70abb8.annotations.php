<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'JsUtils' => 'Ajax\\JsUtils',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
),
  '#traitMethodOverrides' => array (
  'controllers\\NonConnecte2' => 
  array (
  ),
),
  'controllers\\NonConnecte2::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', "/users")
  ),
);

