<?php


namespace Components;


  /**
   * Entity_Foo
   *
   * @package net.evalcode.components
   * @subpackage persistence.entity
   *
   * @author evalcode.net
   *
   * @name foo
   * @cache entities
   * @collection components/entity/collection/foo
   */
  class Entity_Foo extends Entity_Abstract
  {
    // PROPERTIES
    /**
     * @id
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @name created_at
     * @var Components\Date
     */
    public $createdAt;

    /**
     * @belongsTo nosql:components/entity/group
     */
    public $groupId;
    //--------------------------------------------------------------------------
  }
?>
