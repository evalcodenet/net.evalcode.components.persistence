<?php


namespace Components;


  /**
   * Entity_Abstract
   *
   * @package net.evalcode.components
   * @subpackage persistence.entity
   *
   * @author evalcode.net
   */
  class Entity_Abstract implements Entity
  {
    // PROPERTIES
    /**
     * @transient
     *
     * @var Components\Entity_Collection
     */
    public $collection;
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * (non-PHPdoc)
     * @see \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
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
      return Objects::toString($this);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Serializable::serialVersionUid()
     */
    public function serialVersionUid()
    {
      return 1;
    }
    //--------------------------------------------------------------------------
  }
?>
