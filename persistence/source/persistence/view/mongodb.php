<?php


namespace Components;


  /**
   * Persistence_View_Mongodb
   *
   * @package net.evalcode.components
   * @subpackage persistence.view
   *
   * @author evalcode.net
   */
  class Persistence_View_Mongodb implements Persistence_View
  {
    // PROPERTIES
    /**
     * @var string
     */
    public $name;
    /**
     * @var \Components\Persistence_Resource_Mongodb
     */
    public $resource;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct($name_, Persistence_Resource_Mongodb $resource_)
    {
      $this->name=$name_;
      $this->resource=$resource_;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    public function save(Entity $entity_)
    {
      return $this->resource->save($entity_, $this->name);
    }

    public function drop()
    {
      return $this->resource->collectionDrop($this->name);
    }

    public function create()
    {
      return $this->resource->collectionCreate($this->name);
    }

    public function indexCreate($name_, $property_/*, $property1_... */)
    {

    }

    public function indexDrop($name_)
    {

    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return string_hash($this->name);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{name: %s, resource: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->name,
        $this->resource
      );
    }
    //--------------------------------------------------------------------------
  }
?>
