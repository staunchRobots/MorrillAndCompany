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
class Doctrine_Template_Sortable extends Doctrine_Template
{    
  /**
   * Array of Sortable options
   *
   * @var string
   */
  protected $_options = array('name'        =>  'position',
                              'alias'       =>  null,
                              'type'        =>  'integer',
                              'length'      =>  8,
                              'unique'      =>  true,
                              'options'     =>  array(),
                              'fields'      =>  array(),
                              'uniqueBy'    =>  array(),
                              'uniqueIndex' =>  true,
                              'canUpdate'   =>  false,
                              'indexName'   =>  'sortable'
  );

  /**
   * __construct
   *
   * @param string $array 
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }


  public function setup()
  {
  }


  /**
   * Set table definition for sortable behavior
   * (borrowed and modified from Sluggable in Doctrine core)
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $name = $this->_options['name'];

    if ($this->_options['alias'])
    {
      $name .= ' as ' . $this->_options['alias'];
    }

    $this->hasColumn($name, $this->_options['type'], $this->_options['length'], $this->_options['options']);

    if ($this->_options['uniqueIndex'] == true && ! empty($this->_options['uniqueBy']))
    {
      $indexFields = array($this->_options['name']);
      $indexFields = array_merge($indexFields, $this->_options['uniqueBy']);

      $this->index($this->_options['indexName'], array('fields' => $indexFields,
                                                       'type'   => 'unique'));
    }
    elseif ($this->_options['unique'])
    {
      $indexFields = array($this->_options['name']);
      $this->index($this->_options['indexName'], array('fields' => $indexFields,
                                                       'type'   => 'unique'));
    }

    $this->addListener(new Doctrine_Template_Listener_Sortable($this->_options));
  }

  /**
   * Demotes a sortable object to a lower position
   *
   * @return void
   */
  public function demote()
  { 
    $object = $this->getInvoker();       
    $position = $object->position;
    
    if ($object->position < $object->getFinalPosition())
    {
      $object->moveToPosition($position + 1);
    }
  }


  /**
   * Promotes a sortable object to a higher position
   *
   * @return void
   */
  public function promote()
  {
    $object = $this->getInvoker();       
    $position = $object->position;
    
    if ($object->position > 1)
    {
      $object->moveToPosition($position - 1);
    }
  }

  /**
   * Sets a sortable object to the first position
   *
   * @return void
   */
  public function moveToFirst()
  {
    $object = $this->getInvoker();       
    $object->moveToPosition(1);
  }


  /**
   * Sets a sortable object to the last position
   *
   * @return void
   */
  public function moveToLast()
  {
    $object = $this->getInvoker();       
    $object->moveToPosition($object->getFinalPosition());
  }


  /**
   * Moves a sortable object to a designate position
   *
   * @param int $newPosition
   * @return void
   */
  public function moveToPosition($newPosition)
  {
    if (!is_int($newPosition))
    {
      throw new Doctrine_Exception('moveToPosition requires an Integer as the new position. Entered ' . $newPosition);
    }

    $object = $this->getInvoker();       
    $position = $object->position;

    // Position is required to be unique. Blanks it out before it moves others up/down.
    if(!$object->setPosition(null)){
      throw new Doctrine_Exception('Failed to set the position to null on your '.get_class($object));
    }
		$object->save();

    // if(!$object->save()){
    //   throw new Doctrine_Exception('Failed to save your '.get_class($object).' with a blank position.');
    // }    
    if ($position > $newPosition)
    {
      $q = Doctrine_Query::create()
                         ->update(get_class($object))
                         ->set('position', 'position + 1')
                         ->where('position < ' . $position)
                         ->andWhere('position >= ' . $newPosition)
                         ->orderBy('position DESC');
                
      foreach ($this->_options['uniqueBy'] as $field)
      {
        $q->addWhere($field.' = '.$object[$field]);
      }
                        
      if(!$q->execute()){
        throw new Doctrine_Exception('Failed to run the following query: '.$q->getSql());
      }
    }
    elseif ($position < $newPosition)
    {

      $q = Doctrine_Query::create()
                         ->update(get_class($object))
                         ->set('position', 'position - 1')
                         ->where('position > ?', $position)
                         ->andWhere('position <= ' . $newPosition);

      foreach($this->_options['uniqueBy'] as $field)
      {
        $q->addWhere($field . ' = ' . $object[$field]);
      }

      if(!$q->execute()){
        throw new Doctrine_Exception('Failed to run the following query: '.$q->getSql());
      }
    }
    
    if(!$object->setPosition($newPosition)){
      throw new Doctrine_Exception('Failed to set the position on your '.get_class($object));
    }
		$object->save();
    // if(!$object->save()){
    //   throw new Doctrine_Exception('Failed to save your '.get_class($object));
    // }
  }


