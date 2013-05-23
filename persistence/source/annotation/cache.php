<?php


namespace Components;


  /**
   * Annotation_Cache
   *
   * @package net.evalcode.components
   * @subpackage annotation
   *
   * @author evalcode.net
   */
  class Annotation_Cache extends Annotation
  {
    // PREDEFINED PROPERTIES
    /**
     * cache
     *
     * @var string
     */
    const NAME='cache';
    /**
     * Annotation_Cache
     *
     * @var string
     */
    const TYPE=__CLASS__;
    //--------------------------------------------------------------------------


    // PROPERTIES
    /**
     * Cache namespace.
     *
     * @var string
     */
    public $value='entity';
    //--------------------------------------------------------------------------
  }
?>
