<?php


namespace Components;


  /**
   * Entity_Properties
   *
   * @package net.evalcode.components
   * @subpackage persistence.entity
   *
   * @author evalcode.net
   *
   * @property string name
   * @property string type
   * @property string collectionName
   * @property string collectionType
   *
   * @property string primaryKey
   * @property boolean autoIncrement
   *
   * @property boolean cache
   * @property string cacheNamespace
   *
   * @internal
   */
  class Entity_Properties extends Properties
  {
    // CONSTRUCTION
    public function __construct($type_, array $properties_=array())
    {
      parent::__construct($properties_);

      $this->type=$type_;
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * @param string $type_
     *
     * @return \Components\Entity_Properties
     */
    public static function forType($type_)
    {
      if(false===isset(self::$m_instance[$type_]))
      {
        $properties=Cache::get("persistence/entity/properties/$type_");

        if(false===$properties)
        {
          self::$m_instance[$type_]=new static($type_);
          self::$m_instance[$type_]->initialize();

          Cache::set("persistence/entity/properties/$type_", self::$m_instance[$type_]->m_properties);
        }
        else
        {
          self::$m_instance[$type_]=new static($type_, $properties);
        }
      }

      return self::$m_instance[$type_];
    }

    /**
     * @param string $name_
     *
     * @return \Components\Entity_Properties
     */
    public static function forName($name_)
    {
      if(false===isset(self::$m_mapTypeByName[$name_]))
        self::$m_mapTypeByName[$name_]=Runtime_Classloader::lookup($name_);

      $type=self::$m_mapTypeByName[$name_];
      if(false===isset(self::$m_instance[$type]))
        return static::forType($type);

      return self::$m_instance[$type];
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private static $m_instance=array();
    private static $m_mapTypeByName=array();
    //-----


    private function initialize()
    {
      $a=Annotations::get($this->type);

      $this->name=Runtime_Classloader::lookupName($this->type);

      if($annotation=$a->getTypeAnnotation(Annotation_Name::NAME))
        $this->collectionName=$a->getTypeAnnotation(Annotation_Name::NAME)->value;
      else
        $this->collectionName=strtolower(substr($this->m_type, strrpos($this->m_type, '_')+1));

      if($annotation=$a->getTypeAnnotation(Annotation_Collection::NAME))
        $this->collectionType=Runtime_Classloader::lookup($annotation->value);
      else
        $this->collectionType=Entity_Collection::TYPE;

      if($annotation=$a->getTypeAnnotation(Annotation_Cache::NAME))
      {
        $this->cache=true;
        $this->cacheNamespace=$annotation->value;
      }
      else
      {
        $this->cache=false;
      }

      $this->autoIncrement=true;
      foreach($a->getPropertyAnnotations() as $property=>$annotations)
      {
        if(isset($annotations[Annotation_Id::NAME]))
        {
          $this->primaryKey=$property;
          $this->autoIncrement=Boolean::valueIsTrue($annotations[Annotation_Id::NAME]->auto);
        }
      }
    }
    //--------------------------------------------------------------------------
  }
?>
