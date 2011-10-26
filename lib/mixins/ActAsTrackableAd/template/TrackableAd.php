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
class Doctrine_Template_TrackableAd extends Doctrine_Template
{    
	const ACTION_CLICK = 1;
	const ACTION_VIEW = 2;
	
  /**
   * Array of Translatable options
   *
   * @var string
   */
  protected $_options = array('reportsClassName' => null,
							  'fkColumnName'     => 'source',);

  /**
   * __construct
   *
   * @param string $array 
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    $this->_plugin = new ReportTableGenerator($this->_options);
  }


    /**
     * Initialize the I18n plugin for the template
     *
     * @return void
     */
    public function setUp()
    {
	  	// If reportsTableName option wasn't specified, we have to
	  	// create such table manually
	  	if(!$this->_options['reportsClassName'])
	  	{
	  		$this->_options['reportsClassName'] = str_replace('Table', '', get_class($this->_table)).'DailyReport';
	  	}

	  	$this->_plugin->initialize($this->_table);
	  	$this->addListener(new Doctrine_Template_Listener_TrackableAd($this->_options));
    }


	/**
	 * Zwiększa ilość wyświetleń i ilość zużytych środków
	 * 
	 * @return bool informacja czy wszystko się powiodło
	 */
	public function processView()
	{
		$this->processVisitorAction(self::ACTION_VIEW);
	}
	
	/**
	 * Zwiększa ilość kliknięć i ilość zużytych środków
	 * 
	 * @return bool informacja czy wszystko się powiodło
	 */
	public function processClick()
	{
		$this->processVisitorAction(self::ACTION_CLICK);
	}

	/**
	 * Obrabia akcję podjętą przez użytkownika i na jej podstawie
	 * decyduje, jakie dane dopisać do dzisiejszego raportu
	 * 
	 * @param int $type Typ akcji
	 * @throws RuntimeException
	 * @return bool
	 */
	private function processVisitorAction($type)
	{
		if(method_exists($this->getInvoker(), 'preProcessVisitorAction'))
		{
			$this->getInvoker()->preProcessVisitorAction($type);
		}
		
		try
		{
			$c = Doctrine_Manager::connection();
			$c->beginTransaction();
			
			$report = $this->getReport();
			switch($type)
			{
				case self::ACTION_VIEW:  $report->addView(); break;
				case self::ACTION_CLICK: $report->addClick(); break;
			}
			$report->save();
			
			$c->commit();
		}
		catch(Exception $e)
		{
			$c->rollback();
			throw $e;
		}
	}
	
	/**
	 * Zwraca raport dotyczący ruchu na dany dzień.
	 * Jeśli nie istnieje jeszcze taki raport - tworzy nowy.
	 * 
	 * @param datestring $date Dzień. Jeśli pusty - zostanie zwrócony raport z dzisiaj.
	 * @return void
	 */
	public function getReport($date = null)
	{
		if($date == null) $date = date("Y-m-d");
		
		$o = $this->getInvoker();
		
		$reportClassName = $this->_options['reportsClassName'];
		$fkName = $this->_options['fkColumnName'];
		
		$report = Doctrine::getTable($reportClassName)->
								 createQuery('adr')->
								 where('adr.date = ?', date("Y-m-d"))->
								 andWhere('adr.'.$fkName.' = ?', $this->getInvoker()->getId())->
								 execute()->
								 getFirst();

		if(!$report)
		{
			$report = new $reportClassName();
			$report->source = $o;
			$report->date = date("Y-m-d");
			$report->save();
		}
		
		return $report;
	}
	
	/**
	 * Zwraca liczbę kliknięć aktualnej reklamy
	 * 
	 * @see getVisitData
	 * @return int
	 */
	public function getClicks($from=null, $to=null)
	{
		return $this->getVisitData('a.clicks', $from, $to);
	}

	/**
	 * Zwraca liczbę wyświetleń aktualnej reklamy
	 * 
	 * @see getVisitData
	 * @return int
	 */
	public function getViews($from=null, $to=null)
	{
		return $this->getVisitData('a.views', $from, $to);
	}
	
	/**
	 * Zwraca sumę danego pola w danym okresie czasu dla aktualnej reklamy
	 * 
	 * @param date string $from Pierwszy dzień sprawdzanego przedziału czasu. Jeśli null - zwrócone zostaną wszystkie kliknięcia
	 * @param date string $to Ostatni dzień sprawdzanego przedziału czasu. Jeśli null - zwrócone zostaną kliknięcia z dnia $from
	 */
	public function getVisitData($field, $from=null, $to=null)
	{
		$o = $this->getInvoker();
		
		$reportClassName = $this->_options['reportsClassName'];
		$fkName = $this->_options['fkColumnName'];
		
		$q = Doctrine::getTable($reportClassName)
				  ->createQuery('a')
				  ->select('SUM('.$field.')')
				  ->where("a.$fkName = ?", $this->getInvoker()->getId());
				  
		if($from != null ) {
			$q->where('a.date >= ?', $from);
			if($to != null) {
				$q->where('a.date <= ?', $to);
			}
		}
		$res = $q->execute()->toArray();
		$s = $res[0]['SUM'];
		return $s ? $s : 0;
	}

	/**
	 * Zwraca dane dotyczące aktywności użytkowników typu $dataType w formacie array( 'Dzień' => wartość )
	 * Raport generowany jest za okres od $from do $to
	 * 
	 * @param int $dataType Typ żądanej aktywności
	 * @param timestamp $from Początek przeszukiwanego okresu
	 * @param timestamp $to Koniec przeszukiwanego okresu
	 * @return array Raport aktywności
	 */
	public function getUsersActivityReport($dataType, $from, $to)
	{
		$o = $this->getInvoker();
		
		$reportClassName = $this->_options['reportsClassName'];
		$fkName = $this->_options['fkColumnName'];
		
		$from = is_int($from) ? date("Y-m-d", $from) : $from;
		$to = is_int($to) ? date("Y-m-d", $to) : $to;
		$con = Doctrine_Manager::connection();
		$column = $dataType == self::ACTION_CLICK ? 'clicks' : 'views';
		$res = Doctrine::getTable($reportClassName)->
				createQuery('r')->
				select('r.date as date, r.'.$column.' as value')->
				from("$reportClassName r")->
				where('date between ? and ?', array($from, $to))->
				andWhere("r.$fkName = ?", $this->getInvoker()->getId())->
				execute();
		$res = $res->toArray();
		return lc('$i["date"] => (int)$i["value"] for $i in $res', array('res'=>$res));
	}

}
