<?php


namespace Components;


  /**
   * Entity_Default
   *
   * @package net.evalcode.components.persistence
   * @subpackage entity
   *
   * @author evalcode.net
   *
   * @api
   */
  class Entity_Default implements Entity
  {
    // PREDEFINED PROPERTIES
    const TYPE=__CLASS__;
    const PRIMARY_KEY_DEFAULT='id';
    //--------------------------------------------------------------------------


    // PROPERTIES
    /**
     * @transient
     *
     * @var \Components\Entity_Collection
     */
    public $collection;
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * @see \Components\Serializable_Php::__sleep() \Components\Serializable_Php::__sleep()
     */
    public function __sleep()
    {
      if(null===self::$m_mapper)
        self::$m_mapper=new Object_Mapper();

      $this->m_properties=self::$m_mapper->dehydrate($this);

      return array('m_properties');
    }

    /**
     * @see \Components\Serializable_Php::__wakeup() \Components\Serializable_Php::__wakeup()
     */
    public function __wakeup()
    {
      if(null===self::$m_mapper)
        self::$m_mapper=new Object_Mapper();

      self::$m_mapper->hydrate($this, $this->m_properties);
    }

    /**
     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return \math\hasho($this);
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
      return Objects::toString($this);
    }

    /**
     * @see \Components\Serializable::serialVersionUid() \Components\Serializable::serialVersionUid()
     */
    public function serialVersionUid()
    {
      return 1;
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @transient
     * @var \Components\Object_Mapper
     */
    private static $m_mapper;
    //--------------------------------------------------------------------------
  }
?>
