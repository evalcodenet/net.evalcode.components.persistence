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
      $resource=Persistence::nosql()->view('foo');

      $foo=new Entity_Foo();
      $foo->name='Foo Bar';
      $foo->createdAt=Date::now();
      $resource->save($foo);

      $foo=new Entity_Foo();
      $foo->name='Foo Bar';
      $foo->createdAt=Date::now();
      $resource->save($foo);

      var_dump($foo);
    }

    public function post()
    {
      return $this->get();
    }
    //--------------------------------------------------------------------------
  }
?>
