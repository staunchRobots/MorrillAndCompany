<?php

/**
 * Property form base class.
 *
 * @method Property getObject() Returns the current form's model object
 *
 * @package    
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePropertyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'description'    => new sfWidgetFormInputText(),
      'street'         => new sfWidgetFormInputText(),
      'city'           => new sfWidgetFormInputText(),
      'postal_code'    => new sfWidgetFormInputText(),
      'state'          => new sfWidgetFormInputText(),
      'tagline'        => new sfWidgetFormInputText(),
      'price'          => new sfWidgetFormInputText(),
      'bedrooms'       => new sfWidgetFormInputText(),
      'bathrooms'      => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'full_bathrooms' => new sfWidgetFormInputText(),
      'half_bathrooms' => new sfWidgetFormInputText(),
      'garage_spaces'  => new sfWidgetFormInputText(),
      'lots_size'      => new sfWidgetFormInputText(),
      'square_footage' => new sfWidgetFormInputText(),
      'have_see_more'  => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'description'    => new sfValidatorPass(array('required' => false)),
      'street'         => new sfValidatorPass(array('required' => false)),
      'city'           => new sfValidatorPass(array('required' => false)),
      'postal_code'    => new sfValidatorPass(array('required' => false)),
      'state'          => new sfValidatorPass(array('required' => false)),
      'tagline'        => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorPass(array('required' => false)),
      'bedrooms'       => new sfValidatorInteger(array('required' => false)),
      'bathrooms'      => new sfValidatorInteger(array('required' => false)),
      'type'           => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorPass(array('required' => false)),
      'full_bathrooms' => new sfValidatorInteger(array('required' => false)),
      'half_bathrooms' => new sfValidatorInteger(array('required' => false)),
      'garage_spaces'  => new sfValidatorInteger(array('required' => false)),
      'lots_size'      => new sfValidatorInteger(array('required' => false)),
      'square_footage' => new sfValidatorInteger(array('required' => false)),
      'have_see_more'  => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('property[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Property';
  }

}
