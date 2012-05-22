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
  	$this->properties = Doctrine::getTable('Property')->createQuery('q')->orderBy('q.id')->execute();
  }
  
  public function executeList(sfWebRequest $request)
  {
  }
  
  public function executeMail(sfWebRequest $request)
  {
    $e = new QuoteRequest();
    $e->setEmail($request->getParameter('email'))->
        setContents($request->getParameter('message'))->
	setPhone($request->getParameter('phone'))->
	save()
    ;
  
    mail('todd@toddmorrill.com', 'A new quote request from morrillandco.com', $request->getParameter('email')."\n".$request->getParameter('phone')."\n".$request->getParameter('message'));
    mail('tmorrill@mris.com',    'A new quote request from morrillandco.com', $request->getParameter('email')."\n".$request->getParameter('phone')."\n".$request->getParameter('message'));

    /*
    The code below is not working becuase of some server/symfony/config issues I dont have a time to deal with, but
    How it should look like:

    $message = $this->getMailer()->compose(
      array('quotes@morrillandcompany.com' => 'Morrill And Company website'),
      array('todd@toddmorrill.com',
	    'tmorrill@mris.com'),
      'A new quote request from morrillandco.com',
      $request->getParameter('email')."\n".$request->getParameter('phone')."\n".$request->getParameter('message')
    );
    
    $this->getMailer()->send($message);
    */

    if($request->getParameter('id') == -1)
      $this->redirect('/');
    else
      $this->redirect('@show?id='.$request->getParameter('id'));
  }
  
}
