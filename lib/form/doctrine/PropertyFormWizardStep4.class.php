<?php

/**
 * Property form.
 *
 * @package    cms
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PropertyFormWizardStep4 extends PropertyForm
{

  public function configure()
  {
  	parent::configure();
  	$this->useFields(array());
  	$prop = $this->getObject();
  	$details = $prop->getDetails();
    $this->validatorSchema['detail'] = new sfValidatorPass();
  }
  
  public function save($con=null)
  {
	$a = 0;
  	foreach($this->getValue('detail') as $detailId=>$imageSet)
  	{
  		$photos = explode(',', $imageSet);
  		foreach($photos as $photoUrl)
  		{
  			$newName = md5(rand().uniqid().time()).'.jpg';
  			$url = str_replace('http://', '', $photoUrl);
  			$url = explode('/', $url);
  			$id = $url[2];
  			
  			if(strpos($id, '_')) $id = substr($id, 0, strpos($id, '_'));

			// if(!$id) { die(var_dump($id)); }
			// continue;
			$flickr = new phpFlickr('f1710e4730545a958f505435f67e6a70', '6fa0744090de04ba');
			$sizes = $flickr->request('flickr.photos.getSizes', array('photo_id'=>$id));
			$sizes = unserialize($sizes);

			$biggest = end($sizes['sizes']['size']);
			$bigUrl = $biggest['source'];

  			$photoFile = file_get_contents($bigUrl);
  			if(!strlen($photoFile)) continue;
  			
  			file_put_contents(sfConfig::get('sf_upload_dir').'/'.$newName, $photoFile);
  			$photo = new Photo();
  			$photo->setPhoto($newName)->
  					setDetailId($detailId)->
  					setPropertyId($this->getObject()->getId())->
  					save();
			if(++$a == 1) $photo->setIsMainPhoto(true)->save();
  		}
  	}
  	return $this->getObject();
  }
	
}
