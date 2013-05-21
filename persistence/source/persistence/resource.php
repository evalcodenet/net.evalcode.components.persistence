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
    const METHOD_EXECUTE='executeImpl';
    const METHOD_EXECUTE_LOGGED='executeLogged';
    //--------------------------------------------------------------------------


    // PROPERTIES
    public static $logStatements=false;
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
     * @param boolean $enabled_
     */
    public static function logStatements($enabled_=true)
    {
      self::$logStatements=$enabled_;

      if(true===$enabled_)
        self::$m_methodExecute=self::METHOD_EXECUTE_LOGGED;
      else
        self::$m_methodExecute=self::METHOD_EXECUTE;
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
     * @param string $query_
     *
     * @throws \Components\Persistence_Exception
     */
    public function execute($query_)
    {
      return $this->{self::$m_methodExecute}($query_);
    }

    /**
     * @param string $query_
     *
     * @throws \Components\Persistence_Exception
     */
    public function executeLogged($query_)
    {
      Log::debug('persistence/resource', $query_);

      return $this->executeImpl($query_);
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
    protected static $m_methodExecute=self::METHOD_EXECUTE;

    /**
     * @var \Components\Uri
     */
    protected $m_uri;
    /**
     * @var boolean|null
     */
    private $m_isReadOnly;
    //--------------------------------------------------------------------------


    abstract protected function executeImpl($query_);
    //--------------------------------------------------------------------------
  }


  // FIXME Find a better place (without initializer methods) ...
  Debug::addFlagListener(function() {
    Persistence_Resource::logStatements(Debug::enabled(Persistence::LOG_STATEMENTS));
  });
?>
