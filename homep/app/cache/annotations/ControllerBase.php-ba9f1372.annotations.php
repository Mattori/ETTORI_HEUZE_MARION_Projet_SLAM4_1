<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
  'Controller' => 'Ubiquity\\controllers\\Controller',
),
  '#traitMethodOverrides' => array (
  'controllers\\ControllerBase' => 
  array (
  ),
),
);

