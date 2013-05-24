<?php


namespace Components;


  /**
   * Persistence_Properties
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   *
   * @property string entityName
   * @property string entityType
   *
   * @property string collectionName
   * @property string collectionType
   * @property string collectionPrimaryKey
   *
   * @property boolean collectionPrimaryKeyAutoIncrement
   *
   * @internal
   */
  class Persistence_Properties extends Properties
  {
    // STATIC ACCESSORS
    /**
     * @param string $entityType_
     *
     * @return \Components\Persistence_Properties
     */
    public static function forEntityType($entityType_)
    {
      if(false===isset(self::$m_instance[$entityType_]))
      {
        $cacheKey='persistence/properties/'.md5($entityType_);
        $properties=Cache::get($cacheKey);

        if(false===$properties)
        {
          self::$m_instance[$entityType_]=new static();
          self::$m_instance[$entityType_]->entityType=$entityType_;
          self::$m_instance[$entityType_]->initialize();

          Cache::set($cacheKey, self::$m_instance[$entityType_]->m_properties);
        }
        else
        {
          self::$m_instance[$entityType_]=new static($properties);
        }
      }

      return self::$m_instance[$entityType_];
    }

    /**
     * @param string $entityName_
     *
     * @return \Components\Persistence_Properties
     */
    public static function forEntityName($entityName_)
    {
      if(false===isset(self::$m_mapEntityTypeByEntityName[$entityName_]))
      {
        self::$m_mapEntityTypeByEntityName[$entityName_]=Runtime_Classloader::lookup($entityName_);

        if(false===isset(self::$m_mapEntityTypeByEntityName[$entityName_]))
          return null;
      }

      $entityType=self::$m_mapEntityTypeByEntityName[$entityName_];
      if(false===isset(self::$m_instance[$entityType]))
        return static::forEntityType($entityType);

      return self::$m_instance[$entityType];
    }

    /**
     * @param string $collectionName_
     * @param string $primaryKey_
     *
     * @return \Components\Persistence_Properties
     */
    public static function generic($collectionName_)
    {
      $collectionName_=String::namespaceToTableName($collectionName_);

      $instance=new static();
      $instance->collectionName=$collectionName_;
      $instance->collectionType=Entity_Collection::TYPE;
      $instance->collectionPrimaryKey=Entity_Default::PRIMARY_KEY_DEFAULT;
      $instance->collectionPrimaryKeyAutoIncrement=true;
      $instance->entityName=$collectionName_;
      $instance->entityType=Entity_Default::TYPE;

      return $instance;
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private static $m_instance=array();
    private static $m_mapEntityTypeByEntityName=array();
    //-----


    private function initialize()
    {
      $a=Annotations::get($this->entityType);

      $this->entityName=Runtime_Classloader::lookupName($this->entityType);

      if($annotation=$a->getTypeAnnotation(Annotation_Name::NAME))
        $this->collectionName=$a->getTypeAnnotation(Annotation_Name::NAME)->value;
      else
        $this->collectionName=strtolower(substr($this->entityType, strrpos($this->entityType, '_')+1));

      if($annotation=$a->getTypeAnnotation(Annotation_Collection::NAME))
        $this->collectionType=Runtime_Classloader::lookup($annotation->value);
      else
        $this->collectionType=Entity_Collection::TYPE;

      $this->autoIncrement=true;
      foreach($a->getPropertyAnnotations() as $property=>$annotations)
      {
        if(isset($annotations[Annotation_Id::NAME]))
        {
          $this->collectionPrimaryKey=$property;
          $this->collectionPrimaryKeyAutoIncrement=Boolean::valueIsTrue($annotations[Annotation_Id::NAME]->auto);
        }
      }
    }
    //--------------------------------------------------------------------------
  }
?>
