<?php

/**
 * Photo form base class.
 *
 * @method Photo getObject() Returns the current form's model object
 *
 * @package    
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePhotoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'detail_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'), 'add_empty' => false)),
      'property_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => false)),
      'is_main_photo' => new sfWidgetFormInputCheckbox(),
      'views'         => new sfWidgetFormInputText(),
      'photo'         => new sfWidgetFormInputText(),
      'position'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'detail_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'))),
      'property_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Property'))),
      'is_main_photo' => new sfValidatorBoolean(array('required' => false)),
      'views'         => new sfValidatorInteger(array('required' => false)),
      'photo'         => new sfValidatorPass(array('required' => false)),
      'position'      => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Photo', 'column' => array('position', 'detail_id')))
    );

    $this->widgetSchema->setNameFormat('photo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }

}
