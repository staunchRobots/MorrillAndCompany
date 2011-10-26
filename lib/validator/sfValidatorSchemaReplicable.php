<?php

class sfValidatorSchemaReplicable extends sfValidatorBase
{	

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addRequiredOption('form');
  }

  /**
   * For every replicated form in $values,
   * binds and validate those data to $form which is subject of this validator.
   * 
   * If at least one form is invalid, throws an exception
   * Otherwise, returns initial array
   * 
   * @return array
   */
  protected function doClean($values)
  {
  	$form = $this->getOption('form');
  	foreach($values as $k=>&$v)
  	{
  	  $tmpform = clone $form;
  	  $tmpform->bind($v, @array('name'=>$v['name']));
	  $unset = true; foreach($v as $each) if( ($each != '' && !is_array($each)) || (isset($each['error'])) ) $unset = false;
	  if($unset) { unset($values[$k]); continue; }
  	}

    return $values;
  }
  
}

