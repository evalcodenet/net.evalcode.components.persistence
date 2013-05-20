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
  class Persistence_Resource_Mongodb extends Persistence_Resource_Pool
  {
    // OVERRIDES/IMPLEMENTS
    public function configure(array $resourceIdentifiers_)
    {
      $this->m_resourceIdentifiers=$resourceIdentifiers_;

      $this->resource=$this;
      $this->resourceReadOnly=$this;
    }

/*
    public function connection()
    {
      if(null===$this->m_connection)
      {
        $socket='127.0.0.1';
        $dir=@opendir(sys_get_temp_dir());
        while($entry=@readdir($dir))
        {
          if(preg_match('/mongo[-\D.sock]/', $entry))
          {
            $socket="/tmp/$entry";

            break;
          }
        }

        @closedir($dir);

        $this->m_connection=new \Mongo("mongodb://$socket");
      }

      return $this->m_connection;
    }

    public function db()
    {
      if(null===$this->m_db)
        $this->m_db=$this->connection()->{'asia_tmogroup_components_'.Runtime::getInstanceNamespace()};

      return $this->m_db;
    }

    public function collection($name_=null)
    {
      return $this->db()->$name_;
    }
*/

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
    private $m_resourceIdentifiers=array();
    /*
    private static $m_instance;
    private $m_connection;
    private $m_db;
    */
    //--------------------------------------------------------------------------
  }
?>
