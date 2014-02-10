<?php
/*
Latch Prestashop module - Integrates Latch into the Prestashop authentication process.
Copyright (C) 2013 Eleven Paths

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
 * Stores the latch configuration parameters for the customer. Follows the
 * ActiveRecord pattern to interact with the database.
 */
class LatchData extends ObjectModel {

    public $id_data = NULL;
    public $id_customer = NULL;
    public $id_latch = NULL;
    
    function __construct($databaseRow) {
        parent::__construct();

        if ($databaseRow != NULL &&
                array_key_exists("id_data", $databaseRow) && 
                array_key_exists("id_customer", $databaseRow) &&
                array_key_exists("id_latch", $databaseRow)) {
            $this->id_data = (int)$databaseRow["id_data"];
            $this->id_customer = (int)$databaseRow["id_customer"];
            $this->id_latch = $databaseRow["id_latch"];
        }
        $this->id = &$this->id_data;
    }

    public static $definition = array(
        'table' => 'latch_data',
        'primary' => 'id_data',
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'required' => true),
            'id_latch' => array('type' => self::TYPE_STRING, 'size' => 100)
        )
    );

    /*
     * Initializes the database tables during the module installation.
     */
    public static function createTable() {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'latch_data`(
			`id_data` int(10) unsigned NOT NULL auto_increment,
			`id_customer` int(10) unsigned NOT NULL,
			`id_latch` varchar(100),
			PRIMARY KEY (`id_data`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
    }

    /*
     * Removes the database tables during the unistallation.
     */
    public static function dropTable() {
        $sql = 'DROP TABLE`' . _DB_PREFIX_ . 'latch_data`';
        Db::getInstance()->execute($sql);
    }
    
    /*
     * Adds the Latch ID to the customer configuration. Used after the pairing
     * process.
     */
    public static function storeLatchId($customerId, $latchId) {
        $customerData = self::getLatchDataFromCustomerId($customerId);
        $customerData->id_customer = (int) $customerId; // In case it's a new record
        $customerData->id_latch = $latchId;
        return $customerData->save();
    }
    
    /*
     * Removes the Latch ID from the customer configuration. Used after the
     * unpairing process.
     */
    public static function removeLatchId($customerId) {
        $customerData = self::getLatchDataFromCustomerId($customerId);
        $customerData->id_latch = NULL;
        return $customerData->save();
    }
    
    /*
     * Retrieve or create the Latch confguration for a customer.
     */
    public static function getLatchDataFromCustomerId($customerId) {
        $query = new DBQuery();
        $query->select('*');
        $query->from('latch_data');
        $query->where('id_customer = ' . $customerId);
        $row = Db::getInstance()->getRow($query);
        return ($row) ? new LatchData($row) : new LatchData(NULL);
    }
}
