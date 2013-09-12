<?php


namespace Components;


  /**
   * Persistence_Resource_Mongodb
   *
   * @package net.evalcode.components.persistence
   * @subpackage resource
   *
   * @author evalcode.net
   */
  class Persistence_Resource_Mongodb extends Persistence_Resource_Abstract
  {
    // STATIC ACESSORS
    /**
     * Scans default locations for a mongodb server socket.
     *
     * @return string
     */
    public static function lookupSocket()
    {
      /* @var $path Io_Path */
      foreach(Io::tmpPathSystem() as $path)
      {
        if(preg_match('/mongo[-\D.sock]/', $path->getPath()))
          return $path->getPath();
      }

      return null;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    public function collectionExists($name_)
    {
      if(null===$this->m_collections)
      {
        if(false===($this->m_collections=$this->cache('collection', 'names')))
        {
          $this->m_collections=$this->execute("return db.getCollectionNames();");

          $this->cache('collection', 'names', $this->m_collections);
        }
      }

      return in_array($name_, $this->m_collections);
    }

    public function collectionCreate($name_)
    {
      $result=$this->execute("db.createCollection('$name_'); return db.getCollectionNames();");

      if(isset($result['ok']) && 1===(int)$result['ok'])
      {
        $this->m_collections=$result['retval'];
        $this->cache('collection', 'names', $this->m_collections);

        return true;
      }

      return false;
    }

    public function collectionDrop($name_)
    {
      $result=$this->execute("db.$name_.drop(); return db.getCollectionNames();");

      if(isset($result['ok']) && 1===(int)$result['ok'])
      {
        $this->m_collections=$result['retval'];
        $this->cache('collection', 'names', $this->m_collections);

        return true;
      }

      return false;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**
     * @see \Components\Persistence_Resource::view() \Components\Persistence_Resource::view()
     *
     * @return \Components\Persistence_View_Mongodb
     */
    public function view($name_, Persistence_Properties $properties_=null)
    {
      if(null===$properties_)
        $properties_=Persistence_Properties::forEntityName($name_);
      if(null===$properties_)
        $properties_=Persistence_Properties::generic($name_);

      return new Persistence_View_Mongodb($this, $properties_);
    }

    /**
     * @see \Components\Persistence_Resource::connection() \Components\Persistence_Resource::connection()
     *
     * @return \MongoDB
     */
    public function connection()
    {
      if(null===$this->m_database)
      {
        if($this->m_isolated)
          $this->m_database=$this->driver()->{$this->m_databaseNameIsolated};
        else
          $this->m_database=$this->driver()->{$this->m_databaseName};
      }

      return $this->m_database;
    }

    /**
     * @see \Components\Persistence_Resource::driver() \Components\Persistence_Resource::driver()
     *
     * @return \MongoClient
     */
    public function driver()
    {
      if(null===$this->m_driver)
      {
        if(Boolean::valueIsTrue($this->m_uri->getQueryParam('isolate')))
        {
          $this->m_isolated=true;
          $this->m_uri->removeQueryParam('isolate');
        }

        if($this->m_uri->getHost())
        {
          $this->m_databaseName=$this->m_uri->getPathParam(0);
          $this->m_connectionString=(string)$this->m_uri;
          $this->m_connectionOptions=array();
        }
        else
        {
          $this->m_databaseName=$this->m_uri->getFragment();
          $this->m_connectionString="mongodb://{$this->m_uri->getPath()}";
          $this->m_connectionOptions=$this->m_uri->getQueryParams();
        }

        if($this->m_isolated)
          $this->m_databaseNameIsolated=$this->m_databaseName.'_'.Runtime::getInstanceNamespace();

        if(0!==Persistence::$debugMode&Persistence::BIT_LOG_STATEMENTS)
          Log::debug('persistence/resource/mongodb', 'Connecting to database [%s].', $this);

        $this->m_driver=new \MongoClient($this->m_connectionString, $this->m_connectionOptions);
      }

      return $this->m_driver;
    }

    /**
     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
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
      return sprintf('%s@%s{uri: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_uri
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private $m_connectionOptions=array();
    private $m_connectionString;
    private $m_isolated=false;
    private $m_collections;
    private $m_databaseName;
    private $m_databaseNameIsolated;
    /**
     * @var \MongoDB
     */
    private $m_database;
    /**
     * @var \MongoClient
     */
    private $m_driver;
    //-----


    /**
     * @see \Components\Persistence_Resource_Abstract::findImpl() \Components\Persistence_Resource_Abstract::findImpl()
     */
    protected function findImpl($table_, $property_, $value_)
    {

    }

    /**
     * @see \Components\Persistence_Resource_Abstract::saveImpl() \Components\Persistence_Resource_Abstract::saveImpl()
     */
    protected function saveImpl($table_, $primaryKey_, array $record_)
    {
      // Trick the driver to return the new id without manipulating the original array.
      $properties=&$record_;
      $data=$record_;

      $result=$this->connection()->$table_->save($data);

      if(isset($result['ok']) && 1===(int)$result['ok'])
        return $data['_id']->{'$id'};

      return false;
    }

    /**
     * @see \Components\Persistence_Resource_Abstract::removeImpl() \Components\Persistence_Resource_Abstract::removeImpl()
     */
    protected function removeImpl($table_, $property_, $value_)
    {

    }

    /**
     * @see \Components\Persistence_Resource::executeImpl() \Components\Persistence_Resource::executeImpl()
     */
    protected function executeImpl($statement_)
    {
      return $this->connection()->execute($statement_);
    }

    /**
     * @see \Components\Persistence_Resource::queryImpl() \Components\Persistence_Resource::queryImpl()
     */
    protected function queryImpl(Query $query_)
    {
      // FIXME Return true/false success status and deliver extended result via Query instance.
      $query_->result($this->connection()->command($query_($this->connection())));
    }
    //--------------------------------------------------------------------------
  }
?>
