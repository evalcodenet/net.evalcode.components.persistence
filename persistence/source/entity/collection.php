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
    // PROPERTIES
    /**
     * @var \Components\Persistence_View
     */
    public $view;
    public $type;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Persistence_View $view_, $entityType_)
    {
      $this->view=$view_;
      $this->type=$entityType_;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    public function find($primaryKey_)
    {
      $result=$this->view->find($primaryKey_);
    }

    public function isEmpty()
    {

    }

    public function count()
    {

    }

    public function clear()
    {

    }

    public function add($element_)
    {

    }

    public function addAll(Collection $collection_)
    {

    }

    public function remove($element_=null)
    {

    }

    public function removeAll(Collection $collection_)
    {

    }

    public function retainAll(Collection $collection_)
    {

    }

    public function arrayValue()
    {

    }

    public function hashCode()
    {

    }

    public function equals($object_)
    {

    }

    public function __toString()
    {
      return Objects::toString($this);
    }
    //--------------------------------------------------------------------------
  }
?>
