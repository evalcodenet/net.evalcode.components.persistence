<?php


namespace Components;


  /**
   * Persistence_Resource_Pool
   *
   * @package net.evalcode.components.persistence
   * @subpackage resource
   *
   * @author evalcode.net
   */
  class Persistence_Resource_Pool implements Persistence_Resource
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


    // ACCESSORS
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
     * @see \Components\Persistence_Resource::save() \Components\Persistence_Resource::save()
     */
    public function save($table_, $primaryKey_, array $record_)
    {
      return $this->resource->save($table_, $primaryKey_, $record_);
    }

    /**
     * @see \Components\Persistence_Resource::find() \Components\Persistence_Resource::find()
     */
    public function find($table_, $property_, $value_)
    {
      return $this->resource->find($table_, $property_, $value_);
    }

    /**
     * @see \Components\Persistence_Resource::remove() \Components\Persistence_Resource::remove()
     */
    public function remove($table_, $property_, $value_)
    {
      return $this->resource->remove($table_, $property_, $value_);
    }

    /**
     * @see \Components\Persistence_Resource::query() \Components\Persistence_Resource::query()
     */
    public function query(Query $query_)
    {
      return $this->resource->query($query_);
    }

    /**
     * @see \Components\Persistence_Resource::execute() \Components\Persistence_Resource::execute()
     */
    public function execute($statement_)
    {
      return $this->resource->execute($statement_);
    }

    /**
     * @see \Components\Persistence_Resource::collection() \Components\Persistence_Resource::collection()
     */
    public function collection($name_)
    {
      return $this->resource->collection($name_);
    }

    /**
     * @see \Components\Persistence_Resource::view() \Components\Persistence_Resource::view()
     */
    public function view($name_, Persistence_Properties $properties_=null)
    {
      return $this->resource->view($name_, $properties_);
    }

    /**
     * @see \Components\Persistence_Resource::connection() \Components\Persistence_Resource::connection()
     */
    public function connection()
    {
      return $this->resource->connection();
    }

    /**
     * @see \Components\Persistence_Resource::driver() \Components\Persistence_Resource::driver()
     */
    public function driver()
    {
      return $this->resource->driver();
    }

    /**
     * @see \Components\Persistence_Resource::transactionBegin() \Components\Persistence_Resource::transactionBegin()
     */
    public function transactionBegin()
    {
      $this->resource->transactionBegin();
    }

    /**
     * @see \Components\Persistence_Resource::transactionCommit() \Components\Persistence_Resource::transactionCommit()
     */
    public function transactionCommit()
    {
      $this->resource->transactionCommit();
    }

    /**
     * @see \Components\Persistence_Resource::transactionRollback() \Components\Persistence_Resource::transactionRollback()
     */
    public function transactionRollback()
    {
      $this->resource->transactionRollback();
    }

    /**
     * @see \Components\Persistence_Resource::isReadOnly() \Components\Persistence_Resource::isReadOnly()
     */
    public function isReadOnly()
    {
      return 0===count($this->m_write);
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
      return sprintf('%s@%s{read: %s, write: %s}',
        __CLASS__,
        $this->hashCode(),
        Arrays::toString($this->m_read),
        Arrays::toString($this->m_write)
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private $m_read=[];
    private $m_write=[];
    //--------------------------------------------------------------------------
  }
?>
