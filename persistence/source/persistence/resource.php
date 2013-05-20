<?php


namespace Components;


  /**
   * Persistence_Resource
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   */
  abstract class Persistence_Resource implements Resource
  {
    // CONSTRUCTION
    public function __construct(Uri $uri_=null)
    {
      $this->m_uri=$uri_;
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * @return string
     */
    public static function type()
    {
      return get_called_class();
    }
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    /**
     * @return boolean
     */
    public function isReadOnly()
    {
      return $this->m_uri->hasQueryParam('writable')
        && Boolean::valueIsFalse($this->m_uri->getQueryParam('writable'));
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    protected $m_uri;
    //--------------------------------------------------------------------------
  }
?>
