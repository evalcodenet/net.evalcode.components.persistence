<?php


namespace Components;


  /**
   * Persistence_Exception_Pdo
   *
   * @package net.evalcode.components
   * @subpackage persistence.exception
   *
   * @author evalcode.net
   */
  class Persistence_Exception_Pdo extends Persistence_Exception
  {
    // PREDEFINED PROPERTIES
    const ERROR_ACCESS_DENIED=1044;
    const ERROR_UNKNOWN_DATABASE=1049;
    //--------------------------------------------------------------------------
  }
?>
