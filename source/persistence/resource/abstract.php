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
    public function save($table_, $primaryKey_, array $record_)
    {
      $result=$this->{self::$m_methodSave[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}(
        $table_, $primaryKey_, $record_
      );

      if(null===$result)
        return null;

      $record_[$primaryKey_]=$result;

      $this->addToResultsCache($table_, $primaryKey_, $result, $record_);

      return $result;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::find()
     */
    public function find($table_, $property_, $value_)
    {
      if($record=$this->loadFromResultsCache($table_, $property_, $value_))
        return $record;

      $record=$this->{self::$m_methodFind[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}(
        $table_, $property_, $value_
      );

      if($record)
      {
        $this->addToResultsCache($table_, $property_, $value_, $record);

        return $record;
      }

      return null;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::remove()
     */
    public function remove($table_, $property_, $value_)
    {
      $result=$this->{self::$m_methodRemove[Persistence::$debugMode&Persistence::BIT_NO_DEBUG]}(
        $table_, $property_, $value_
      );

      if($result)
      {
        $this->removeFromResultsCache($table_, $property_, $value_);

        return true;
      }

      return false;
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
    public function debugSave($table_, $primaryKey_, array $record_)
    {
      if(Persistence::$debugMode&Persistence::BIT_PROFILE)
      {
        $time=microtime(true);
      }
      else
      {
        Log::debug('persistence/resource', 'Save [table: %s, record: %s].',
          $table_,
          HashMap::valueOf($record_)
        );

        return $this->saveImpl($table_, $primaryKey_, $record_);
      }

      $result=$this->saveImpl($table_, $primaryKey_, $record_);
      Log::debug('persistence/resource', 'Save completed in %.5fs [table: %s, record; %s].',
        microtime(true)-$time,
        $table_,
        HashMap::valueOf($record_)
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
    protected static $m_methodFind=array(
      0=>'debugFind',
      1=>'findImpl'
    );
    protected static $m_methodSave=array(
      0=>'debugSave',
      1=>'saveImpl'
    );
    protected static $m_methodRemove=array(
      0=>'debugRemov',
      1=>'removeImpl'
    );
    /**
     * @var \Components\Object_Mapper
     */
    protected static $m_objectMapper;

    private static $m_cache=array();
    private static $m_cacheResults=array();
    private static $m_cacheFlushed=false;

    /**
     * @var \Components\Uri
     */
    protected $m_uri;

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
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     *
     * @return array|scalar
     */
    abstract protected function findImpl($table_, $property_, $value_);

    /**
     * @param string $table_
     * @param string $primaryKey_
     * @param array|scalar $record_
     *
     * @return scalar
     */
    abstract protected function saveImpl($table_, $primaryKey_, array $record_);

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     *
     * @return boolean
     */
    abstract protected function removeImpl($table_, $property_, $value_);

    /**
     * @param \Components\Query $query_
     */
    abstract protected function queryImpl(Query $query_);

    /**
     * @param string $statement_
     */
    abstract protected function executeImpl($statement_);


    // INTERNAL QUERY, 2ND & 3RD LEVEL CACHE
    /**
     * Internal resource & properties cache.
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

      return $value_;
    }

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     * @param array|scalar $record_
     */
    protected function addToResultsCache($table_, $property_, $value_, array $record_)
    {
      self::$m_cacheResults[$table_][$property_][$value_]=$record_;
    }

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     *
     * @return false|array|scalar $record_
     */
    protected function loadFromResultsCache($table_, $property_, $value_)
    {
      if(false===isset(self::$m_cacheResults[$table_]))
        self::$m_cacheResults[$table_]=Cache::get("persistence/resource/results/$table_");

      if(false===isset(self::$m_cacheResults[$table_][$property_][$value_]))
        return false;

      return self::$m_cacheResults[$table_][$property_][$value_];
    }

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     * @param array|scalar $record_
     */
    protected function removeFromResultsCache($table_, $property_, $value_)
    {
      if(isset(self::$m_cacheResults[$table_][$property_][$value_]))
        self::$m_cacheResults[$table_][$property_][$value_]=null;
    }
    //--------------------------------------------------------------------------


    // DESTRUCTION
    // FIXME Bad ... but maybe cool
    public function __destruct()
    {
      if(false===self::$m_cacheFlushed)
      {
        foreach(self::$m_cache as $namespace=>$bucket)
        {
          Log::debug('persistence/resource', 'Flushing resource cache bucket %s.', $namespace);
          Cache::set($namespace, $bucket);
        }

        foreach(self::$m_cacheResults as $table=>$results)
        {
          Log::debug('persistence/resource', 'Flushing cache bucket persistence/resource/results/%s.', $table);
          Cache::set("persistence/resource/results/$table", $results);
        }

        self::$m_cacheFlushed=true;
      }
    }
    //--------------------------------------------------------------------------
  }
?>
