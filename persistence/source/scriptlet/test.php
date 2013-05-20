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
      // backend > resource
      var_dump((string)Persistence::sql()->resource);

      // resource
      var_dump((string)Persistence::resource('nosql'));

      // backend
      var_dump((string)Persistence::sql());

      // backend
      var_dump((string)Persistence::backend('nosql'));

      // backend > view
      var_dump((string)Persistence::nosql()->view('foo'));

      // backend > checked collection backed by view
      var_dump((string)Persistence::nosql('components/entity/foo'));

      // backend > checked collection backed by view
      var_dump((string)Persistence::collection('nosql:components/entity/foo'));

      // checked view
      var_dump((string)Persistence::view('nosql:components/entity/foo'));

      // checked view
      var_dump((string)Persistence::collection('nosql:components/entity/foo')->view);
    }

    public function post()
    {
      return $this->get();
    }
    //--------------------------------------------------------------------------
  }
?>
