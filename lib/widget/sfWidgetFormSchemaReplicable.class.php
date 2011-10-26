<?php

class sfWidgetFormSchemaReplicable extends sfWidgetFormSchema
{
	
	protected
	  $replicatedForm = null;
	
  /**
   * Constructor.
   *
   * The first argument must be:
   *
   *  * an array of sfForm to replicate
   *
   * Available options:
   *
   *  * name_format:    The sprintf pattern to use for input names
   *  * form_formatter: The form formatter name (table and list are bundled)
   *
   * @param mixed $form     Initial fields
   * @param array $options    An array of options
   * @param array $attributes An array of default HTML attributes
   * @param array $labels     An array of HTML labels
   * @param array $helps      An array of help texts
   *
   * @throws InvalidArgumentException when the passed fields not null or array
   *
   * @see sfWidgetForm
   */
  public function __construct(sfForm $form, $options = array(), $attributes = array(), $labels = array(), $helps = array())
  {
  	$this->replicatedForm = $form;
  	parent::__construct(null, $options, $attributes, $labels, $helps);
  }
	
	public function render($name, $values = array(), $attributes = array(), $errors = array())
	{
		if($values == null) $values = array();
		$name = str_replace(array('[[',']]'), array('[',']'), $name);

		$form = $this->replicatedForm;
		$form->getWidgetSchema()->setFormFormatterName('list');

		$emptyForm = clone $form;
		$emptyForm->setDefaults(array());
		$emptyForm->getWidgetSchema()->setNameFormat($name.'[*js*][%s]');
		$replicate_pattern = '<ul>'.htmlentities($emptyForm.'').'</ul>';

		$initialForms = array();
		foreach($values as $k=>$v)
		{
		  $tmpForm = clone $form;

		  if(is_object($v)) {
		  	$tmpForm->setObject($v);
		  	$v = $v->toArray();
		  }

		  $tmpForm->getWidgetSchema()->setNameFormat($name.'['.$k.'][%s]');

		  $desiredFields = array_keys($tmpForm->getWidgetSchema()->getFields());
		  $availableFields = array_keys($v);
		  $unnecessaryFields = array_diff($availableFields, $desiredFields);
		  foreach($unnecessaryFields as $k) unset($v[$k]);

		  if(get_class($tmpForm) == 'DetailForm' && !isset($v['Features']) && $v['id'])
		  {
		  	$fet = Doctrine::getTable('Feature')->findByDetailId($v['id']);

	  	 	$v['Features'] = array('replicated'=>array());
		  	foreach($fet as $d) $v['Features']['replicated'][] = $d->toArray();
		  }

		  $tmpForm->setDefaults($v, array());
		  $initialForms[] = $tmpForm;
		}
		
		if(!count($initialForms))
		{
			$emptyForm = clone $form;
			$emptyForm->setDefaults(array());
			$emptyForm->getWidgetSchema()->setNameFormat($name.'[0][%s]');
			$initialForms[] = $emptyForm;
		}

		$rendered = '';
		foreach($initialForms as $k=>&$f) {
		
			$rendered .= '<li><ul>';
			$rendered .= $f;
			$rendered .= '</ul>';
			if($k > 0) $rendered .= '<input type="button" value="Delete" class="kamikaze"/>';
			$rendered .= '</li>';
		}
		
		$id = uniqid();
		$output = <<<OUTPUT
		<div class="replicableSchema">
			<input type="button" value="Add!" class="replicate" />
			<ul class="replicableForms">
				$rendered
			</ul>
			<input id="$id" type="hidden" value="$replicate_pattern" name="replicate_pattern" />
		</div>
OUTPUT;
		return $output;
	}
	
}
