<?php

/**
 * Feature filter form base class.
 *
 * @package    
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFeatureFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'property_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Property'), 'add_empty' => true)),
      'detail_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Detail'), 'add_empty' => true)),
      'name'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'property_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Property'), 'column' => 'id')),
      'detail_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Detail'), 'column' => 'id')),
      'name'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('feature_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Feature';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'property_id' => 'ForeignKey',
      'detail_id'   => 'ForeignKey',
      'name'        => 'Text',
    );
  }
}
