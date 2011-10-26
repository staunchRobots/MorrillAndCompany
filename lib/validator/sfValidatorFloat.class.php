<?php

/**
 * Validator liczb zmiennoprzecinkowych zezwalający na używanie przecinka
 * zamiast kropki, oraz na kontrolę ilości miejsc po przecinku
 * 
 * @author skylife
 */
class sfValidatorFloat extends sfValidatorNumber
{
	
  /**
   * @see sfValidatorBase
   * Opcje:
   * * min_decimals(int) - minimalna liczba miejsc po przecinku
   * * max_decimals(int) - maksymalna liczba miejsc po przecinku
   */
  public function configure($options=array(), $messages=array())
  {
  	parent::configure($options, $messages);
  	$this->addOption('min_decimals');
  	$this->addOption('max_decimals');
  }
	
  /**
   * @see sfValidatorBase
   */
  public function clean($value)
  {
    return $this->doClean($value);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $value = parent::doClean(str_replace(',','',$value));
    $decimals = strpos($value.'', '.') ? strlen(substr($value, strpos($value.'', '.')+1)) : 0;
    
    if(($minDec = $this->getOption('min_decimals')) && $decimals < $minDec)
    {
   	  throw new sfValidatorError($this, 'min_decimals', array('value' => $value, 'min_decimals' => $minDec));
    }
    
    if(($maxDec = $this->getOption('max_decimals')) && $decimals > $maxDec)
    {
   	  throw new sfValidatorError($this, 'max_decimals', array('value' => $value, 'max_decimals' => $maxDec));
    }
    return $value;
  }
}
