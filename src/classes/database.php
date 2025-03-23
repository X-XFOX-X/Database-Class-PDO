<?php

namespace App\classes;

use PDO;
use PDOException;

class database
{
    private $host = "localhost";
    private $db_name = "db_name";
    private $username = "root";
    private $password = "";
    private $conn;
    private $tablename = "";

    // Database connection class that returns an object (pdo)
    public function __construct($tablename)
    {
        $this->tablename = $tablename;

        $this->conn = null;
        try {
            // connect to the database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // For better error management
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Returns all fields in the selected table
    public function getdata()
    {
        try {
            $result = $this->conn->query("select * from $this->tablename");
            $fetch = $result->fetchAll(PDO::FETCH_ASSOC);
            return $fetch;
        } catch (PDOException $exception) {
            echo "error: " . $exception->getMessage();
        }
    }

    // Gets data as key and value in an array and inserts it into the table
    // Note: Returns an error if values ​​are entered incorrectly in the input
    public function newdata(array $data)
    {
        try {
            $c = 0;
            $keys = '';
            $keysvalue = '';
            $values = '';
            $execute = [];
            foreach ($data as $key => $value) {
                if ($c !== 0) {
                    $keys .= ",";
                    $keysvalue .= ",";
                    $values .= ",";
                }
                $c++;
                $keys .= " $key";
                $keysvalue .= " :$key";
                $values .= " '$value'";
                $execute[":$key"] = $value;
            }

            $query = "INSERT INTO $this->tablename ($keys) VALUES ($keysvalue)";
            $statement = $this->conn->prepare($query);

            if ($statement->execute($execute)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            echo "database error:" . $error->getMessage();
        }
    }


    // Gets the name of the field in the selected table along with its value and deletes it if it exists.
    public function deletedata($variable, $value)
    {
        try {
            $query = "SELECT * FROM $this->tablename WHERE `$variable` = '$value'";
            $p = $this->conn->query($query);
            $map = $p->fetch(PDO::FETCH_ASSOC);
            if ($map) {
                $query = "DELETE FROM $this->tablename WHERE `$variable` = :$value";
                $p = $this->conn->prepare($query);
                if ($p->execute([":$value" => $value])) {
                    return true;
                }
            } else {
                return false;
            }
        } catch (PDOException $th) {
            echo "database error:" . $th->getMessage();
        }
    }

    // Gets an ID along with an array of field names and values ​​and edits the field
    public function editdata($variable, $valuein, array $data)
    {
        $query = "SELECT * FROM $this->tablename WHERE `$variable` = $valuein";
        $p = $this->conn->query($query);
        $map = $p->fetch(PDO::FETCH_ASSOC);
        if ($map) {
            try {
                $c = 0;
                $keys = '';
                $execute = [];
                foreach ($data as $key => $value) {
                    if ($c !== 0) {
                        $keys .= ",";
                    }
                    $c++;
                    $keys .= " $key = :$key";
                    $execute[":$key"] = $value;
                }
                $query = "UPDATE $this->tablename SET $keys WHERE $this->tablename.$variable = $valuein;";
                $statement = $this->conn->prepare($query);
                if ($statement->execute($execute)) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $error) {
                echo "database error:" . $error->getMessage();
            }
        } else {
            echo false;
        }
    }

    // Searches the table by receiving the field name and its value
    // Changes the search type by placing the operator value with the specified values
    // = equals the entered value
    // LIKE the entered value exists in the string or etc.
    public function search($variable, $value, $operator)
    {
        try {
            $char = '';
            if ($operator == '=') {
                $char = '';
            } elseif ($operator == 'like' || $operator == 'LIKE') {
                $char = '%';
            } else {
                return false;
            }
            $query = "SELECT * FROM $this->tablename WHERE $variable $operator :valuee";
            $p = $this->conn->prepare($query);
            $p->execute(['valuee' => $char . $value . $char]);
            $map = $p->fetchAll(PDO::FETCH_ASSOC);
            if ($map) {
                return $map;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Executes the entered query
    public function runQuery($query)
    {
        try {
            $stmt = $this->conn->query($query);
            $stmt = $stmt->fetchAll(pdo::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
