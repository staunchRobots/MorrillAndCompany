<?php

/**
 * Property form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PropertyFormWizardStep3 extends PropertyForm
{

  public function configure()
  {
  	parent::configure();
  	$this->useFields(array());
  	
  	$prop = $this->getObject();
  	$details = $prop->getDetails();
  	foreach($details as $k=>$detail)
  	{
  	  $form = new FeatureForm(null, array('detail'=>$detail));
	  $this->embedFormReplicable('Detail: '.$detail->getName(), $form, $detail->getFeatures());
  	}
  }
	
}
