<?php

/**
 * Detail form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DetailForm extends BaseDetailForm
{
	
  public function configure()
  {
  	unset($this['has_features']);
  	$this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCE();
  	$detail = $this->getObject(); 	
 	$form = new FeatureForm(null, array('detail'=>$detail)); 	
 	$this->embedFormReplicable('Features', $form, array());
  }
	
	/**
	 * Saves the object taking care of the replicable models
	 * @see sfFormSymfony
	 */
	protected function doSave($con=null)
	{		
		$detail = $this->getObject();
		foreach($this->getValues() as $k=>$v)
		{
			try {
				if($detail->get($k) == '') $detail->set($k, $v);
			} catch(Exception $e) { }
		}
		$detail->save();

		foreach($this->embeddedForms as $label=>$form)
		{
			if(!$form instanceof ReplicableFormWrapper) continue;
			$val = $this->getValue($label);
		 	$form->bind($val);
		 	
			$form->getReplicatedForm()->
				   getObject()->
				   	 setDetail($detail)->
			       	 setProperty($detail->getProperty());

			$form->save($this->getObject());
		}

		return $detail;
	}
  
  
}
