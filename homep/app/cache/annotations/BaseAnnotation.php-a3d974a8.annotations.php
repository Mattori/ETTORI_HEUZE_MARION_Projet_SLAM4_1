<?php

return array(
  '#namespace' => 'Ubiquity\\annotations',
  '#uses' => array (
  'Annotations' => 'mindplay\\annotations\\Annotations',
  'JArray' => 'Ubiquity\\utils\\JArray',
  'Annotation' => 'mindplay\\annotations\\Annotation',
  'ClassUtils' => 'Ubiquity\\cache\\ClassUtils',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\annotations\\BaseAnnotation' => 
  array (
  ),
),
  'Ubiquity\\annotations\\BaseAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'property'=>true, 'inherited'=>true)
  ),
);

