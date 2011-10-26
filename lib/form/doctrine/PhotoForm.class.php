<?php

/**
 * Photo form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotoForm extends BasePhotoForm
{

  public function __construct($object = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($object, $options, $CSRFSecret);
  }

  public function configure()
  {
  	parent::configure();
  	unset($this['views'],$this['position']);
	
  	$q = Doctrine::getTable('Detail')->
  			createQuery('d')->
  			where('d.has_photos = ?', true)->
  			andWhere('d.property_id = ?', $this->getObject()->getPropertyId());
  	
  	$this->widgetSchema['photo'] = new sfWidgetFormInputFileEditable(array(
  	  'file_src'   => '/uploads/'.$this->getObject()->getPhoto(),
  	  'is_image'   => true
  	));
  	$this->validatorSchema['photo'] = new sfValidatorFile(array(
      'required'   =>  $this->isNew(),
      'path'       => 'uploads/',
      'mime_types' => 'web_images',
  	));
  	$this->validatorSchema['photo_delete'] = new sfValidatorBoolean(array('required'=>false));

  	if($detail = $this->getOption('detail'))
  	{
  		unset($this['detail_id']);
  		$this->getObject()->setDetail($detail);
  		if(!$this->getOption('property'))
  		{
  			$this->setOption('property', $detail->getProperty());
  		}
  	}

  	if($property = $this->getOption('property'))
  	{
  		unset($this['property_id']);
  		$this->getObject()->setProperty($property);
  	}
  	
  	if(isset($this['detail_id']))
  	{
  		$this->widgetSchema['detail_id']->setOption('query', $q);
  		$this->validatorSchema['detail_id']->setOption('query', $q);
  	}
  	
  }

}
