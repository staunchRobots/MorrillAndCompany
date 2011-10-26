<?php

class Doctrine_Template_Listener_DailyReport extends Doctrine_Record_Listener
{
  /**
   * Array of sortable options
   *
   * @var array
   */
  protected $_options = array();
  protected static $__options = array();


  /**
   * __construct
   *
   * @param array $options 
   * @return void
   */  
  public function __construct(array $options)
  {
    $this->_options = self::$__options = $options;
  }
  
}

