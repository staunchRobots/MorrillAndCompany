<?php

class ReportTableGenerator extends Doctrine_Record_Generator
{
    protected $_options = array(
                            'className'     => '%CLASS%DailyReport',
                            'tableName'     => '%TABLE%_daily_report',
                            'extraColumns'  => array(),
                            'generateFiles' => false,
                            'table'         => false,
                            'pluginTable'   => false,
                            'children'      => array(),
                            'fkColumnName'  => 'source',
                            'type'          => 'string',
                            'length'        => 2,
                            'options'       => array(),
                            'cascadeDelete' => true,
                            'appLevelDelete'=> false
                            );

    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct($options)
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    }

    public function buildRelation()
    {
        $this->buildForeignRelation('Report');
        $this->buildLocalRelation();
    }

    /**
     * buildDefinition
     *
     * @param object $Doctrine_Table
     * @return void
     */
    public function setTableDefinition()
    {
    	$columns = array(
	    	array($this->_options['fkColumnName'], 'int', null, array('type' => 'int', 'notnull' => true, 'default' => 0, )),
	    	array('date', 'date', null, array('type' => 'date', 'notnull' => true, 'default' => 0, )),
	    	array('clicks', 'int', null, array('type' => 'int', 'notnull' => true, 'default' => 0, )),
	    	array('views', 'int', null, array('type' => 'int', 'notnull' => true, 'default' => 0, )),
    	);
    	
    	foreach($this->_options['extraColumns'] as $k=>$options)
    	{
    		$columns[] = array($k, $options['type'], null, $options);
    	}
    	
    	foreach($columns as $col)
    	{
	        call_user_func_array(array($this, 'hasColumn'), $col);
    	}
    }
    
    public function setup()
    {
    	$cName = get_class($this->getOption('table'));
    	$cName = str_replace('Table', '', $cName);
        $this->hasOne($cName, array(
             'local' => $this->_options['fkColumnName'],
             'foreign' => 'id',
             'onDelete' => 'CASCADE',
#        	 'alias' => 'Source',
#        	 'foreignAlias' => 'Reports'
        ));
        $this->actAs(new Doctrine_Template_DailyReport($this->_options));
    }
}
