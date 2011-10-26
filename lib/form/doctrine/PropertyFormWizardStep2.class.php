<?php

/**
 * Property form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PropertyFormWizardStep2 extends PropertyForm
{

  public function configure()
  {
  	parent::configure();
  	$this->useFields(array());
  	
  	$f = new DetailForm();
  	$f->getObject()->setProperty($this->getObject());
  	$this->embedFormReplicable('Detail', $f, $this->getObject()->getDetails());
  }

}
