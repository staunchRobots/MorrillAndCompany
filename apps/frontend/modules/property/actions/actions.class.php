<?php

/**
 * property actions.
 *
 * @package    
 * @subpackage property
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class propertyActions extends sfActions
{

  public function executeIndex(sfWebRequest $request)
  {
  	$this->property = Doctrine::getTable('Property')->
  								createQuery('q')->
  								orderBy('q.id')->
  								execute()->
  								getFirst();
  	$this->activeType = null;
  	if($type = $this->getRequest()->getParameter('type'))
  	{
  		$this->activeType = $type;
  	}
  }

  public function executeShow(sfWebRequest $request)
  {
  	$this->property = ($id = $request->getParameter('id'))
  							? $this->getRoute()->getObject()
  							: Doctrine::getTable('Property')->
  										createQuery('q')->
  										orderBy('q.id')->
  										execute()->
  										getFirst();
  }
  
  public function executeList(sfWebRequest $request)
  {
  }
  
  public function executeMail(sfWebRequest $request)
  {
    mail('todd@wildcatwebdev.com', 'message', $request->getParameter('email')."\n".$request->getParameter('phone')."\n".$request->getParameter('message'));
    mail('kontakt@azielinski.info', 'message', $request->getParameter('email')."\n".$request->getParameter('phone')."\n".$request->getParameter('message'));
    $this->redirect('@show?id='.$request->getParameter('id'));
  }
  
}
