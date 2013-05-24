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
     * @param string $table_
     * @param array|scalar $record_
     *
     * @return null|scalar
     */
    function save($table_, $primaryKey_, array $record_);

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     *
     * @return null|array|scalar
     */
    function find($table_, $property_, $value_);

    /**
     * @param string $table_
     * @param string $property_
     * @param scalar $value_
     *
     * @return boolean
     */
    function remove($table_, $property_, $value_);

    /**
     * @param \Components\Query $query_
     */
    function query(Query $query_);

    /**
     * @param string $statement_
     */
    function execute($statement_);

    /**
     * @param string $name_
     * @param \Components\Persistence_Properties $properties_
     *
     * @return \Components\Persistence_View
     */
    function view($name_, Persistence_Properties $properties_=null);

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
