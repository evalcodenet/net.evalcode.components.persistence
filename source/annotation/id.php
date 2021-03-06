<?php


namespace Components;


  /**
   * Annotation_Id
   *
   * @package net.evalcode.components.persistence
   * @subpackage annotation
   *
   * @author evalcode.net
   *
   * @api
   */
  class Annotation_Id extends Annotation
  {
    // PREDEFINED PROPERTIES
    /**
     * id
     *
     * @var string
     */
    const NAME='id';
    /**
     * Annotation_Id
     *
     * @var string
     */
    const TYPE=__CLASS__;
    //--------------------------------------------------------------------------


    // PROPERTIES
    /**
     * @var boolean
     */
    public $auto=true;
    //--------------------------------------------------------------------------
  }
?>
