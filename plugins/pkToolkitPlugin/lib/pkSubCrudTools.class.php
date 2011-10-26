<?php

class pkSubCrudTools
{ 
  static public function getFormClass($model, $subtype)
  {
    return $model . ucfirst($subtype) . 'Form';
  }
}

