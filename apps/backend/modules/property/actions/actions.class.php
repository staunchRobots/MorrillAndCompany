<?php

require_once dirname(__FILE__).'/../lib/propertyGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/propertyGeneratorHelper.class.php';

/**
 * property actions.
 *
 * @package    cms
 * @subpackage property
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class propertyActions extends autoPropertyActions
{
	
	public function executeImportSet(sfWebRequest $r)
	{
		$url = $r->getParameter('url');
		$url = str_replace(array('_colon_','_slash_'), array(':','/'), $url);

		$c = file_get_contents($url);
		$c = substr($c, strpos($c, '<div id="setThumbs" class="clearfix">'));
		$c = substr($c, 0, strpos($c, '<br clear="all"'));
		
		$matches = array();
		preg_match_all('#src="(.*?)"#', $c, $matches);
		
		die(json_encode($matches[1]));

		$setId = $r->getParameter('id');
		$flickr = new phpFlickr('f1710e4730545a958f505435f67e6a70', '6fa0744090de04ba');
		$photos = $flickr->request('flickr.photosets.getPhotos', array('photoset_id'=>$setId));
		$photos = unserialize($photos);
		$photos = $photos['photoset'];
		
		$url = 'http://www.flickr.com/photos/'.$photos['owner'].'/sets/'.$photos['id'].'/';

		$c = file_get_contents($url);
		$c = substr($c, strpos($c, '<div id="setThumbs" class="clearfix">'));
		$c = substr($c, 0, strpos($c, '<br clear="all"'));
		die($c);
		die(print_r($url));
		$photos = $photos['photoset']['photo'];
		
		$files = array();
		foreach($photos as $photo)
		{
			$file = $flickr->request('flickr.photos.getInfo', array('photo_id'=>$photo['id']));
			$file = unserialize($file);
			$files[] = 'http://farm2.static.flickr.com/'.$file['server'].'/'.$file['id'].'/'.$file['secret'].'_s.jpg';
		}
		return json_encode($file);
	}

	public function executeNew(sfWebRequest $r)
	{
		$this->redirect('@property_wizard?step=1&id=fake');
	}
	
	public function executeEdit(sfWebRequest $r)
	{
		$this->redirect('@property_wizard?step=1&id='.$r->getParameter('id'));
	}
	
	/**
	 * Stadium edition, multi-step wizard
	 * 
	 * @return void
	 */
	public function executeWizard(sfWebRequest $r)
	{
		$this->step = min(5, max(1, $r->getParameter('step', 1)));
		$this->{'processStep'.$this->step}($r);
	}
	
	/**
	 * Stadium edition, Step 1 - Data
	 * 
	 * @return void
	 */
	private function processStep1(sfWebRequest $r)
	{
		$this->property = Doctrine::getTable('Property')->findOneById($r->getParameter('id'));
		$this->form = new PropertyFormWizardStep1($this->property, array('user_id'=>$this->getUser()->getGuardUser()->getId()));
		if($s = $this->processStepForm($r, $this->form))
		{
			$this->redirect(sprintf('@property_wizard?step=2&id=%d', $s->getId()));
		}
	}
	
	/**
	 * Stadium edition, Step 2 - Details
	 * 
	 * @return void
	 */
	private function processStep2(sfWebRequest $r)
	{
		$this->property = Doctrine::getTable('Property')->findOneById($r->getParameter('id'));
		$this->form = new PropertyFormWizardStep2($this->property, array('user_id'=>$this->getUser()->getGuardUser()->getId()));
		if($s = $this->processStepForm($r, $this->form))
		{
			$this->redirect(sprintf('@property_wizard?step=4&id=%d', $s->getId()));
		}
	}
	
	/**
	 * Stadium edition, Step 3 - Features
	 * 
	 * @return void
	 
	private function processStep3(sfWebRequest $r)
	{
		$this->property = Doctrine::getTable('Property')->findOneById($r->getParameter('id'));
		$this->form = new PropertyFormWizardStep3($this->property, array('user_id'=>$this->getUser()->getGuardUser()->getId()));
		if($s = $this->processStepForm($r, $this->form))
		{
			$this->redirect(sprintf('@property_wizard?step=4&id=%d', $s->getId()));
		}
	}
	*/
	
	/**
	 * Stadium edition, Step 4 - Photos upload
	 * 
	 * @return void
	 */
	private function processStep4(sfWebRequest $r)
	{
		$this->property = Doctrine::getTable('Property')->findOneById($r->getParameter('id'));
		$this->form = new PropertyFormWizardStep4($this->property, array('user_id'=>$this->getUser()->getGuardUser()->getId()));
		if($s = $this->processStepForm($r, $this->form))
		{
			$this->redirect(sprintf('@property_wizard?step=5&id=%d', $s->getId()));
		}
	}

	/**
	 * Stadium edition, Step 5 - Photos management
	 * 
	 * @return void
	 */
	private function processStep5(sfWebRequest $r)
	{
		$this->property = Doctrine::getTable('Property')->findOneById($r->getParameter('id'));
		$this->form = new PropertyFormWizardStep5($this->property, array('user_id'=>$this->getUser()->getGuardUser()->getId()));
		if($s = $this->processStepForm($r, $this->form))
		{
			$this->getUser()->setFlash('notice', 'This property was saved successfully!');
			$this->redirect('property/index');
		}
	}
	
	/**
	 * Processes the each step form, binds the data, validates, process the form in general
	 * 
	 * @param sfForm $form Concrete step form
	 * @param sfWebRequest $r The request object
	 * @return mixed Value returned by $form->save() on success, or false on failure
	 */
	private function processStepForm(sfWebRequest $r, sfForm $form)
	{
		// If no form were submitted, return false
		if(!$r->isMethod('post')) return false;
		
		$nameFormat = $form->getWidgetSchema()->getNameFormat();
		$nameFormat = substr($nameFormat, 0, strpos($nameFormat, '['));

		// If no data were supplied, return false
		if(!$r->hasParameter($nameFormat) && !count($r->getFiles($nameFormat))) return false;

		$form->bind($r->getParameter($nameFormat), $r->getFiles($nameFormat));
		
		// If form is not valid, return false
		if(!$form->isValid()) return false;
		try {
			return $form->save();
		} catch(Exception $e) {
			die($e->getMessage());
			return false;
		}
	}


  protected function processForm(sfWebRequest $request, sfForm $form)
  {
  	$form->setOption('user_id', $this->getUser()->getGuardUser()->getId());
  	parent::processForm($request, $form);
  }
   
}
