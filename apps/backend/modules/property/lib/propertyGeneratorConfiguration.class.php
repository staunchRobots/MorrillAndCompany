<?php

/**
 * property module configuration.
 *
 * @package    cms
 * @subpackage property
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class propertyGeneratorConfiguration extends BasePropertyGeneratorConfiguration
{

  public function getForm($object = null, $options = array())
  {
    $class = $this->getFormClass();
	$options['user_id'] = sfContext::getInstance()->getUser()->getGuardUser()->getId();

	if($object)
	{
		$object = Doctrine::getTable(get_class($object))->findOneById($object->getId());
	}
    $form = new $class($object, array_merge($this->getFormOptions(), $options));
    if($object)
    {
    	unset($form['id']);
    }
    return $form;
  }

}
