<?php


namespace Components;


  /**
   * Persistence_Backend
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   */
  abstract class Persistence_Backend implements Object
  {
    // PROPERTIES
    /**
     * @var \Components\Persistence_Resource
     */
    public $resource;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct(Persistence_Resource $resource_)
    {
      $this->resource=$resource_;
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
     * @param string $entity_
     *
     * @return \Components\Entity_Collection
     *
     * @throws \Components\Persistence_Exception
     */
    public function collection($name_)
    {
      if(!$type=Runtime_Classloader::lookup($name_))
      {
        throw new Persistence_Exception('persistence/backend', sprintf(
          'Unable to resolve entity type for given name [name: %s].', $name_
        ));
      }

      if($annotation=Annotations::get($type)->getTypeAnnotation(Annotation_Collection::NAME))
      {
        if($annotation->value && !($collection=Runtime_Classloader::lookup($annotation->value)))
        {
          throw new Persistence_Exception('persistence/backend', sprintf(
            'Unable to resolve entity collection type for given name [name: %s].',
              $annotation->value
          ));
        }

        return new $collection($this->view($name_), $type);
      }

      return new Entity_Collection($this->view($name_), $type);
    }

    abstract public function view($name_);
    //--------------------------------------------------------------------------


    // OVERRIDES/IMPLEMENTS
    /**
     * (non-PHPdoc)
     * @see \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * (non-PHPdoc)
     * @see \Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{resource: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->resource
      );
    }
    //--------------------------------------------------------------------------
  }
?>
