<?php


namespace Components;


  /**
   * Persistence_Resource_Abstract
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource
   *
   * @author evalcode.net
   */
  abstract class Persistence_Resource_Abstract implements Persistence_Resource
  {
    // OVERRIDES/IMPLEMENTS
    /**
     * @var string
     */
    public $charset;
    /**
     * @var string
     */
    public $collation;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Uri $uri_)
    {
      $this->m_uri=$uri_;
      $this->m_cacheNamespace='persistence/resource/'.md5($uri_->hashCode());
      $this->m_objectMapper=new Object_Mapper();
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * @return string
     */
    public static function type()
    {
      return get_called_class();
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::save()
     */
    public function save(Entity $entity_, $collection_=null)
    {
      $type=get_class($entity_);
      $typeProperties=Entity_Properties::forType($type);

      $properties=$this->m_objectMapper->mapObjectOfType($entity_, $type);
      $result=$this->{self::$m_methodSave[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}(
        $typeProperties->collectionName, $properties
      );

      if(false===$result)
        return false;

      if($typeProperties->autoIncrement)
        $entity_->{$typeProperties->primaryKey}=$result;

      if($typeProperties->cache)
        $this->cache($typeProperties->cacheNamespace, $entity_->{$typeProperties->primaryKey}, $properties);

      return true;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::query()
     */
    public function query(Query $query_)
    {
      return $this->{self::$m_methodQuery[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}($query_);
    }

    /**
     * @param string $statement_
     *
     * @throws \Components\Persistence_Exception
     */
    public function execute($statement_)
    {
      return $this->{self::$m_methodExecute[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionBegin()
     */
    public function transactionBegin()
    {
      throw new Persistence_Exception('persistence/resource', 'Transactions are not supported.');
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionCommit()
     */
    public function transactionCommit()
    {
      throw new Persistence_Exception('persistence/resource', 'Transactions are not supported.');
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::transactionRollback()
     */
    public function transactionRollback()
    {
      throw new Persistence_Exception('persistence/resource', 'Transactions are not supported.');
    }

    /**
     * @param string $entity_
     *
     * @return \Components\Entity_Collection
     *
     * @throws \Components\Persistence_Exception
     */
    public function collection($name_)
    {
      $typeProperties=Entity_Properties::forName($name_);
      $collectionType=$typeProperties->collectionType;

      return new $collectionType($this->view($typeProperties->collectionName), $typeProperties->type);
    }

    /**
     * @return boolean
     */
    public function isReadOnly()
    {
      if(null===$this->m_isReadOnly)
      {
        $this->m_isReadOnly=$this->m_uri->hasQueryParam('writable')
          && Boolean::valueIsFalse($this->m_uri->getQueryParam('writable'));
      }

      return $this->m_isReadOnly;
    }
    //--------------------------------------------------------------------------


    // INTERNAL ACCESSORS
    /**
     * @param string $collection_
     * @param array|scalar $properties_
     *
     * @return mixed
     */
    public function debugSave($collection_, array $properties_)
    {
      if(Persistence::$debugMode&Persistence::BIT_PROFILE)
      {
        $time=microtime(true);
      }
      else
      {
        Log::debug('persistence/resource', 'Save [collection: %s, properties: %s].',
          $collection_,
          HashMap::valueOf($properties_)
        );

        return $this->saveImpl($collection_, $properties_);
      }

      $result=$this->saveImpl($collection_, $properties_);
      Log::debug('persistence/resource', 'Save completed in %.5fs [collection: %s, properties; %s].',
        microtime(true)-$time,
        $collection_,
        HashMap::valueOf($properties_)
      );

      return $result;
    }

    /**
     * @param \Components\Query $query_
     */
    public function debugQuery(Query $query_)
    {
      if(Persistence::$debugMode&Persistence::BIT_PROFILE)
      {
        $time=microtime(true);
      }
      else
      {
        Log::debug('persistence/resource', 'Query [%s].', $query_);
        $this->queryImpl($query_);

        return;
      }

      $this->queryImpl($query_);
      Log::debug('persistence/resource', 'Query completed in %.5fs [%s].', microtime(true)-$time, $query_);
    }

    /**
     * @param string $statement_
     *
     * @throws \Components\Persistence_Exception
     */
    public function debugExecute($statement_)
    {
      if(Persistence::$debugMode&Persistence::BIT_PROFILE)
      {
        $time=microtime(true);
      }
      else
      {
        Log::debug('persistence/resource', 'Execute [%s].', $statement_);
        return $this->executeImpl($statement_);
      }

      $result=$this->executeImpl($statement_);
      Log::debug('persistence/resource', 'Execution completed in %.5fs [%s].', microtime(true)-$time, $statement_);

      return $result;
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    protected static $m_methodExecute=array(
      0=>'debugExecute',
      1=>'executeImpl'
    );
    protected static $m_methodQuery=array(
      0=>'debugQuery',
      1=>'queryImpl'
    );
    protected static $m_methodSave=array(
      0=>'debugSave',
      1=>'saveImpl'
    );

    private static $m_cache=array();

    /**
     * @var \Components\Uri
     */
    protected $m_uri;
    /**
     * @var \Components\Object_Mapper
     */
    protected $m_objectMapper;

    /**
     * @var string
     */
    private $m_cacheNamespace;
    /**
     * @var boolean|null
     */
    private $m_isReadOnly;
    //-----


    /**
     * Private cache.
     *
     * @param string $namespace_
     * @param string $key_
     * @param mixed $value_
     *
     * @return mixed
     */
    protected function cache($namespace_, $key_, $value_=null, $ttl_=0)
    {
      $namespace="{$this->m_cacheNamespace}/$namespace_";
      if(false===isset(self::$m_cache[$namespace]))
        self::$m_cache[$namespace]=Cache::get($namespace);

      if(null===$value_)
      {
        if(isset(self::$m_cache[$namespace][$key_]))
          return self::$m_cache[$namespace][$key_];

        return false;
      }

      self::$m_cache[$namespace][$key_]=$value_;
      Cache::set($namespace, self::$m_cache[$namespace], $ttl_);

      return $value_;
    }

    /**
     * @param string $collection_
     * @param array|scalar $properties_
     */
    abstract protected function saveImpl($collection_, array $properties_);

    /**
     * @param \Components\Query $query_
     */
    abstract protected function queryImpl(Query $query_);

    /**
     * @param string $statement_
     */
    abstract protected function executeImpl($statement_);
    //--------------------------------------------------------------------------
  }
?>
