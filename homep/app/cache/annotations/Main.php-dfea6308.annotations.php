<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
),
  '#traitMethodOverrides' => array (
  'controllers\\Main' => 
  array (
  ),
),
);

