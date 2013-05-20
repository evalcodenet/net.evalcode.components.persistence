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
    // CONSTRUCTION
    public function __construct(Persistence_Backend_Pdo $backend_, $name_)
    {
      $this->m_backend=$backend_;
      $this->m_name=$name_;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return string_hash($this->m_name);
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
      return sprintf('%s@%s{name: %s, backend: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_name,
        $this->m_backend
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var \Components\Persistence_Backend_Pdo
     */
    protected $m_backend;
    protected $m_name;
    //--------------------------------------------------------------------------
  }
?>
