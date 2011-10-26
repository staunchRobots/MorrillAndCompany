<?php

/**
 * Feature form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureForm extends BaseFeatureForm
{

  public function configure()
  {
  	if($detail = $this->getOption('detail'))
  	{  		
  		unset($this['detail_id']);
  		$this->getObject()->setDetail($detail);
  		
  		$this->setOption('property', $detail->getProperty());
  	}
  	
  	if($property = $this->getOption('property'))
  	{  		
  	  	unset($this['property_id']);
  		$this->getObject()->setProperty($property);
  	}	
  }
  
}
