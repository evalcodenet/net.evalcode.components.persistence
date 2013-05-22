<?php


namespace Components;


  /**
   * Command_Mongodb
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   *
   * @method \Components\Command_Mongodb CREATE_COLLECTION
   */
  class Command_Mongodb extends Enumeration implements Command
  {
    // PREDEFINED PROPERTIES
    const CREATE_COLLECTION='create';
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    public function __construct($name_, $value_, array $options_=array())
    {
      parent::__construct($name_);

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
    //-----


    public static function values()
    {
      return array(
        'CREATE_COLLECTION'
      );
    }
    //--------------------------------------------------------------------------
  }
?>
