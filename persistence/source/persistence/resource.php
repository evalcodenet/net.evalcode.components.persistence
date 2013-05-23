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
  interface Persistence_Resource extends Resource
  {
    // ACCESSORS/MUTATORS
    /**
     * @param \Components\Entity $entity_
     *
     * @return boolean
     */
    function save(Entity $entity_);

    /**
     * @param \Components\Query $query_
     */
    function query(Query $query_);

    /**
     * @param string $statement_
     */
    function execute($statement_);

    /**
     * @param string $entity_
     *
     * @return \Components\Entity_Collection
     *
     * @throws \Components\Persistence_Exception
     */
    function collection($name_);

    /**
     * @param string $name_
     *
     * @return \Components\Persistence_View
     */
    function view($name_);

    /**
     * Return underlying connection or resource.
     *
     * @return mixed
     *
     * @throws \Components\Persistence_Exception
     */
    function connection();

    /**
     * Return underlying driver or resource.
     *
     * @return mixed
     *
     * @throws \Components\Persistence_Exception
     */
    function driver();

    /**
     * @throws \Components\Persistence_Exception
     */
    function transactionBegin();

    /**
     * @throws \Components\Persistence_Exception
     */
    function transactionCommit();

    /**
     * @throws \Components\Persistence_Exception
     */
    function transactionRollback();

    /**
     * @return boolean
     */
    function isReadOnly();
    //--------------------------------------------------------------------------
  }
?>
