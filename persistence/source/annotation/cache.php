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
     * @var string
     */
    public $namespace='persistence/cache/entity';
    /**
     * @var integer
     */
    public $ttl=0;
    //--------------------------------------------------------------------------
  }
?>