  /**
   * Send an array from the sortable_element tag (symfony+prototype)and it will 
   * update the sort order to match
   *
   * @param string $order
   * @return void
   * @author Travis Black
   */
  public function sortTableProxy($order)
  {
    /*
      TODO 
        - Make this a transaction.
        - Add proper error messages.
    */

    $class = get_class($this->getInvoker()); 

    foreach ($order as $position => $id) 
    {
      $newObject = Doctrine::getTable($class)->findOneById($id);

      if ($newObject->position != $position + 1)
      {
        $newObject->moveToPosition($position + 1);
      }
    }
  }


  /**
   * Finds all sortable objects and sorts them based on position attribute
   * Ascending or Descending based on parameter
   *
   * @param string $order
   * @return $query
   */
  public function findAllSortedTableProxy($order = 'ASC')
  {
    $order = $this->formatAndCheckOrder($order);

    $class = get_class($this->getInvoker()); 
    $query = Doctrine_Query::create()
                           ->from($class . ' od')
                           ->orderBy('od.position ' . $order);

    return $query->execute();
  }


  /**
   * Finds and returns records sorted where the parent (fk) in a specified
   * one to many relationship has the value specified
   *
   * @param string $parent_value
   * @param string $parent_column_value
   * @param string $order
   * @return $query
   */
  public function findAllSortedWithParentTableProxy($parent_value, $parent_column_name = null, $order = 'ASC')
  {
    $order = $this->formatAndCheckOrder($order);
    
    $object = $this->getInvoker();
    $class  = get_class($object);
    
    if (!$parent_column_name)
    {
      $parents = get_class($object->getParent());

      if (count($parents) > 1)
      {
        throw new Doctrine_Exception('No parent column name specified and object has mutliple parents');
      }
      elseif (count($parents) < 1)
      {
        throw new Doctrine_Exception('No parent column name specified and object has no parents');
      }
      else
      {
        $parent_column_name = $parents[0]->getType();
        exit((string) $parent_column_name);
        exit(print_r($parents[0]->toArray()));
      }
    }
    
    $query = Doctrine_Query::create()
                           ->from($class . ' od')
                           ->where('od.' . $parent_column_name . ' = ?', $parent_value)
                           ->orderBy('position ' . $order);

    return $query->execute();
  }


  /**
   * Formats the ORDER for insertion in to query, else throws exception
   *
   * @param string $order
   * @return $order
   */
  public function formatAndCheckOrder($order)
  {
    $order = strtolower($order);

    if ($order == 'ascending' || $order == 'asc')
    {
      $order = 'ASC';
    }
    elseif ($order == 'descending' || $order == 'desc')
    {
      $order = 'DESC';
    }
    else
    {
      throw new Doctrine_Exception('Order parameter value must be "asc" or "desc"');
    }
    
    return $order;
  }


  /**
   * Get the final position of a model
   *
   * @return $position
   */
  public function getFinalPosition()
  {
    $object = $this->getInvoker();       
    
    $q = Doctrine_Query::create()
                       ->select('position')
                       ->from(get_class($object) . ' st')
                       ->orderBy('position desc')
                       ->limit(1);

   foreach($this->_options['uniqueBy'] as $field)
   {
     if(is_object($object[$field])){
       $q->addWhere($field . ' = ' . $object[$field]['id']); 
     }
     else{
       $q->addWhere($field . ' = ' . $object[$field]);
     }
   }
    
    try
    {
      $last = $q->execute();
      $position = $last[0]->position;
    }
    catch (Exception $e)
    {
      //return 0;
      exit ($q->getSql());
    }

    return $position;
  }
}
