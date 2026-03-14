<?php
defined("ROOTPATH") or exit("Access Denied!");

trait Database
{

    private function connect()
    {
        try {
            $constring = "mysql:host=" . DB_HOST . ":3306;dbname=" . DB_NAME;
            $con = new PDO($constring, DB_USER, DB_PASS);
            return $con;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    public function query($query, $data = [])
    {


        $con = $this->connect();
        $stmt = $con->prepare($query);
        $check = $stmt->execute($data);
        // show($stmt);
        if ($check) {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            // show($result);


            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        return false;
    }

    public function get_row($query, $data = [])
    {

        $con = $this->connect();
        $stmt = $con->prepare($query);

        $check = $stmt->execute($data);
        if ($check) {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            if (is_array($result) && count($result)) {
                return $result[0];
            }
        }
        return false;
    }
}