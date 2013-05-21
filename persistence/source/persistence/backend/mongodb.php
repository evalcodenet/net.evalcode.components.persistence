<?php


namespace Components;


  /**
   * Persistence_Backend_Mongodb
   *
   * @package net.evalcode.components
   * @subpackage persistence.backend
   *
   * @author evalcode.net
   */
  class Persistence_Backend_Mongodb extends Persistence_Backend
  {
    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Backend::view()
     */
    public function view($name_)
    {
      return new Persistence_View_Mongodb($this, $name_);
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * Scans default locations for a mongodb server socket.
     *
     * @return string
     */
    public static function lookupSocket()
    {
      /* @var $path Io_Path */
      foreach(Io::systemTmpPath() as $path)
      {
        if(preg_match('/mongo[-\D.sock]/', $path->getPath()))
          return $path->getPath();
      }

      return null;
    }
    //--------------------------------------------------------------------------
  }
?>
