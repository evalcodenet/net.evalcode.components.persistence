<?php


namespace Components;


  /**
   * Persistence_Resource_Pdo_Mysql
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource.pdo
   *
   * @author evalcode.net
   */
  class Persistence_Resource_Pdo_Mysql extends Persistence_Resource_Pdo
  {
    // OVERRIDES/IMPLEMENTS
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
      return sprintf('%s@%s{uri: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_uri
      );
    }
    //--------------------------------------------------------------------------
  }
?>
