<?php


namespace Components;


  /**
   * Persistence_Resource_Schema
   *
   * @package net.evalcode.components
   * @subpackage persistence.resource
   *
   * @author evalcode.net
   *
   * @application persistence
   */
  class Persistence_Resource_Schema extends Rest_Resource
  {
    // ACCESSORS/MUTATORS
    /**
     * @GET
     * @POST
     */
    public function update()
    {
      foreach(Runtime_Classloader::get()->getClasspaths() as $type=>$path)
      {
        if(Entity_Default::TYPE!==$type)
        {
          $class=new \ReflectionClass($type);
          if($class->isSubclassOf('Components\\Entity'))
            $this->updateForEntity($type);
        }
      }
    }
    //--------------------------------------------------------------------------


    // INTERNAL ACCESSORS
    public function updateForEntity($entityType_)
    {
      $properties=Persistence_Properties::forEntityType($entityType_);

      $name=$properties->collectionName;
      $primaryKey=$properties->collectionPrimaryKey;


      // TODO Migrate schema ...
    }
    //--------------------------------------------------------------------------
  }
?>
