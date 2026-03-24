<?php
class Database
{
    public static $connection;

    public static function getConnection()
    {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli("localhost", "root", "your_mysql_password", "skillshop_db", 3306);

            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
 
    public static function iud($query, $types = null, $params = [])
    {
        $conn = self::getConnection();
        $statement = $conn->prepare($query);

        if (!$statement) {
            throw new Exception("MySQL Prepare Error: " . $conn->error . " | Query: " . $query);
        }

        if ($types !== null && !empty($params)) {
            $statement->bind_param($types, ...$params);
        }

        $result = $statement->execute();

        if (!$result) {
            throw new Exception("MySQL Execute Error: " . $statement->error);
        }

        $statement->close();
        return $result;
    }

    public static function search($query, $types = null, $params = [])
    {
        $conn = self::getConnection();
        $statement = $conn->prepare($query);

        if (!$statement) {
            throw new Exception("MySQL Prepare Error: " . $conn->error . " | Query: " . $query);
        }

        if ($types !== null && !empty($params)) {
            $statement->bind_param($types, ...$params);
        }

        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }
}
