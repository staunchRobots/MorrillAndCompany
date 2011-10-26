<?php


/**
 * Easily attach photos for each object
 *
 * @package     csDoctrineSortablePlugin
 * @subpackage  listener
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Travis Black <tblack@centresource.com>
 */
class Doctrine_Template_Listener_Photoable extends Doctrine_Record_Listener
{
  /**
   * Array of sortable options
   *
   * @var array
   */
  protected $_options = array();
  protected static $__options = array();


  /**
   * __construct
   *
   * @param array $options 
   * @return void
   */  
  public function __construct(array $options)
  {
    $this->_options = self::$__options = $options;
  }

  /**
   * Process thumbnails
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preSave(Doctrine_Event $event)
  {
    $fieldName = $this->_options['field'];
    $object = $event->getInvoker();
	$this->processImage($object, $object->$fieldName);
  }

  /**
   * When a sortable object is deleted, promote all objects positioned lower than itself
   *
   * @param string $Doctrine_Event 
   * @return void
   */  
  public function postDelete(Doctrine_Event $event)
  {
    $fieldName = $this->_options['field'];
    $object = $event->getInvoker();
    $photo = $object->$fieldName;
    $path = sfConfig::get('sf_upload_dir').'/'.$photo;
    if(file_exists($path)) unlink($path);
  }

	private function processImage($invoker, $onlyThumb=false)
	{
		$i = $invoker;
		if(!self::$__options['maxWidth'] && !self::$__options['maxHeight']) return
		
	    $fieldName = $this->_options['field']?$this->_options['field']:'photo';
		
		// Don't do anything if observed field haven't changed or photo just doesn't exists
		$modifiedFields = $i->getModified();
		if((!$i->isNew() && in_array($fieldName, $modifiedFields)) || !$i->hasPhoto()) return;
		
		$path = $i->getPhotoPath();
		$ext = substr($path, strpos($path, '.')+1);
		
		// Also, we cannot resize flash movies
		if($ext == 'swf') return;
		
		$dim = getimagesize($path);
		if($dim[0] > self::$__options['maxWidth'] || $dim[1] > self::$__options['maxHeight'])
		{
			$t = new sfThumbnail(self::$__options['maxWidth'], self::$__options['maxHeight'], true, true, 50);
			$t->loadFile($path);
			$t->save($path, 'image/jpeg');
		}

	}

}
