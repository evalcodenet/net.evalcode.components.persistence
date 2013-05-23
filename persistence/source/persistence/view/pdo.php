<?php


namespace Components;


  /**
   * Persistence_View_Pdo
   *
   * @package net.evalcode.components
   * @subpackage persistence.view
   *
   * @author evalcode.net
   */
  class Persistence_View_Pdo implements Persistence_View
  {
    // PROPERTIES
    /**
     * @var string
     */
    public $name;
    /**
     * @var \Components\Persistence_Resource_Pdo
     */
    public $resource;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct($name_, Persistence_Resource_Pdo $resource_)
    {
      $this->name=$name_;
      $this->resource=$resource_;
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
