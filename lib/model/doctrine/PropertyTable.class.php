<?php


class PropertyTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Property');
    }

    /**
     * Returns available property types
     * 
     * @return array
     */
    public function getTypes()
    {
    	return array('Sold','Under contract', 'For Rent', 'For Sale',
    		'Availailable - Rent',
    		'Availailable - Purchase',
    		'Availailable - Rent or Purchase',
    		'Currently Rented');
    }
    
   /**
     * Returns available property statuses
     * 
     * @return array
     */
    public function getStatuses()
    {
    	return array(
    		'For Rent',
    		'For Sale',
    	);
    }
    
}