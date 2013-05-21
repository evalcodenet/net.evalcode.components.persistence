<?php


namespace Components;


  // FIXME Find a better place (without initializer methods) or implement support for "default" configurations.
  Annotations::registerAnnotations(array(
    Annotation_Cache::NAME=>Annotation_Cache::TYPE,
    Annotation_Collection::NAME=>Annotation_Collection::TYPE,
    Annotation_Id::NAME=>Annotation_Id::TYPE,
    Annotation_Resource::NAME=>Annotation_Resource::TYPE,
    Annotation_Transient::NAME=>Annotation_Transient::TYPE
  ));

  Resource_Type::registerResourceType('mysql', Persistence_Resource_Pdo_Mysql::type());
  Resource_Type::registerResourceType('mongodb', Persistence_Resource_Mongodb::type());

  Persistence_Scriptlet_Test::serve('test');
?>
