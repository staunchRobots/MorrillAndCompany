<?php

/**
 * Property form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PropertyFormWizardStep5 extends PropertyForm
{

  public function configure()
  {
  	parent::configure();
  	$this->useFields(array());
  	$prop = $this->getObject();
  	$details = $prop->getDetails();
  	foreach($details as $k=>$detail)
  	{
  	  if(!$detail->getHasPhotos()) continue;
  	  $form = new BaseForm();
  	  foreach($detail->getPhotos() as $photo)
  	  {
  	  	$photoForm = new PhotoForm($photo);
  	  	unset($photoForm['property_id']);
  	  	$photoForm->getWidgetSchema()->setNameFormat('photo[%s]');
  	  	$form->embedForm($photo->getId(), $photoForm);
  	  }
  	  $this->embedForm($detail->getId(), $form);
  	  
  	  $this->widgetSchema[$detail->getId()]["order"] = new sfWidgetFormInputHidden();
  	  $this->validatorSchema[$detail->getId()]["order"] = new sfValidatorPass();
  	}
  }

  public function save($con=null)
  {
  	$c = Doctrine_Manager::connection();
        $hasMainPhoto = false;
	$propertyId = $this->getObject()->getId();
	foreach($this->getValues() as $detailId=>$photos)
  	{
  		foreach($photos as $photoId=>$photo)
  		{
  			$obj = Doctrine::getTable('Photo')->findOneById($photoId);
  			if(!$obj) continue;

  			if($photo['photo_delete'])
  			{
  				$obj->delete();
  				continue;
  			}

  			if($photo['detail_id'] != $obj->getDetailId())
  			{
#				(var_dump($photo));
				$obj->setDetailId($photo['detail_id']);
				$obj->save();
  			}

  			if($photo['photo'] instanceof sfValidatedFile)
  			{
  				$newName = md5(time().rand()).'.jpg';

  				$p = $photo['photo'];
  				$p->save(sfConfig::get('sf_upload_dir').'/'.$newName);

  				$obj->setPhoto($newName)->save();
  			}
  			
  			if($photo['is_main_photo'] && !$hasMainPhoto)
  			{
					$c->
					  prepare('UPDATE photo SET is_main_photo=false WHERE detail_id='.$detailId)->
					  execute();
					  
					$c->
					  prepare('UPDATE photo SET is_main_photo=true WHERE id='.$photoId)->
					  execute();
				    $hasMainPhoto = true;
  			}
  		}
  		
  		$order = $photos['order'];
  		if($order)
  		{
  		  $order = explode('&',$order);
  		  $i = 0;

			  $c->
			    prepare('UPDATE photo SET position=position+1000 WHERE detail_id='.$detailId)->
			    execute();
				  
  			  foreach($order as $k=>$v)
  			  {
  			  	if(!strstr($v,'=')) continue;
  			  	$myId = substr($v, strpos($v, '=')+1);
  			  	if(!($myId = (int)$myId)) continue;

  			  	$photo = Doctrine::getTable('Photo')->findOneById($myId);
				if($photo) $photo->setPosition(++$i)->save();
  			  }

  		}
	}  		

 	if(!$hasMainPhoto)
 	{
 		$photo = Doctrine::getTable('Photo')->findOneByPropertyId($propertyId);
 		if($photo)
 		{
 			$photo->
 				setIsMainPhoto(true)->
 				save();
 		}
 	}
  	return $this;
  }

}
