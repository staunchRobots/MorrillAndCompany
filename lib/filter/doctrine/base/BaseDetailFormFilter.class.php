<?php

/**
 * Detail filter form base class.
 *
 * @package    
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'property_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => true)),
      'name'         => new sfWidgetFormFilterInput(),
      'description'  => new sfWidgetFormFilterInput(),
      'movie_url'    => new sfWidgetFormFilterInput(),
      'has_photos'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'has_features' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'property_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Property'), 'column' => 'id')),
      'name'         => new sfValidatorPass(array('required' => false)),
      'description'  => new sfValidatorPass(array('required' => false)),
      'movie_url'    => new sfValidatorPass(array('required' => false)),
      'has_photos'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'has_features' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Detail';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'property_id'  => 'ForeignKey',
      'name'         => 'Text',
      'description'  => 'Text',
      'movie_url'    => 'Text',
      'has_photos'   => 'Boolean',
      'has_features' => 'Boolean',
    );
  }
}
