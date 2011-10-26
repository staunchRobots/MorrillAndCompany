<?php

/**
 * Property form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PropertyForm extends BasePropertyForm
{

	private
		$types = array(),
		$statuses = array();

  public function configure()
  {
  	$this->types = Doctrine::getTable('Property')->getTypes();
  	$this->statuses = Doctrine::getTable('Property')->getStatuses();
  	
  	$this->getObject()->setUserId($this->getOption('user_id'));

  	$this->widgetSchema['status']    = new sfWidgetFormChoice(array('choices'=>$this->types, 'expanded'=>true, 'multiple'=>true));
  	$this->validatorSchema['status'] = new sfValidatorChoice(array('required'=>false, 'choices'=>array_keys($this->types), 'multiple'=>true));

  	$this->widgetSchema['type']    = new sfWidgetFormChoice(array('choices'=>$this->statuses));
  	$this->validatorSchema['type'] = new sfValidatorChoice(array('required'=>true, 'choices'=>array_keys($this->statuses)));

  	unset($this['id'],$this['user_id'],$this['name']);
  }

  public function updateDefaultsFromObject()
  {
  	$retval = parent::updateDefaultsFromObject();

  	$matrix = array_flip($this->statuses);
  	$this->defaults['type'] = $matrix[$this->getObject()->getType()];
  	
  	$this->defaults['status'] = explode(', ', $this->getObject()->getStatus());
  	$matrix = array_flip($this->types);

  	$retval['status'] = &$this->defaults['status'];
  	foreach($this->defaults['status'] as $k=>&$v) if($v) $v = $matrix[$v]; else unset($this->defaults['type'][$k]);

  	return $retval;
  }

	/**
	 * Saves the object taking care of the replicable models
	 * @see sfFormSymfony
	 */
	protected function doSave($con=null)
	{
		$status = array();
		foreach($this->values['status'] as $pStatus) $status[] = $this->types[$pStatus];

		$this->values['type'] = $this->statuses[$this->values['type']];
		$this->values['status'] = implode(', ', $status);
		
		
		$retVal = parent::doSave($con);

		foreach($this->embeddedForms as $label=>$form)
		{
			if(!$form instanceof ReplicableFormWrapper) continue;
			$form->bind($this->getValue($label));
			$form->save($this->getObject());
		}

		return $retVal;
	}

}
