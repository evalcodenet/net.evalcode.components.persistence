<?php


namespace Components;


  /**
   * Persistence_View
   *
   * @package net.evalcode.components
   * @subpackage persistence
   *
   * @author evalcode.net
   *
   * @property string name
   * @property \Components\Persistence_Resource resource
   *
   * @method \Components\Entity_Collection getIterator
   */
  interface Persistence_View extends Object, Iterable
  {
    // ACCESSORS/MUTATORS
    /**
     * @param scalar $primaryKey_
     *
     * @return array|scalar
     */
    function findByPk($primaryKey_);

    /**
     * @param array|scalar $record_
     */
    function save(array $record_);

    /**
     * @param scalar $primaryKey_
     *
     * @return boolean
     */
    function remove($primaryKey_);

    /**
     * @return \Components\Entity_Collection
     */
    function collection();

    /**
     * @return \Components\Entity_Collection
     */
    function getIterator();
    //--------------------------------------------------------------------------
  }
?>
