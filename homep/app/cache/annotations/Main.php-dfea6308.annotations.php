<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'RequestUtils' => 'Ubiquity\\utils\\RequestUtils',
),
  '#traitMethodOverrides' => array (
  'controllers\\Main' => 
  array (
  ),
),
);

