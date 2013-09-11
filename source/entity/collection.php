<?php


namespace Components;


  /**
   * Entity_Collection
   *
   * @package net.evalcode.components
   * @subpackage persistence.entity
   *
   * @author evalcode.net
   */
  class Entity_Collection implements Collection_Mutable
  {
    // PREDEFINED PROPERTIES
    const TYPE=__CLASS__;
    //--------------------------------------------------------------------------


    // PROPERTIES
    /**
     * @var \Components\Persistence_View
     */
    public $view;
    /**
     * @var \Components\Persistence_Properties
     */
    public $properties;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Persistence_View $view_, Persistence_Properties $properties_)
    {
      $this->view=$view_;
      $this->properties=$properties_;

      if(null===self::$m_objectMapper)
        self::$m_objectMapper=new Object_Mapper();
      if(false===isset(self::$m_cache[$this->properties->collectionName]))
        self::$m_cache[$this->properties->collectionName]=array();
    }
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    public function save(Entity $entity_)
    {
      $properties=self::$m_objectMapper->dehydrateObjectOfType($entity_, get_class($entity_));
      $result=$this->view->save($properties);

      if(false===$result)
        return false;

      $entity_->{$this->properties->collectionPrimaryKey}=$result;

      static::saveToCache($this->properties->collectionName, $result, $entity_);

      return true;
    }

    public function findByPk($primaryKey_)
    {
      if($entity=static::loadFromCache($this->properties->collectionName, $primaryKey_))
        return $entity;

      if($properties=$this->view->findByPk($primaryKey_))
      {
        $entity=self::$m_objectMapper->hydrateForType($this->properties->entityType, $properties);

        return static::saveToCache($this->properties->collectionName, $primaryKey_, $entity);
      }

      return null;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**     * @see \Components\Collection::isEmpty() \Components\Collection::isEmpty()
     */
    public function isEmpty()
    {
      return 0===$this->view->count();
    }

    /**     * @see \Components\Countable::count() \Components\Countable::count()
     */
    public function count()
    {
      return $this->view->count();
    }

    /**     * @see \Components\Collection_Mutable::clear() \Components\Collection_Mutable::clear()
     */
    public function clear()
    {
      return $this->view->clear();
    }

    /**     * @see \Components\Collection_Mutable::add() \Components\Collection_Mutable::add()
     *
     * @param \Components\Entity $element_
     */
    public function add($element_)
    {
      return $this->save($element_);
    }

    /**     * @see \Components\Collection_Mutable::addAll() \Components\Collection_Mutable::addAll()
     */
    public function addAll(Collection $collection_)
    {

    }

    /**     * @see \Components\Collection_Mutable::remove() \Components\Collection_Mutable::remove()
     *
     * @param \Components\Entity $element_
     */
    public function remove($element_=null)
    {
      if(null===$element_)
        $element_=$this->current();

      $primaryKey=$element_->{$this->properties->collectionPrimaryKey};

      if($this->view->remove($primaryKey))
      {
        static::removeFromCache($primaryKey);

        return true;
      }

      return false;
    }

    /**     * @see \Components\Collection_Mutable::removeAll() \Components\Collection_Mutable::removeAll()
     */
    public function removeAll(Collection $collection_)
    {

    }

    /**     * @see \Components\Collection_Mutable::retainAll() \Components\Collection_Mutable::retainAll()
     */
    public function retainAll(Collection $collection_)
    {

    }

    /**     * @see \Components\Collection::arrayValue() \Components\Collection::arrayValue()
     */
    public function arrayValue()
    {
      return $this->view->findAll();
    }

    /**     * @see \Components\Iterator::current() \Components\Iterator::current()
     */
    public function current()
    {

    }

    /**     * @see \Components\Iterator::key() \Components\Iterator::key()
     */
    public function key()
    {

    }

    /**     * @see \Components\Iterator::hasNext() \Components\Iterator::hasNext()
     */
    public function hasNext()
    {

    }

    /**     * @see \Components\Iterator::hasPrevious() \Components\Iterator::hasPrevious()
     */
    public function hasPrevious()
    {

    }

    /**     * @see \Components\Iterator::next() \Components\Iterator::next()
     */
    public function next()
    {

    }

    /**     * @see \Components\Iterator::previous() \Components\Iterator::previous()
     */
    public function previous()
    {

    }

    /**     * @see \Components\Iterator::rewind() \Components\Iterator::rewind()
     */
    public function rewind()
    {

    }

    /**     * @see \Components\Iterator::valid() \Components\Iterator::valid()
     */
    public function valid()
    {

    }

    /**     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {

    }

    /**     * @see \Components\Object::equals() \Components\Object::equals()
     */
    public function equals($object_)
    {

    }

    /**     * @see \Components\Object::__toString() \Components\Object::__toString()
     */
    public function __toString()
    {
      return Objects::toString($this);
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var array|\Components\Entity
     */
    private static $m_cache=array();
    /**
     * @var \Components\Object_Mapper
     */
    private static $m_objectMapper;
    //-----


    // HELPERS
    /**
     * @param string $namespace_
     * @param mixed $key_
     * @param \Components\Entity $entity_
     */
    protected static function saveToCache($namespace_, $key_, Entity $entity_)
    {
      return self::$m_cache[$namespace_][$key_]=$entity_;
    }

    /**
     * @param string $namespace_
     * @param mixed $key_
     *
     * @return \Components\Entity|false
     */
    protected static function loadFromCache($namespace_, $key_)
    {
      if(false===isset(self::$m_cache[$namespace_][$key_]))
        return false;

      return self::$m_cache[$namespace_][$key_];
    }

    /**
     * @param string $namespace_
     * @param mixed $key_
     */
    protected static function removeFromCache($namespace_, $key_)
    {
      if(isset(self::$m_cache[$namespace_][$key_]))
        self::$m_cache[$namespace_][$key_]=null;
    }
    //--------------------------------------------------------------------------
  }
?>
