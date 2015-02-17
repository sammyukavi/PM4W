<?php

/**
 * This class handles all the database operations
 * @since 1.0
 * @author Sammy Ukavi
 * @version 2.0
 * @copyright Copyright (c) 2012, Sammy Ukavi
 * @link http://www.zikiza.com
 */
class DBOperations {

    public $con;

    function __construct() {
        $this->connect();
    }

    function __destruct() {
        $this->con->close();
    }

    /**
     * Function to connect with database
     */
    private function connect() {
        $this->con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->con->connect_errno) {
            echo '<h1>Error establishing a database connection</h1>';
            die();
        }
        $this->con->set_charset("utf8");
    }

    public function Rollback($params = null) {
        if (empty($params)) {
            return $this->con->rollback();
        }
        return $this->con->rollback();
    }

    /**
     * 
     * @param string $string
     * @return string
     * @since 2.0
     */
    public function EscapeString($string) {
        return $this->con->escape_string($string);
    }

    public function Insert($table_name, $params) {
        //var_dump($params);
        if (empty($params)) {
            return false;
        }
        $sql = "INSERT INTO " . TABLE_PREFIX . $table_name;
        $fields = array();
        $values = array();
        foreach ($params as $field => $value) {
            $fields[] = $this->con->escape_string($field);
            $values[] = $this->con->escape_string($value);
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '(\'' . implode('\', \'', $values) . '\')';
        $sql .= $fields . ' VALUES ' . $values;
        //var_dump($sql);
        if ($this->con->query($sql)) {
            return $this->con->insert_id;
        } else {
            logMessage("Error in Function", "An error occured when running the query " . $sql . " This occured in the class " . __CLASS__ . ' in the function ' . __FUNCTION__ . '<br/>
          The error is as shown below. <br/>' . $this->con->error);
            return false;
        }
    }

    public function Fetch($table_name, $columnsToFetch = "*", array $where = NULL, $OrderBy = NULL, $asc = true, $limit = NULL) {
        $records = false;
        $num_rows = 0;
        if (is_array($columnsToFetch)) {
            $sql = "SELECT " . implode(",", $columnsToFetch) . " FROM " . TABLE_PREFIX . "$table_name";
        } elseif (!empty($where)) {
            $sql = "SELECT $columnsToFetch FROM " . TABLE_PREFIX . "$table_name";
        } else {
            $sql = "SELECT $columnsToFetch FROM " . TABLE_PREFIX . "$table_name";
        }
        //var_dump($sql);
       // die();
        if (isset($where) && is_array($where)) {
            $sql.=" WHERE ";
            $counter = 0;
            $lastIndex = (count($where) - 1);
            foreach ($where as $keyId => $value) {
                if ($counter !== $lastIndex) {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "' AND ";
                } else {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "'";
                }
                $counter+=1;
            }
        }

        if (!empty($OrderBy)) {
            $sql .= " ORDER BY $OrderBy";
            if ($asc) {
                $sql .= " ASC";
            } else {
                $sql .= " DESC";
            }
        }
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        //var_dump($sql);
        if ($result = $this->con->query($sql)) {
            $num_rows = $result->num_rows;
            /* free result set */
            $result->close();
        }

        if (isset($num_rows)) {
            if ($num_rows === 1) {
                if ($result = $this->con->query($sql)) {
                    while ($obj = $result->fetch_assoc()) {
                        $records = $obj;
                    }
                }
            } elseif ($num_rows > 1) {
                if ($result = $this->con->query($sql)) {
                    while ($obj = $result->fetch_assoc()) {
                        $records[] = $obj;
                    }
                }
            }
        }
        return $records;
    }

    public function Search($table_name, $columnsToSearch, array $paramsToSearch = NULL, $OrderBy = NULL, $asc = true, $limit = NULL) {
        $records = false;
        if (is_array($columnsToSearch)) {
            $sql = "SELECT " . implode(",", $columnsToSearch) . " FROM " . TABLE_PREFIX . "$table_name";
        } else {
            $sql = "SELECT $columnsToSearch FROM " . TABLE_PREFIX . "$table_name";
        }

        if (isset($paramsToSearch) && is_array($paramsToSearch)) {
            $sql.=" WHERE ";
            $counter = 0;
            $lastIndex = (count($paramsToSearch) - 1);
            foreach ($paramsToSearch as $keyId => $value) {
                if ($counter !== $lastIndex) {
                    $sql.= $this->con->escape_string($keyId) . " LIKE '" . $this->con->escape_string($value) . "' OR ";
                } else {
                    $sql.= $this->con->escape_string($keyId) . " LIKE '" . $this->con->escape_string($value) . "'";
                }
                $counter+=1;
            }
        }

        if (!empty($OrderBy)) {
            $sql .= " ORDER BY $OrderBy";
            if ($asc) {
                $sql .= " ASC";
            } else {
                $sql .= " DESC";
            }
        }
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        //echo $sql;
        if ($result = $this->con->query($sql)) {
            $num_rows = $result->num_rows;
            /* free result set */
            $result->close();
        }

        if (isset($num_rows)) {
            if ($num_rows === 1) {
                if ($result = $this->con->query($sql)) {
                    while ($obj = $result->fetch_assoc()) {
                        $records = $obj;
                    }
                }
            } elseif ($num_rows > 1) {
                if ($result = $this->con->query($sql)) {
                    while ($obj = $result->fetch_assoc()) {
                        $records[] = $obj;
                    }
                }
            }
        }
        return $records;
    }

    public function FetchCustomQuery($table_name = NULL, $custom_parameters = NULL, $query = NULL) {
        $record = false;

        if ((isset($table_name) && !empty($table_name)) && (isset($custom_parameters) && !empty($custom_parameters))) {
            $query = "SELECT * FROM " . TABLE_PREFIX . "$table_name WHERE $custom_parameters";
            if ($result = $this->con->query($query)) {
                $num_rows = $result->num_rows;
                /* free result set */
                $result->close();
            }
            if (isset($num_rows)) {
                if ($num_rows === 1) {
                    if ($result = $this->con->query($query)) {
                        while ($obj = $result->fetch_assoc()) {
                            $record = $obj;
                        }
                    }
                } elseif ($num_rows > 1) {
                    if ($result = $this->con->query($query)) {
                        while ($obj = $result->fetch_assoc()) {
                            $record[] = $obj;
                        }
                    }
                }
            }
        } elseif (isset($query) && !empty($query)) {
            // echo $query;
            if ($result = $this->con->query($query)) {
                $num_rows = $result->num_rows;
                /* free result set */
                $result->close();
            }
            if (isset($num_rows)) {
                if ($num_rows === 1) {
                    if ($result = $this->con->query($query)) {
                        while ($obj = $result->fetch_assoc()) {
                            $record = $obj;
                        }
                    }
                } elseif ($num_rows > 1) {
                    if ($result = $this->con->query($query)) {
                        while ($obj = $result->fetch_assoc()) {
                            $record[] = $obj;
                        }
                    }
                }
            }
        }
        return $record;
    }

    public function Update($table_name, array $params, array $keys) {
        $lastIndexOfArray = (count($params) - 1);
        $index = 0;
        $sql = 'UPDATE ' . TABLE_PREFIX . $table_name . ' SET ';
        foreach ($params as $arrayKey => $value) {
            if ($index != $lastIndexOfArray) {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "', ";
            } else {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "'";
            }
            $index++;
        }
        $sql.=" WHERE ";
        if (isset($keys) && is_array($keys)) {
            $lastIndexOfArray = (count($keys) - 1);
            $index = 0;
            foreach ($keys as $keyId => $value) {
                if ($index !== $lastIndexOfArray) {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "' AND ";
                } else {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "'";
                }
                $index++;
            }
        }
        //var_dump($sql);
        if ($this->con->query($sql)) {
            return true;
        } else {
            logMessage("Error in Function", "An error occured when running the query `" . $sql . "` This occured in the class " . __CLASS__ . ' in the function ' . __FUNCTION__ . '<br/>
                    The error is as shown below. <br/>' . $this->con->error);
        }
        return false;
    }

    /**
     * This function updates multiple rows with similar key all at once
     * @param String $table_name Name of table to update
     * @param array $params associative array of columns to update
     * @param String $comma_separated_primaryKeys primary key ids in which data will be updated
     * @since 1.0
     */
    public function UpdateRows($table_name, array $params, $key, $comma_separated_primaryKeys) {
        $comma_separated_primaryKeys = str_replace(",", "','", $comma_separated_primaryKeys);
        $lastIndexOfArray = (count($params) - 1);
        $index = 0;
        $sql = 'UPDATE ' . TABLE_PREFIX . $table_name . ' SET ';
        foreach ($params as $arrayKey => $value) {
            if ($index != $lastIndexOfArray) {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "', ";
            } else {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "'";
            }
            $index++;
        }

        $sql.=" WHERE $key IN('$comma_separated_primaryKeys')";
        if ($this->con->query($sql)) {
            return true;
        } else {
            logMessage("Error in Function", "An error occured when running the query " . $sql . " This occured in the class " . __CLASS__ . ' in the function ' . __FUNCTION__ . '<br/>
                    The error is as shown below. <br/>' . $this->con->error);
            return false;
        }
    }

    /**
     * This function checks whether a certain value or values exist is a column or columns in a table
     * @param String $tablename name of the table to check
     * @param array $paramsToCheck multidimensional array of table columns and respective values to check for
     * @return boolean
     * @since 1.0
     */
    public function CheckIFExists($tablename, array $paramsToCheck) {
        (int) $row_cnt = 0;
        $lastIndexOfArray = (count($paramsToCheck) - 1);
        $index = 0;
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . $tablename . ' WHERE ';
        foreach ($paramsToCheck as $arrayKey => $value) {
            if ($index != $lastIndexOfArray) {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "' AND ";
            } else {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "' LIMIT 1";
            }
            $index++;
        }
        //var_dump($sql);
        if ($result = $this->con->query($sql)) {
            $row_cnt = $result->num_rows;
            $result->close();
        }

        if ($row_cnt > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function CountRowsinColumn($table_name, $columnToCount, array $where = NULL) {
        $num_rows = 0;
        $sql = "SELECT COUNT($columnToCount) AS $columnToCount FROM " . TABLE_PREFIX . "$table_name";

        if (isset($where) && is_array($where)) {
            $sql.=" WHERE ";
            $counter = 0;
            $lastIndex = (count($where) - 1);
            foreach ($where as $keyId => $value) {
                if ($counter !== $lastIndex) {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "' AND ";
                } else {
                    $sql.= $this->con->escape_string($keyId) . "='" . $this->con->escape_string($value) . "'";
                }
                $counter+=1;
            }
        }

        // var_dump($sql);
        if ($result = $this->con->query($sql)) {
            $num_rows = $result->num_rows;
            /* free result set */
            $result->close();
        }

        if (isset($num_rows)) {
            if ($num_rows >= 1) {
                if ($result = $this->con->query($sql)) {
                    while ($obj = $result->fetch_assoc()) {
                        return $obj[$columnToCount];
                    }
                }
            }
        }
        return $num_rows;
    }

    public function RunQueryForResults($query) {
        if (!empty($query)) {
            return $this->con->query($query);
        } else {
            return false;
        }
    }

    public function Delete($tablename, array $params) {
        (int) $row_cnt = 0;
        $lastIndexOfArray = (count($params) - 1);
        $index = 0;
        $sql = 'DELETE FROM ' . TABLE_PREFIX . $tablename . ' WHERE ';
        foreach ($params as $arrayKey => $value) {
            if ($index != $lastIndexOfArray) {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "' AND ";
            } else {
                $sql.= $this->con->escape_string($arrayKey) . "='" . $this->con->escape_string($value) . "'";
            }
            $index++;
        }
        if ($this->con->query($sql)) {
            return true;
            $result->close();
        } else {
            return false;
        }
    }

}
