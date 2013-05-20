<?php


namespace Components;


  /**
   * Persistence_Resource_Pool
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource
   *
   * @author evalcode.net
   */
  // TODO (CSH) Generalize to Resource_Connection, Resource_Connection_Pool etc.
  class Persistence_Resource_Pool extends Persistence_Resource
  {
    // PROPERTIES
    /**
     * @var Components\Persistence_Resource
     */
    public $resource;
    /**
     * @var Components\Persistence_Resource
     */
    public $resourceReadOnly;
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    public function configure(array $resourceIdentifiers_)
    {
      foreach($resourceIdentifiers_ as $resourceIdentifier)
      {
        $uri=Uri::valueOf($resourceIdentifier);
        $resource=$uri->getResource();

        if(!$resource instanceof Persistence_Resource)
        {
          throw new Exception_IllegalArgument('persistence/resource/pool',
            'Resource for given identifier is not a valid persistence resource.'
          );
        }

        if($resource->isReadOnly())
          $this->m_read[]=$resource;
        else
          $this->m_write[]=$resource;
      }

      $this->resource=$this->m_write[rand(0, count($this->m_write)-1)];
      $this->resourceReadOnly=$this->m_read[rand(0, count($this->m_read)-1)];

      if(!$this->resource)
        $this->resource=$this->resourceReadOnly;
    }
    //--------------------------------------------------------------------------


    // OVRRIDES/IMPLEMENTS
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
      return sprintf('%s@%s{read: %s, write: %s}',
        __CLASS__,
        $this->hashCode(),
        Arrays::toString($this->m_read),
        Arrays::toString($this->m_write)
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private $m_read=array();
    private $m_write=array();
    //--------------------------------------------------------------------------
  }
?>
