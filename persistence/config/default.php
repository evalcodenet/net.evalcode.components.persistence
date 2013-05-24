<?php


namespace Components;


  Annotations::registerAnnotations(array(
    Annotation_Cache::NAME=>Annotation_Cache::TYPE,
    Annotation_Collection::NAME=>Annotation_Collection::TYPE,
    Annotation_Id::NAME=>Annotation_Id::TYPE,
    Annotation_Transient::NAME=>Annotation_Transient::TYPE
  ));


  Resource_Type::registerResourceType('mysql', Persistence_Resource_Pdo_Mysql::type());
  Resource_Type::registerResourceType('mongodb', Persistence_Resource_Mongodb::type());


  Persistence_Resource_Schema::serve('schema');
  Persistence_Scriptlet_Test::serve('test');


  Debug::addFlagListener(function($active_, array $flags_)
  {
    if($active_)
    {
      $bits=array();
      if(isset($flags_[Persistence::LOG_STATEMENTS]))
        $bits[]=Persistence::BIT_LOG_STATEMENTS;
      if(isset($flags_[Persistence::LOG_QUERIES]))
        $bits[]=Persistence::BIT_LOG_QUERIES;
      if(isset($flags_[Persistence::PROFILE]))
        $bits[]=Persistence::BIT_PROFILE;

      Persistence::$debugMode=Bitmask::getBitmaskForBits($bits);
    }
    else
    {
      Persistence::$debugMode=Persistence::BIT_NO_DEBUG;
    }
  });
?>
