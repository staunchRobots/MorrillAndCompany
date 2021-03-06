<?php

/**
 * Detail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    cms
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Detail extends BaseDetail
{
	
	public function getPhotos()
	{
		return Doctrine::getTable('Photo')->
					createQuery('p')->
					where('p.detail_id = ?', $this->getId())->
					orderBy('p.position')->
					execute();
	}
	
}
