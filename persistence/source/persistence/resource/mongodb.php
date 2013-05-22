<?php


namespace Components;


  /**
   * Persistence_Resource_Mongodb
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource
   *
   * @author evalcode.net
   */
  class Persistence_Resource_Mongodb extends Persistence_Resource
  {
    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::collectionExists()
     */
    public function collectionExists($name_)
    {
      if(null===$this->m_collections)
      {
        if(false===($this->m_collections=Cache::get("{$this->m_cacheNamespace}/collections")))
        {
          $this->m_collections=$this->connection()->getCollectionNames();

          Cache::set("{$this->m_cacheNamespace}/collections", $this->m_collections);
        }
      }

      return in_array($name_, $this->m_collections);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::collectionCreate()
     */
    public function collectionCreate($name_)
    {
      $this->invoke(Command_Mongodb::CREATE_COLLECTION($name_));
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::collectionDrop()
     */
    public function collectionDrop($name_)
    {
      // FIXME Parameter scope ...
      $this->invoke(function() {return array('drop'=>$name_);});
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::connection()
     *
     * @return \MongoDB
     */
    public function connection()
    {
      if(null===$this->m_database)
      {
        if($this->m_isolated)
          $this->m_database=$this->driver()->selectDB($this->m_databaseNameIsolated);
        else
          $this->m_database=$this->driver()->selectDB($this->m_databaseName);
      }

      return $this->m_database;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::driver()
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

        if(0<(self::$m_debugMode&Persistence_Resource::DEBUG_LOG_STATEMENTS))
          Log::debug('persistence/resource/mongodb', 'Connecting to database [%s].', $this);

        $this->m_driver=new \MongoClient($this->m_connectionString, $this->m_connectionOptions);
      }

      return $this->m_driver;
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
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::executeImpl()
     */
    protected function executeImpl($js_)
    {
      return $this->connection()->execute($js_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::queryImpl()
     */
    protected function queryImpl($statement_)
    {

    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::invokeImpl()
     */
    protected function invokeImpl($command_)
    {
      return $this->connection()->command($command_());
    }
    //--------------------------------------------------------------------------
  }
?>
