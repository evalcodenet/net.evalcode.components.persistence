<?php


namespace Components;


  /**
   * Persistence_Resource
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   */
  abstract class Persistence_Resource implements Resource
  {
    // PREDEFINED PROPERTIES
    const DEBUG_PROFILE=1;
    const DEBUG_LOG_STATEMENTS=2;
    const DEBUG_LOG_QUERIES=4;

    const METHOD_QUERY='queryImpl';
    const METHOD_QUERY_DEBUG='queryDebug';

    const METHOD_INVOKE='invokeImpl';
    const METHOD_INVOKE_DEBUG='invokeDebug';

    const METHOD_EXECUTE='executeImpl';
    const METHOD_EXECUTE_DEBUG='executeDebug';
    //--------------------------------------------------------------------------


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
    public function __construct(Uri $uri_=null)
    {
      $this->m_uri=$uri_;
      $this->m_cacheNamespace='persistence/resource/'.md5(spl_object_hash($this));
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

    /**
     * @param integer $bitMask_
     */
    public static function debugMode($bitMask_)
    {
      self::$m_debugMode=$bitMask_;

      if(0<(self::$m_debugMode&self::DEBUG_PROFILE))
      {
        self::$m_methodQuery=self::METHOD_QUERY_DEBUG;
        self::$m_methodInvoke=self::METHOD_INVOKE_DEBUG;
        self::$m_methodExecute=self::METHOD_EXECUTE_DEBUG;
      }
      else
      {
        if(0<(self::$m_debugMode&self::DEBUG_LOG_QUERIES))
          self::$m_methodQuery=self::METHOD_QUERY_DEBUG;
        else
          self::$m_methodQuery=self::METHOD_QUERY;

        if(0<(self::$m_debugMode&self::DEBUG_LOG_STATEMENTS))
        {
          self::$m_methodInvoke=self::METHOD_EXECUTE_DEBUG;
          self::$m_methodExecute=self::METHOD_EXECUTE_DEBUG;
        }
        else
        {
          self::$m_methodInvoke=self::METHOD_EXECUTE;
          self::$m_methodExecute=self::METHOD_EXECUTE;
        }
      }
    }
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    /**
     * Return underlying native driver or resource.
     *
     * @return mixed
     *
     * @throws \Components\Persistence_Exception
     */
    abstract public function driver();

    /**
     * Return underlying connection or resource.
     *
     * @return mixed
     *
     * @throws \Components\Persistence_Exception
     */
    abstract public function connection();

    abstract public function collectionExists($name_);
    abstract public function collectionCreate($name_);
    abstract public function collectionDrop($name_);

    /**
     * @param string $statement_
     */
    public function query($statement_)
    {
      return $this->queryImpl($statement_);
    }

    /**
     * @param string $statement_
     */
    public function queryDebug($statement_)
    {
      Log::debug('persistence/resource', $statement_);

      return $this->queryImpl($statement_);
    }

    /**
     * @param \Components\Command|\Callable|\Closure $command_
     *
     * @throws \Components\Persistence_Exception
     */
    public function invoke($command_)
    {
      return $this->{self::$m_methodInvoke}($command_);
    }

    /**
     * @param \Components\Command|\Callable|\Closure $command_
     *
     * @throws \Components\Persistence_Exception
     */
    public function invokeDebug($command_)
    {
      if($command_ instanceof Command)
      {
        Log::debug('persistence/resource', 'Invoking command [%s].', $command_);
      }
      else
      {
        $function=new \ReflectionFunction($command_);
        Log::debug('persistence/resource', 'Invoking command [%s].', $function->getDocComment());
      }

      return $this->invokeImpl($command_);
    }

    /**
     * @param string $statement_
     *
     * @throws \Components\Persistence_Exception
     */
    public function execute($statement_)
    {
      return $this->{self::$m_methodExecute}($statement_);
    }

    /**
     * @param string $statement_
     *
     * @throws \Components\Persistence_Exception
     */
    public function executeDebug($statement_)
    {
      Log::debug('persistence/resource', $statement_);

      return $this->executeImpl($statement_);
    }

    public function transactionBegin()
    {
      throw new Persistence_Exception('persistence/resource', 'Transactions are not supported.');
    }

    public function transactionCommit()
    {
      throw new Persistence_Exception('persistence/resource', 'Transactions are not supported.');
    }

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


    // IMPLEMENTATION
    protected static $m_debugMode=0;
    protected static $m_methodQuery=self::METHOD_QUERY;
    protected static $m_methodInvoke=self::METHOD_INVOKE;
    protected static $m_methodExecute=self::METHOD_EXECUTE;

    /**
     * @var string
     */
    protected $m_cacheNamespace;
    /**
     * @var \Components\Uri
     */
    protected $m_uri;
    /**
     * @var boolean|null
     */
    private $m_isReadOnly;
    //--------------------------------------------------------------------------


    /**
     * @param string $statement_
     */
    abstract protected function queryImpl($statement_);

    /**
     * @param \Components\Command|\Callable|\Closure $command_
     */
    abstract protected function invokeImpl($command_);

    /**
     * @param string $statement_
     */
    abstract protected function executeImpl($statement_);
    //--------------------------------------------------------------------------
  }
?>
