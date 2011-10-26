<?php

class propertyComponents extends sfComponents
{
	
	public function executeList(sfWebRequest $request)
	{
	  	$this->pager = new sfDoctrinePager('Property', 10);
	  	$this->pager->setPage($request->getParameter('page'));
	
	  	$this->activeType = null;
	  	if(($type = $this->getRequest()->getParameter('type')) && $type != 'All')
	  	{
	  		$this->activeType = $type;
		  	$pagerQuery = Doctrine::getTable('Property')->
		  							createQuery('q')->
		  							where('q.type like ?', $this->activeType);
	  		$this->pager->setQuery($pagerQuery);
	  	}
	
	  	$this->pager->init();
	}
	
}
