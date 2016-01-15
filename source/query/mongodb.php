<?php


namespace Components;


  /**
   * Query_Mongodb
   *
   * @package net.evalcode.components.persistence
   * @subpackage query
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


    // OVERRIDES
    public function __construct($key_, $name_, $value_, array $options_=[])
    {
      parent::__construct($key_, $name_);

      $this->m_value=$value_;
      $this->m_options=$options_;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS/MUTATORS
    /**
     * @return boolean
     */
    public function ok()
    {
      return isset($this->m_result['ok']) && 1===(int)$this->m_result['ok'];
    }

    /**
     * @param scalar $result_
     *
     * @return scalar
     */
    public function result($result_=null)
    {
      if(null===$result_)
        return $this->m_result;

      $this->m_result=$result_;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * @see \Components\Closure::__invoke() __invoke
     */
    public function __invoke()
    {
      return array_merge([$this->name()=>$this->m_value], $this->m_options);
    }

    /**
     * @see \Components\Enumeration::__toString() __toString
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
    /**
     * @var scalar[]
     */
    private $m_options=[];
    /**
     * @var scalar
     */
    private $m_value;
    /**
     * @var scalar
     */
    private $m_result;
    //-----


    public static function values()
    {
      return [
        'CREATE_COLLECTION',
        'DROP_COLLECTION'
      ];
    }
    //--------------------------------------------------------------------------
  }
?>
