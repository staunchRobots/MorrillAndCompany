<?php

/**
 * Property filter form base class.
 *
 * @package    
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePropertyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'description'    => new sfWidgetFormFilterInput(),
      'street'         => new sfWidgetFormFilterInput(),
      'city'           => new sfWidgetFormFilterInput(),
      'postal_code'    => new sfWidgetFormFilterInput(),
      'state'          => new sfWidgetFormFilterInput(),
      'tagline'        => new sfWidgetFormFilterInput(),
      'price'          => new sfWidgetFormFilterInput(),
      'bedrooms'       => new sfWidgetFormFilterInput(),
      'bathrooms'      => new sfWidgetFormFilterInput(),
      'type'           => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'full_bathrooms' => new sfWidgetFormFilterInput(),
      'half_bathrooms' => new sfWidgetFormFilterInput(),
      'garage_spaces'  => new sfWidgetFormFilterInput(),
      'lots_size'      => new sfWidgetFormFilterInput(),
      'square_footage' => new sfWidgetFormFilterInput(),
      'have_see_more'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'user_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'description'    => new sfValidatorPass(array('required' => false)),
      'street'         => new sfValidatorPass(array('required' => false)),
      'city'           => new sfValidatorPass(array('required' => false)),
      'postal_code'    => new sfValidatorPass(array('required' => false)),
      'state'          => new sfValidatorPass(array('required' => false)),
      'tagline'        => new sfValidatorPass(array('required' => false)),
      'price'          => new sfValidatorPass(array('required' => false)),
      'bedrooms'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bathrooms'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'           => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorPass(array('required' => false)),
      'full_bathrooms' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'half_bathrooms' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'garage_spaces'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lots_size'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'square_footage' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'have_see_more'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('property_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Property';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'user_id'        => 'ForeignKey',
      'description'    => 'Text',
      'street'         => 'Text',
      'city'           => 'Text',
      'postal_code'    => 'Text',
      'state'          => 'Text',
      'tagline'        => 'Text',
      'price'          => 'Text',
      'bedrooms'       => 'Number',
      'bathrooms'      => 'Number',
      'type'           => 'Text',
      'status'         => 'Text',
      'full_bathrooms' => 'Number',
      'half_bathrooms' => 'Number',
      'garage_spaces'  => 'Number',
      'lots_size'      => 'Number',
      'square_footage' => 'Number',
      'have_see_more'  => 'Boolean',
    );
  }
}
