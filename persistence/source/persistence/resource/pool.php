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
     * @var \Components\Persistence_Resource
     */
    public $resource;
    /**
     * @var \Components\Persistence_Resource
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

        $this->m_read[]=$resource;

        if(!$resource->isReadOnly())
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
     * @see \Components\Persistence_Resource::execute()
     */
    public function execute($query_)
    {
      return $this->resource->{parent::$m_methodExecute}($query_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::execute()
     */
    public function executeLogged($query_)
    {
      return $this->resource->executeLogged($query_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::driver()
     */
    public function driver()
    {
      return $this->resource->driver();
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionBegin()
     */
    public function transactionBegin()
    {
      $this->resource->transactionBegin();
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionCommit()
     */
    public function transactionCommit()
    {
      $this->resource->transactionCommit();
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionRollback()
     */
    public function transactionRollback()
    {
      $this->resource->transactionRollback();
    }

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
    //-----

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::executeImpl()
     */
    protected function executeImpl($query_)
    {
      return $this->resource->executeImpl($query_);
    }
    //--------------------------------------------------------------------------
  }
?>
