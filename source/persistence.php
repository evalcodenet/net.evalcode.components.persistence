<?php


namespace Components;


  /**
   * Persistence
   *
   * @api
   * @package net.evalcode.components.persistence
   *
   * @author evalcode.net
   */
  class Persistence
  {
    // PREDEFINED PROPERTIES
    const PROFILE='PROFILE';
    const LOG_QUERIES='LOG_QUERIES';
    const LOG_STATEMENTS='LOG_STATEMENTS';

    const BIT_NO_DEBUG=1;
    const BIT_PROFILE=2;
    const BIT_LOG_QUERIES=4;
    const BIT_LOG_STATEMENTS=8;
    //--------------------------------------------------------------------------


    // PROPERTIES
    public static $debugMode=1;
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * @param string $name_
     * @param string[] $args_
     *
     * @return \Components\Persistence_Resource|\Components\Entity_Collection
     */
    public static function __callStatic($name_, array $args_=array())
    {
      if(false===isset(self::$m_resources[$name_]))
        static::resource($name_);

      if(0===count($args_))
        return self::$m_resources[$name_];

      return self::$m_resources[$name_]->view(array_shift($args_))->collection();
    }

    /**
     * @param string $name_
     * @param string[] $connectionUris_
     */
    public static function registerResource($name_, array $connectionUris_)
    {
      self::$m_resourceIdentifiers[$name_]=$connectionUris_;
    }

    /**
     * @param string $name_
     *
     * @return \Components\Persistence_Resource
     */
    public static function resource($name_)
    {
      Config::get('persistence');

      if(false===isset(self::$m_resources[$name_]))
      {
        if(false===isset(self::$m_resourceIdentifiers[$name_]))
        {
          throw new Exception_IllegalArgument('components/persistence', sprintf(
            'No persistence resource configured for given argument [name: %s].', $name_
          ));
        }

        $resource=new Persistence_Resource_Pool();
        $resource->configure(self::$m_resourceIdentifiers[$name_]);

        self::$m_resources[$name_]=$resource;
      }

      return self::$m_resources[$name_]->resource;
    }

    /**
     * @param string $name_
     *
     * @return \Components\Persistence_View
     */
    public static function view($name_, Persistence_Properties $properties_=null)
    {
      $urn=Urn::valueOf($name_);
      if('urn'===$urn->getScheme())
        throw new Exception_IllegalArgument('components/persistence', 'Argument must be formatted like: resource:entity/name.');

      if(false===isset(self::$m_resources[$urn->getScheme()]))
        static::resource($urn->getScheme());

      return self::$m_resources[$urn->getScheme()]->view($urn->getNamespace(), $properties_);
    }

    /**
     * @param string $name_
     *
     * @return \Components\Entity_Collection
     */
    public static function collection($name_, Persistence_Properties $properties_=null)
    {
      $urn=Urn::valueOf($name_);
      if('urn'===$urn->getScheme())
        throw new Exception_IllegalArgument('components/persistence', 'Argument must be formatted like: resource:entity/name.');

      if(false===isset(self::$m_resources[$urn->getScheme()]))
        static::resource($urn->getScheme());

      return self::$m_resources[$urn->getScheme()]->view($urn->getNamespace(), $properties_)->collection();
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var \Components\Persistence_Resource_Pool[]
     */
    private static $m_resources=array();
    private static $m_resourceIdentifiers=array();
    //--------------------------------------------------------------------------
  }
?>
