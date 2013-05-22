<?php


namespace Components;


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


  Debug::addFlagListener(function() {

    $mask=0;
    if(Debug::enabled(Persistence::LOG_STATEMENTS))
      $mask+=Persistence_Resource::DEBUG_LOG_STATEMENTS;
    if(Debug::enabled(Persistence::LOG_QUERIES))
      $mask+=Persistence_Resource::DEBUG_LOG_QUERIES;
    if(Debug::enabled(Persistence::PROFILE))
      $mask+=Persistence_Resource::DEBUG_PROFILE;

    Persistence_Resource::debugMode($mask);
  });
?>
