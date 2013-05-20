<?php


namespace Components;


  /**
   * Persistence
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   */
  class Persistence
  {
    // STATIC ACCESSORS
    /**
     * @param string $name_
     * @param array|string $args_
     *
     * @return \Components\Persistence_Backend|\Components\Entity_Collection
     */
    public static function __callStatic($name_, array $args_=array())
    {
      if(false===isset(self::$m_backends[$name_]))
        static::backend($name_);

      if(0===count($args_))
        return self::$m_backends[$name_];

      return self::$m_backends[$name_]->collection(array_shift($args_));
    }

    /**
     * @param string $name_
     * @param array|string $connectionUris_
     */
    public static function registerResource($name_, array $connectionUris_)
    {
      self::$m_resourceIdentifiers[$name_]=$connectionUris_;
    }

    /**
     * @param string $type_
     * @param string $name_
     */
    public static function registerBackend($name_, $type_)
    {
      self::$m_backendTypes[$name_]=$type_;
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

        $identifier=reset(self::$m_resourceIdentifiers[$name_]);
        $resource=Uri::valueOf($identifier)->getResource();

        if(!$resource instanceof Persistence_Resource_Pool)
          $resource=new Persistence_Resource_Pool();

        $resource->configure(self::$m_resourceIdentifiers[$name_]);

        self::$m_resources[$name_]=$resource;
      }

      return self::$m_resources[$name_];
    }

    /**
     * @param string $name_
     *
     * @return \Components\Persistence_Backend
     *
     * @throws \Components\Exception_IllegalArgument
     */
    public static function backend($name_)
    {
      Config::get('persistence');

      if(false===isset(self::$m_backends[$name_]))
      {
        if(false===isset(self::$m_backendTypes[$name_]))
        {
          throw new Exception_IllegalArgument('components/persistence', sprintf(
            'No persistence backend configured for given argument [name: %s].', $name_
          ));
        }

        self::$m_backends[$name_]=new self::$m_backendTypes[$name_](self::resource($name_));
      }

      return self::$m_backends[$name_];
    }

    /**
     * @param string $name_
     *
     * @return \Components\Persistence_View
     */
    public static function view($name_)
    {
      $urn=Urn::valueOf($name_);
      if(false===isset(self::$m_backends[$urn->getScheme()]))
        static::backend($urn->getScheme());

      return self::$m_backends[$urn->getScheme()]->view($urn->getNamespace());
    }

    /**
     * @param string $backend_
     * @param string $entityType_
     *
     * @return \Components\Entity_Collection
     */
    public static function collection($name_)
    {
      $urn=Urn::valueOf($name_);
      if(false===isset(self::$m_backends[$urn->getScheme()]))
        static::backend($urn->getScheme());

      return self::$m_backends[$urn->getScheme()]->collection($urn->getNamespace());
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private static $m_backends=array();
    private static $m_backendTypes=array();
    private static $m_resources=array();
    private static $m_resourceIdentifiers=array();
    //--------------------------------------------------------------------------
  }
?>
