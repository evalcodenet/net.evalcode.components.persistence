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
   */
  class Entity_Foo extends Entity_Default
  {
    // PROPERTIES
    /**
     * @id
     * @var string
     */
    public $id;

    /**
     * @unique
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
