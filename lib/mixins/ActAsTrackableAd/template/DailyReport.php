<?php

/**
 * Easily translate model's fields
 *
 * @package     skyBehaviors
 * @subpackage  template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Adam Zieliński <kontakt@azielinski.info>
 */
class Doctrine_Template_DailyReport extends Doctrine_Template
{    
  /**
   * Array of Translatable options
   *
   * @var string
   */
  protected $_options = array('reportsTableName' => null);

  /**
   * __construct
   *
   * @param string $array 
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = $options;
  }
  

  /**
   * Set table definition for TrackableAd behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
  }


	
	/**
	 * Dodaje wyświetlenie danego raportu
	 * 
	 * @throws RuntimeException
	 * @return bool
	 */
	public function addView() { return $this->increaseViews(); }
	
	/**
	 * Dodaje kliknięcie danego raportu
	 * 
	 * @throws RuntimeException
	 * @return bool
	 */
	public function addClick() { return $this->increaseClicks(); }
	
	
	private function increaseViews()
	{
		$this->getInvoker()->views += 1;
	}
	
	private function increaseClicks()
	{
		$this->getInvoker()->clicks += 1;
	}

}
