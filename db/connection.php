<?php
class Database
{
    public static $connection;

    public static function getConnection()
    {
        if (!isset(self::$connection)) {
            $host = "127.0.0.1";
            $user = "root";
            $pass = "";
            $dbname = "skillshop_db";

            // Establish connection with exception handling for the initial setup phase
            try {
                // Attempt to connect with database name. 
                // In PHP 8.1+, this will throw a mysqli_sql_exception if the DB doesn't exist.
                self::$connection = @new mysqli($host, $user, $pass, $dbname, 3306);
                
                if (self::$connection->connect_error) {
                    // Fallback to server-only connection if DB selection fails
                    self::$connection = new mysqli($host, $user, $pass, null, 3306);
                }
            } catch (Exception $e) {
                // Catch mysqli_sql_exception (e.g. Unknown database)
                self::$connection = new mysqli($host, $user, $pass, null, 3306);
            }

            if (self::$connection->connect_error) {
                // Fallback attempt for standard localhost
                try {
                    self::$connection = new mysqli("localhost", $user, $pass, null, 3306);
                } catch (Exception $e2) {
                    die("CRITICAL CONNECTION FAILURE: " . $e2->getMessage());
                }
            }

            // Ensure we can use the selected database if it was not auto-selected in constructor
            if (self::$connection && !self::$connection->connect_error) {
                @self::$connection->select_db($dbname);
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
?>
