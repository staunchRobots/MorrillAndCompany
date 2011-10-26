<?php

/**
 * Photo filter form base class.
 *
 * @package    
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePhotoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'detail_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'), 'add_empty' => true)),
      'property_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => true)),
      'is_main_photo' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'views'         => new sfWidgetFormFilterInput(),
      'photo'         => new sfWidgetFormFilterInput(),
      'position'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'detail_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Detail'), 'column' => 'id')),
      'property_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Property'), 'column' => 'id')),
      'is_main_photo' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'views'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'photo'         => new sfValidatorPass(array('required' => false)),
      'position'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'detail_id'     => 'ForeignKey',
      'property_id'   => 'ForeignKey',
      'is_main_photo' => 'Boolean',
      'views'         => 'Number',
      'photo'         => 'Text',
      'position'      => 'Number',
    );
  }
}
