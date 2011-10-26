<?php


/**
 * Easily translate model's fields
 *
 * @package     skyBehaviors
 * @subpackage  listener
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Adam Zieliński <kontakt@azielinski.info>
 */
class Doctrine_Template_Listener_TrackableAd extends Doctrine_Record_Listener
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
  
}
