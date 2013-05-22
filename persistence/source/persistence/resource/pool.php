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
    public function execute($statement_)
    {
      return $this->resource->{self::$m_methodExecute}($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::execute()
     */
    public function executeDebug($statement_)
    {
      return $this->resource->executeDebug($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::execute()
     */
    public function query($statement_)
    {
      return $this->resource->{self::$m_methodQuery}($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::execute()
     */
    public function queryDebug($statement_)
    {
      return $this->resource->queryDebug($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::invoke()
     */
    public function invoke($callable_)
    {
      return $this->resource->{self::$m_methodInvoke}($callable_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::invokeDebug()
     */
    public function invokeDebug($callable_)
    {
      return $this->resource->invokeDebug($callable_);
    }

    /**
     * (non-PHPdoc)
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::driver()
     */
    public function driver()
    {
      return $this->resource->driver();
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::connection()
     */
    public function connection()
    {
      return $this->resource->connection();
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
     * @see \Components\Persistence_Resource::collectionExists()
     */
    public function collectionExists($name_)
    {
      return $this->resource->collectionExists($name_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::collectionCreate()
     */
    public function collectionCreate($name_)
    {
      return $this->resource->collectionCreate($name_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::collectionDrop()
     */
    public function collectionDrop($name_)
    {
      return $this->resource->collectionDrop($name_);
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
    protected function executeImpl($statement_)
    {
      return $this->resource->executeImpl($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::executeImpl()
     */
    protected function queryImpl($statement_)
    {
      return $this->resource->queryImpl($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::executeImpl()
     */
    protected function invokeImpl($callable_)
    {
      return $this->resource->invokeImpl($callable_);
    }
    //--------------------------------------------------------------------------
  }
?>
