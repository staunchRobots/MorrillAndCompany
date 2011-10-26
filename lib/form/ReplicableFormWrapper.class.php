<?php 

/**
 * Wrapper for replicable forms
 * 
 * @author skylife
 */
class ReplicableFormWrapper extends BaseForm
{
	
	private $replicatedForm;
	
	/**
	 * If we want to get multiple replicable forms in our widgetSchema,
	 * it's good idea to do embedForm(new ReplicableFormWrapper(new DesiredForm()))
	 * as it will handle all the pain!
	 * 
	 * @param sfForm $formToReplicate
	 * @return unknown_type
	 */
	public function __construct(sfForm $replicatedForm, $defaults)
	{
		$this->replicatedForm = $replicatedForm;
		parent::__construct();
		$this->setDefault('replicated', $defaults);
	}
	
	/**
	 * Returns the form that is replicated by this cloning wrapper
	 * 
	 * @return sfForm
	 */
	public function getReplicatedForm()
	{
		return $this->replicatedForm;
	}
	
	public function getJavascripts() { return parent::getJavascripts() + $this->getReplicatedForm()->getJavascripts(); }
	public function getStylesheets() { return parent::getStylesheets() + $this->getReplicatedForm()->getStylesheets(); }
	
	public function configure()
	{
		$nameFormat = '%s';
		$this->
			setWidgets(array(
				'replicated' => new sfWidgetFormSchemaReplicable($this->replicatedForm)
			))->
			setValidators(array(
				'replicated' => new sfValidatorSchemaReplicable(array('required' => false, 'form' => $this->replicatedForm))
			))->
				widgetSchema->
					setFormFormatterName('list')->
					setLabel('replicated', '');
		
	    $this->validatorSchema->setOption('allow_extra_fields', true);
	    $this->validatorSchema->setOption('filter_extra_fields', false);
	}

	
	// Save all the form's data in one transaction - we can't risk something goes wrong
	// What is done here is all the data is bound to generated forms and saved
	public function save($object=null)
	{
		$values = $this->getValue('replicated');
		try {
			$con = Doctrine_Manager::connection();
			$con->beginTransaction();
			
			$formClass = get_class($this->replicatedForm);
			$initialObject = $this->replicatedForm->getObject();

			$relatedModelName = get_class($initialObject);
			$related = $object->
				   {sprintf('get%ss', $relatedModelName)}();

			$list = array(); foreach($related as $obj) { $list[] = $obj; }

			foreach($values as $valueSet)
			{
				foreach($list as $k=>$obj) if($valueSet['id'] == $obj->getId()) unset($list[$k]);

				$dbObj = null;
				if($valueSet['id']) { $dbObj = Doctrine::getTable($relatedModelName)->findOneById($valueSet['id']); }
				
				// Check if this form was hydrated with data
				$empty = true;
				foreach($valueSet as $v) if($v){ $empty = false; break; }
				
				if($empty) continue;
				
				
				$tmpForm = new $formClass($dbObj, $this->getReplicatedForm()->getOptions());
				$tmpForm->removeRelationField($object);

				foreach($initialObject->toArray() as $k=>$v)
				{
					try {
						# if(!isset($valueSet[$k]) || !$valueSet[$k])
						$tmpForm->getObject()->set($k, $v);
					} catch(Exception $e) { }
				}
				
				$tmpForm->bind($valueSet, array());
				$tmpForm->save();

			}
			// die();
			foreach($list as $obj) $obj->delete();
			
			$con->commit();
		} catch(Exception $e) {
			$con->rollback();
			throw $e;
		}
		return true;
	}
}
