<?php

/**
 * Easily create a slug for each record based on a specified set of fields
 *
 * @package     csDoctrineSortablePlugin
 * @subpackage  template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Travis Black <tblack@centresource.com>
 */
class Doctrine_Template_Photoable extends Doctrine_Template
{    
  /**
   * Array of Sortable options
   *
   * @var string
   */
  protected $_options = array('field'				=>  'photo',
                              'alias'				=>  null,
                              'type'				=>  'varchar',
                              'length'				=>  45,
                              'unique'				=>  true,
                              'flashAllowed'		=>  false,
                              'options'				=>  array(),
                              'fields'				=>  array(),
                              'uniqueBy'			=>  array(),
                              'uniqueIndex'			=>  true,
                              'indexName'			=>  'photo',
  							  'upload_dir'          =>  null,
                              'maxWidth'			=>  null,
                              'maxHeight'			=>  null,
  );

  /**
   * __construct
   *
   * @param string $array 
   * @return void
   */
  public function __construct(array $options = array())
  {
  	if(isset($options['upload_dir'])) {
  		$options['upload_dir'] = sfConfig::get('sf_upload_dir').'/'.$options['upload_dir'];
  	}
  	$this->_options['upload_dir'] = sfConfig::get('sf_upload_dir');
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }
  

  /**
   * Set table definition for sortable behavior
   * (borrowed and modified from Sluggable in Doctrine core)
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $name = $this->_options['field'];

    if ($this->_options['alias'])
    {
      $name .= ' as ' . $this->_options['alias'];
    }

		try {
			sfContext::getInstance()->getEventDispatcher()->connect(
				'form.post_configure',
				array($this, 'configureForm')
			);
		} catch (Exception $exc) { }

    $this->hasColumn($name, $this->_options['type'], $this->_options['length'], $this->_options['options']);
    $this->addListener(new Doctrine_Template_Listener_Photoable($this->_options));
  }

  /**
   * Dodaje do formularza odpowiadającego danemu modelowi pola
	 * pozwalające na dodawanie zdjęć
   *
   * @return void
   */
	public function configureForm(sfEvent $event)
	{
		$form = $event->getSubject();
		if(!($form instanceof sfFormDoctrine)) return;
		$modelName = $form->getModelName();

		if(isset($form->isAttached)) return;
		try { $t = Doctrine::getTable($modelName); } catch (Exception $exc) { return; }
		if(!$t->hasTemplate('Doctrine_Template_Photoable')) return;
		
		$form->isAttached = true;
		$ws = $form->getWidgetSchema();
		$vs = $form->getValidatorSchema();

    	$name = $this->_options['field'];
		sfContext::getInstance()->getLogger()->log('Connecting: '.$name, 1);

		if(isset($ws[$name]) && !$vs[$name] instanceof sfValidatorFile)
		{
			die(get_class($vs[$name]));
			$ws[$name] = new sfWidgetFormInputFile();
			$options = array(
				'required' => false,
				'path' => sfConfig::get('sf_upload_dir'),
			);
			if($this->_options['flashAllowed'])
			{
				$options['mime_categories'] = array(
				  'upload_files' => array(
				     'image/jpeg',
				     'image/pjpeg',
				     'image/png',
				     'image/x-png',
				     'image/gif',
				     'application/x-shockwave-flash',
				  )
				);
			} else {
				$options['mime_types'] = 'web_images';
			}
			$vs[$name] = new sfValidatorFile($options);
		}
		
	}

	
  /**
   * Returns path ($absolute ? absolute : relative to the web directory) to the directory where the photo is uploaded
   * 
   * @return string
   */
  public function getPhotoDir($absolute = false)
  {
  	$o = $this->_options;
  	$dir = $o['upload_dir'];
  	if(!$absolute)
  	{
      $dir = str_replace(sfConfig::get('sf_web_dir'), '', $dir);
   	}
   	$dir = rtrim($dir.'/').'/';
   	return $dir;
  }
	
  /**
   * Returns path (relative to the web directory) where the photo is uploaded
   * 
   * @return string
   */
  public function getPhotoPath($absolute = true)
  {
    $object = $this->getInvoker();
    $photo = $object->get($this->_options['field']);
    
  	return $this->getPhotoDir($absolute).$photo;
  }

  /**
   * Returns photo url
   *
   * @return void
   */
  public function getPhotoUrl()
  {
  	sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'Tag'));
    return public_path($this->getPhotoPath(false));
  }


  /**
   * Returns true if object has related photo, false otherwise
   *
   * @return bool
   */
  public function hasPhoto()
  {
    $object = $this->getInvoker();
    $photo = $object->get($this->_options['field']);
    return $photo != '' && file_exists($this->getPhotoPath());
  }
  
  


}
