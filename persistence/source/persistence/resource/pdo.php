<?php


namespace Components;


  /**
   * Persistence_Resource_Pdo
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource
   *
   * @author evalcode.net
   */
  abstract class Persistence_Resource_Pdo extends Persistence_Resource
  {
    // OVERRIDES/IMPLEMENTS
    public function collectionExists($name_)
    {

    }

    public function collectionCreate($name_)
    {

    }

    public function collectionDrop($name_)
    {

    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::connection()
     *
     * @return \PDO
     */
    public function connection()
    {
      if(null===$this->m_driver)
        return $this->driver();

      return $this->m_driver;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::driver()
     *
     * @return \PDO
     */
    public function driver()
    {
      if(null===$this->m_driver)
      {
        if($host=$this->m_uri->getHost())
        {
          $dsn="{$this->m_uri->getScheme()}:host=$host";

          $database=$this->m_uri->getPathParam(0);
          $username=$this->m_uri->getUsername();
          $password=$this->m_uri->getPassword();
        }
        else
        {
          $dsn="{$this->m_uri->getScheme()}:unix_socket={$this->m_uri->getPath()}";

          $database=$this->m_uri->getFragment();
          $username=$this->m_uri->getQueryParam('username');
          $password=$this->m_uri->getQueryParam('password');
        }

        $create=false;

        try
        {
          if(0<(self::$m_debugMode&Persistence_Resource::DEBUG_LOG_STATEMENTS))
            Log::debug('persistence/resource/pdo', 'Connecting to database [%s;dbname=%s].', $dsn, $database);

          $this->m_driver=new \PDO("$dsn;dbname=$database", $username, $password, $this->driverOptions());
        }
        catch(\PDOException $e)
        {
          if(Persistence_Exception_Pdo::ERROR_UNKNOWN_DATABASE===$e->getCode()
            && Boolean::valueIsTrue($this->m_uri->getQueryParam('create')))
            $create=true;
          else
            throw new Persistence_Exception_Pdo('persistence/resource/pdo', 'Failed to connect to database.', $e);
        }

        if($create)
        {
          try
          {
            if(0<(self::$m_debugMode&Persistence_Resource::DEBUG_LOG_STATEMENTS))
              Log::debug('persistence/resource/pdo', 'Connecting to database [%s].', $dsn);

            $this->m_driver=new \PDO($dsn, $username, $password, $this->driverOptions());
          }
          catch(\PDOException $e)
          {
            throw new Persistence_Exception_Pdo('persistence/resource/pdo', 'Failed to connect to database system.', $e);
          }

          $this->createDatabase($database, $username);
          $this->execute("USE $database;");
        }

        // TODO (CSH) Can be added to driverOptions()?
        $this->m_driver->setAttribute(\PDO::ATTR_AUTOCOMMIT,
          $this->m_uri->hasQueryParam('autocommit')
            && Boolean::valueIsTrue($this->m_uri->getQueryParam('autocommit'))
        );
      }

      return $this->m_driver;
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var \PDO
     */
    private $m_driver;
    //-----


    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::executeImpl()
     */
    protected function executeImpl($statement_)
    {
      return $this->driver()->exec($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::queryImpl()
     */
    protected function queryImpl($statement_)
    {
      return $this->driver()->query($statement_);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Resource::invokeImpl()
     */
    protected function invokeImpl($callable_)
    {
      return $this->driver()->query($callable_());
    }

    /**
     * @param string $dsn_
     * @param string $database_
     * @param string $username_
     * @param string $password_
     *
     * @throws \Components\Persistence_Exception
     *
     * @todo May need refactoring if underlying DBMS does not support
     * connection without database. Implementations for systems that
     * do not support creation of database at all may throw a
     * corresponding exception.
     */
    protected function createDatabase($database_, $username_)
    {
      // Override for vendor specific implementation ...
      throw new Persistence_Exception('persistence/resource/pdo',
        'Automatic database creation is not supported for this DBMS.'
      );
    }

    /**
     * @return array
     */
    protected function driverOptions()
    {
      // Override for vendor specific PDO connection properties ...
      return array();
    }
    //--------------------------------------------------------------------------
  }
?>
