<?php


namespace Components;


  /**
   * Persistence_View_Mongodb
   *
   * @package net.evalcode.components.persistence
   * @subpackage view
   *
   * @author evalcode.net
   */
  class Persistence_View_Mongodb implements Persistence_View
  {
    // PROPERTIES
    /**
     * @var \Components\Persistence_Resource_Mongodb
     */
    public $resource;
    /**
     * @var \Components\Persistence_Properties
     */
    public $properties;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Persistence_Resource_Mongodb $resource_, Persistence_Properties $properties_)
    {
      $this->resource=$resource_;
      $this->properties=$properties_;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    public function create()
    {
      return $this->resource->collectionCreate($this->properties->collectionName);
    }

    public function drop()
    {
      return $this->resource->collectionDrop($this->properties->collectionName);
    }

    public function indexCreate($name_, $property_/*, $property1_... */)
    {

    }

    public function indexDrop($name_)
    {

    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * @see \Components\Persistence_View::findByPk() \Components\Persistence_View::findByPk()
     */
    public function findByPk($primaryKey_)
    {
      return $this->resource->find(
        $this->properties->collectionName,
        $this->properties->collectionPrimaryKey,
        $primaryKey_
      );
    }

    /**
     * @see \Components\Persistence_View::save() \Components\Persistence_View::save()
     */
    public function save(array $record_)
    {
      return $this->resource->save(
        $this->properties->collectionName,
        $this->properties->collectionPrimaryKey,
        $record_
      );
    }

    /**
     * @see \Components\Persistence_View::remove() \Components\Persistence_View::remove()
     */
    public function remove($primaryKey_)
    {
      return $this->resource->remove(
        $this->properties->collectionName,
        $this->properties->collectionPrimaryKey,
        $primaryKey_
      );
    }

    /**
     * @see \Components\Persistence_View::collection() \Components\Persistence_View::collection()
     */
    public function collection()
    {
      $collectionType=$this->properties->collectionType;

      return new $collectionType($this, $this->properties);
    }

    /**
     * @see \Components\Persistence_View::getIterator() \Components\Persistence_View::getIterator()
     */
    public function getIterator()
    {
      $collectionType=$this->properties->collectionType;

      return new $collectionType($this, $this->properties);
    }

    /**
     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return string_hash($this->properties->collectionName);
    }

    /**
     * @see \Components\Object::equals() \Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * @see \Components\Object::__toString() \Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{properties: %s, resource: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->properties,
        $this->resource
      );
    }
    //--------------------------------------------------------------------------
  }
?>
