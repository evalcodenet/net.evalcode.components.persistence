<?php


namespace Components;


  /**
   * Query_Mongodb
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   *
   * @method \Components\Query_Mongodb CREATE_COLLECTION
   * @method \Components\Query_Mongodb DROP_COLLECTION
   */
  class Query_Mongodb extends Enumeration implements Query
  {
    // PREDEFINED PROPERTIES
    const CREATE_COLLECTION='create';
    const DROP_COLLECTION='drop';
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    public function __construct($key_, $name_, $value_, array $options_=array())
    {
      parent::__construct($key_, $name_);

      $this->m_value=$value_;
      $this->m_options=$options_;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Callable::__invoke()
     */
    public function __invoke()
    {
      return array_merge(array($this->name()=>$this->m_value), $this->m_options);
    }

    public function result($result_=null)
    {
      if(null===$result_)
        return $this->m_result;

      $this->m_result=$result_;
    }

    public function ok()
    {
      return isset($this->m_result['ok']) && 1===(int)$this->m_result['ok'];
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Enumeration::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{name: %s, value: %s, options: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->m_name,
        $this->m_value,
        Arrays::toString($this->m_options)
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private $m_options=array();
    private $m_value;
    private $m_result;
    //-----


    public static function values()
    {
      return array(
        'CREATE_COLLECTION',
        'DROP_COLLECTION'
      );
    }
    //--------------------------------------------------------------------------
  }
?>
