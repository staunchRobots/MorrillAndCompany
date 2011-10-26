<?php

/**
 * Base project form.
 * 
 * @package    cms
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
	
	public function setObject($v) { $this->object = $v; }
	/**
	 * Method that makes unnecessary providing related object ID,
	 * and is letting to do that by passing related object as an option
	 * 
	 * @param string $object Appropriate object
	 * @param string $modelName Name of the model
	 * @throws RuntimeException
	 * @return void
	 */
	public function removeRelationField($object)
	{
		$modelName = get_class($object);
	  	if($this->getObject()->isNew()) {
	  		if( !$object ) { return; }
	  		$this->getObject()->{'set'.$modelName}($object);
	  	}
	  	
	  	$fieldName = sfInflector::underscore($modelName).'_id';
	  	unset($this[$fieldName]);
	}
	
	/**
	 * Log form errors into current's application log
	 * 
	 * @param sfEvent $event
	 * @return void
	 */
	public static function listenToValidationError($event)
	{
		foreach ($event['error'] as $key => $error)
		{
			self::getEventDispatcher()->notify(new sfEvent(
				$event->getSubject(),
				'application.log',
				array (
					'priority' => sfLogger::NOTICE,
					sprintf('Validation Error: %s: %s', $key, (string) $error)
				)
			));
		}
	}
	
	/**
	 * Performs configure actions which intersects on all of the forms
	 * 
	 * @param sfEvent $event The event which caused execution of this method
	 * @return void
	 */
	static public function postConfigure(sfEvent $event)
	{
		$form = $event->getSubject();
		$form->disableLocalCSRFProtection();

		unset (
			$form['_csrf_token'],
			$form['created_at'],
			$form['updated_at']
//			$form['slug']
		);


		$form->widgetSchema->setFormFormatterName('list');
//		$form->widgetSchema->setNameFormat(str_replace('_form', '', sfInflector::underscore(get_class($form))).'[%s]');
	}

	/**
	 * Allows to embed one form with multiple nested forms embedded, which are easy to replicate
	 * 
	 * @param string $label
	 * @param sfForm $form
	 * @param array $initialValues
	 * @return void
	 */
	protected function embedFormReplicable($label, $form, $initialValues)
	{
		/*
		$holderForm = new BaseForm();
		
		if(!count($initialValues)) {
			$initialValues[] = array();
		}
		
		foreach($initialValues as $k=>$values) {
			if(is_object($values)) $values = $values->toArray();
			$currentlyEmbeddedForm = clone $form;
			$currentlyEmbeddedForm->bind($values);
			$holderForm->embedForm(sprintf('%d', $label, $k), $form);
		}
		
		$this->embedForm($label, $holderForm);
		*/
		$o = $this->getObject();
		$form->removeRelationField($o);
		$this->hackEmbed($label, new ReplicableFormWrapper($form, $initialValues));
	}
	
	private function hackEmbed($name, sfForm $form, $decorator = null)
	{
    	$name = (string) $name;
	
	    $this->embeddedForms[$name] = $form;
	
	    $form = clone $form;
	    unset($form[self::$CSRFFieldName]);
	
	    $widgetSchema = $form->getWidgetSchema();
		
	    $this->setDefault($name, $form->getDefaults());

	    $decorator = null === $decorator ? $widgetSchema->getFormFormatter()->getDecoratorFormat() : $decorator;
	
	    $this->widgetSchema[$name] = new sfWidgetFormSchemaDecorator($widgetSchema, $decorator);
	    $this->validatorSchema[$name] = $form->getValidatorSchema();

	
	    $this->resetFormFields();
	}

	public function getErrors()
	{
	   $errors = array();
	 
	   // individual widget errors
	   foreach ($this as $form_field)
	   {   
	     if ($form_field->hasError())
	     {   
	       $error_obj = $form_field->getError();
	       if ($error_obj instanceof sfValidatorErrorSchema)
	       {   
	         foreach ($error_obj->getErrors() as $error)
	         {   
	           // if a field has more than 1 error, it'll be over-written
	           $errors[$form_field->getName()] = $error->getMessage();
	         }   
	       }   
	       else
	       {   
	         $errors[$form_field->getName()] = $error_obj->getMessage();
	       }   
	     }   
	   }   
	 
	   // global errors
	   foreach ($this->getGlobalErrors() as $validator_error)
	   {   
	     $errors[] = $validator_error->getMessage();
	   }   
	 
	   return $errors;
	}

	public function getJavascripts() {
		$jses = parent::getJavascripts();
		foreach($this->getWidgetSchema()->getFields() as $w) $jses += $w->getJavascripts();
		foreach($this->getEmbeddedForms() as $f) 			 $jses += $f->getJavascripts(); 
		return $jses;
	}
	
	public function getStylesheets() {
		$csses = parent::getStylesheets();
		foreach($this->getWidgetSchema() as $w)  $csses += $w->getStylesheets();
		foreach($this->getEmbeddedForms() as $f) $csses += $f->getStylesheets();
		return $csses;
	}
	
}
