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
class Doctrine_Template_Translatable extends Doctrine_Template
{    
  /**
   * Array of Translatable options
   *
   * @var string
   */
  protected $_options = array();

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
   * Set table definition for sortable behavior
   * (borrowed and modified from Sluggable in Doctrine core)
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->addListener(new Doctrine_Template_Listener_Translatable($this->_options));
  }


	/**
	 * Tłumaczy pole $field z domyślnego języka - polskiego, na język $target
	 * 
	 * @param string $field Nazwa pola do przetłumaczenia
	 * @param string $to Język docelowy
	 * @return string Przetłumaczony tekst
	 */
	public function translateField($field, $to)
	{
		$pl = $this->getInvoker()->Translation['pl']->$field;
		$sc = new TranslatorSC();
		$translator = $sc->translator;
		$translated = $translator->translate($pl, 'pl', $to);
		
		$field{0} = strtolower($field{0});
		$this->getInvoker()->Translation[$to]->$field = $translated;
		
		return $this->getInvoker();
	}
	

}
