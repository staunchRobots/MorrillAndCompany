<?php

/**
 * Feature form base class.
 *
 * @method Feature getObject() Returns the current form's model object
 *
 * @package    
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFeatureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'property_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => false)),
      'detail_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'), 'add_empty' => false)),
      'name'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'property_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Property'))),
      'detail_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'))),
      'name'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('feature[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Feature';
  }

}
