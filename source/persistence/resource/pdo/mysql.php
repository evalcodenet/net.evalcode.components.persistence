<?php


namespace Components;


  /**
   * Persistence_Resource_Pdo_Mysql
   *
   * @package net.evalcode.components.persistence
   * @subpackage resource.pdo
   *
   * @author evalcode.net
   */
  class Persistence_Resource_Pdo_Mysql extends Persistence_Resource_Pdo
  {
    // PREDEFINED PROPERTIES
    const CHARSET_DEFAULT='utf8';
    const COLLATION_DEFAULT='utf8_general_ci';
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Uri $uri_=null)
    {
      parent::__construct($uri_);

      if($uri_->hasQueryParam('charset'))
        $this->charset=$uri_->getQueryParam('charset');
      else
        $this->charset=self::CHARSET_DEFAULT;

      if($uri_->hasQueryParam('collation'))
        $this->collation=$uri_->getQueryParam('collation');
      else
        $this->collation=self::COLLATION_DEFAULT;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
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
      return sprintf('%s@%s{uri: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_uri
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @see \Components\Persistence_Resource_Pdo::createDatabase() \Components\Persistence_Resource_Pdo::createDatabase()
     */
    protected function createDatabase($database_, $username_)
    {
      $this->execute("CREATE DATABASE `{$database_}` DEFAULT CHARACTER SET '{$this->charset}' COLLATE '{$this->collation}'; GRANT ALL PRIVILEGES ON `{$database_}`.* TO '{$username_}'@'127.0.0.1'; FLUSH PRIVILEGES;");
    }

    /**
     * @see \Components\Persistence_Resource_Pdo::driverOptions() \Components\Persistence_Resource_Pdo::driverOptions()
     */
    protected function driverOptions()
    {
      return array(
        \PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES '{$this->charset}' COLLATE '{$this->collation}';"
      );
    }
    //--------------------------------------------------------------------------
  }
?>
