<?php


namespace Components;


  /**
   * Persistence_Backend_Pdo
   *
   * @package net.evalcode.components
   * @subpackage persistence.backend
   *
   * @author evalcode.net
   */
  class Persistence_Backend_Pdo extends Persistence_Backend
  {
    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Backend::collection()
     */
    public function collection($name_)
    {
      $collection=parent::collection($name_);

      // TODO Initialize / update schema ... or wait until first access?

      return $collection;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Persistence_Backend::view()
     */
    public function view($name_)
    {
      return new Persistence_View_Pdo($this, $name_);
    }
    //--------------------------------------------------------------------------
  }
?>
