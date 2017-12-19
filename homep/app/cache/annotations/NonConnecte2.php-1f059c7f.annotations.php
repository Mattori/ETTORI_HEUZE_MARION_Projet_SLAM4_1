<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
  'DAO' => 'Ubiquity\\orm\\DAO',
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

