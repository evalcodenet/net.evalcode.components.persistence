<?php


namespace Components;


  /**
   * Persistence_View
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   *
   * @property string name
   * @property \Components\Persistence_Resource resource
   */
  interface Persistence_View extends Object
  {
    function save(Entity $entity_);
  }
?>
