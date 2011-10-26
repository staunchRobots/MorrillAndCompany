<?php

/**
 * Detail form base class.
 *
 * @method Detail getObject() Returns the current form's model object
 *
 * @package    
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'property_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => false)),
      'name'         => new sfWidgetFormInputText(),
      'description'  => new sfWidgetFormInputText(),
      'movie_url'    => new sfWidgetFormInputText(),
      'has_photos'   => new sfWidgetFormInputCheckbox(),
      'has_features' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'property_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Property'))),
      'name'         => new sfValidatorPass(array('required' => false)),
      'description'  => new sfValidatorPass(array('required' => false)),
      'movie_url'    => new sfValidatorPass(array('required' => false)),
      'has_photos'   => new sfValidatorBoolean(array('required' => false)),
      'has_features' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Detail';
  }

}
