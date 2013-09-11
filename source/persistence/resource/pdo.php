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
  abstract class Persistence_Resource_Pdo extends Persistence_Resource_Abstract
  {
    // OVERRIDES/IMPLEMENTS
    /**     * @see \Components\Persistence_Resource::view() \Components\Persistence_Resource::view()
     *
     * @return \Components\Persistence_View_Pdo
     */
    public function view($name_, Persistence_Properties $properties_=null)
    {
      if(null===$properties_)
        $properties_=Persistence_Properties::forEntityName($name_);

      if(null===$properties_)
      {
        Log::debug('persistence/resource/pdo', 'Given argument is not a valid entity name. Continuing with generic properties [%s].', $name_);

        $properties_=Persistence_Properties::generic($name_);
      }

      return new Persistence_View_Pdo($this, $properties_);
    }

    /**     * @see \Components\Persistence_Resource::connection() \Components\Persistence_Resource::connection()
     *
     * @return \PDO
     */
    public function connection()
    {
      if(null===$this->m_driver)
        return $this->driver();

      return $this->m_driver;
    }

    /**     * @see \Components\Persistence_Resource::driver() \Components\Persistence_Resource::driver()
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
          if(0!==Persistence::$debugMode&Persistence::BIT_LOG_STATEMENTS)
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
            if(0!==Persistence::$debugMode&Persistence::BIT_LOG_STATEMENTS)
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

    /**     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
    }

    /**     * @see \Components\Object::equals() \Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**     * @see \Components\Object::__toString() \Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{uri: %s}',
        get_class($this),
        $this->hashCode(),
        $this->m_uri
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var \PDO
     */
    private $m_driver;
    //-----


    /**     * @see \Components\Persistence_Resource_Abstract::findImpl() \Components\Persistence_Resource_Abstract::findImpl()
     */
    protected function findImpl($table_, $property_, $value_)
    {

    }

    /**     * @see \Components\Persistence_Resource_Abstract::saveImpl() \Components\Persistence_Resource_Abstract::saveImpl()
     */
    protected function saveImpl($table_, $primaryKey_, array $record_)
    {

    }

    /**     * @see \Components\Persistence_Resource_Abstract::removeImpl() \Components\Persistence_Resource_Abstract::removeImpl()
     */
    protected function removeImpl($table_, $property_, $value_)
    {

    }

    /**     * @see \Components\Persistence_Resource::queryImpl() \Components\Persistence_Resource::queryImpl()
     */
    protected function queryImpl(Query $query_)
    {
      return $this->driver()->query($query_($this->driver()));
    }

    /**     * @see \Components\Persistence_Resource::executeImpl() \Components\Persistence_Resource::executeImpl()
     */
    protected function executeImpl($statement_)
    {
      return $this->driver()->exec($statement_);
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
