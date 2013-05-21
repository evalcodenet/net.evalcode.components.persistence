<?php


namespace Components;


  /**
   * Persistence_Scriptlet_Test
   *
   * @package net.evalcode.components
   * @subpackage persistence.scriptlet
   *
   * @author evalcode.net
   */
  class Persistence_Scriptlet_Test extends Http_Scriptlet
  {
    // ACCESSORS
    public function get()
    {
      Persistence::resource('sql')->driver();
    }

    public function post()
    {
      return $this->get();
    }
    //--------------------------------------------------------------------------
  }
?>
