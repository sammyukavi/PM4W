<?php

namespace Wendo;

use MysqliDb as MysqliDb;

class DbHandler {

    public $con;

    public function __construct() {
        $this->con = new MysqliDb(Array(
            'host' => DB_HOST,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'db' => DB_NAME,
            'port' => DB_PORT,
            'prefix' => DB_TABLE_PREFIX,
            'charset' => DB_TABLE_CHARSET));
    }

    public function MultiInsert($table_name, array $params) {
        if (empty($params)) {
            return false;
        }
        $sql = "INSERT IGNORE INTO " . DB_TABLE_PREFIX . $table_name;
        $fields = array();
        $values = array();

        foreach ($params[0] as $field => $value) {
            $fields[] = $this->con->mysqli()->escape_string($field);
        }

        foreach ($params as $field => $item) {
            $Itemvalues = array();
            foreach ($item as $Itemvalue) {
                $Itemvalues[] = $this->con->mysqli()->escape_string($Itemvalue);
            }
            $values[] = '(\'' . implode('\', \'', $Itemvalues) . '\')';
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = implode(',', $values);
        $sql .= $fields . ' VALUES ' . $values;
        $this->con->rawQuery($sql);
        return $this->con->getInsertId();
    }

}
