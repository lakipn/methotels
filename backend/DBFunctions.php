<?php

/**
 * Created by PhpStorm.
 * User: lazar
 * Date: 8/27/16
 * Time: 6:51 PM
 */
class DBFunctions
{
    public static $instance; // Instanca klase
    private $dbhost, $dbusername, $dbpassword, $dbname; // Podaci za pristup bazi podataka
    private $connection; // MySQLi konekcija na bazu podataka

    /**
     * DBFunctions constructor.
     */
    private function __construct()
    {
        $this->dbhost = "localhost";
        $this->dbusername = "lazar";
        $this->dbpassword = "";
        $this->dbname = "methotels";

        $this->connection = new mysqli($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);
        if (mysqli_connect_errno()) {
            die("Nemoguće je uspostaviti konekciju na bazu podataka.");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DBFunctions();
        }
        return self::$instance;
    }

    private function isDbAlive()
    {
        return $this->connection != null;
    }

    /**
     * Checking if username exists on user registration
     * @param $username
     * @return bool
     */
    public function checkIfUsernameExists($username) {
        $ret = array();
        $error = '';
        if($this->isDbAlive())
        {
            $username = mysqli_real_escape_string($this->connection, $username);
            $query = "SELECT TOKEN FROM user WHERE USERNAME = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0) {
                return true;
            }
            return false;
        }
        else {
//            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        return false;
    }

    /**
     * User registration.
     * @param $firstName
     * @param $lastName
     * @param $username
     * @param $password
     * @param $email
     * @return array
     */
    public function register($firstName, $lastName, $username, $password, $email)
    {
        $ret = array();
        $error = '';
        if ($this->isDbAlive()) {
            $firstName = mysqli_real_escape_string($this->connection, $firstName);
            $lastName = mysqli_real_escape_string($this->connection, $lastName);
            $username = mysqli_real_escape_string($this->connection, $username);
            $password = md5(mysqli_real_escape_string($this->connection, $password));
            $email = mysqli_real_escape_string($this->connection, $email);
            $token = uniqid();

            $names = $this->connection->prepare("SET NAMES utf8");
            $names->execute();

            $query = "INSERT INTO user (FIRSTNAME, LASTNAME, USERNAME, PASSWORD, EMAIL, TOKEN) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssss", $firstName, $lastName, $username, $password, $email, $token);
            $stmt->execute();
            $stmt->store_result();
            $ret['status'] = 'OK';
            $ret['token'] = $token;
            return $ret;
        } else {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        if (strlen($error) > 0) {
            $ret['status'] = 'ERROR';
            $ret['error'] = $error;
        }
        return $ret;
    }

    /**
     * Check if user with specified credentials exists.
     * @param $username
     * @param $password
     * @return array
     */
    public function checkLogin($username, $password)
    {
        if ($this->isDbAlive()) {
            $username = mysqli_real_escape_string($this->connection, $username);
            $password = md5(mysqli_real_escape_string($this->connection, $password));

            $names = $this->connection->prepare("SET NAMES utf8");
            $names->execute();
            $query = "SELECT TOKEN FROM user WHERE USERNAME = ? AND PASSWORD = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $stmt->store_result();

            $num_rows = $stmt->num_rows;
            if ($num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Tokenization on user login.
     * @param $username
     * @return array
     */
    public function login($username)
    {
        $ret = array();
        $error = '';
        if ($this->isDbAlive()) {
            $token = uniqid();
            $username = mysqli_real_escape_string($this->connection, $username);
            $query = "UPDATE user SET TOKEN = ? WHERE USERNAME = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ss", $token, $username);
            $stmt->execute();
            $stmt->store_result();
            $ret['status'] = 'OK';
            $ret['token'] = $token;
            return $ret;
        } else {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        $ret['status'] = 'ERROR';
        $ret['error'] = $error;
        return $ret;
    }

    /**
     * Adding new room.
     * @param $roomname
     * @param $beds
     * @param $size
     * @param $tv
     * @return array
     */
    public function newRoom($roomname, $beds, $size, $tv) {
        $ret = array();
        $error = '';
        if($this->isDbAlive())
        {
            $roomname = mysqli_real_escape_string($this->connection, $roomname);
            $beds = mysqli_real_escape_string($this->connection, $beds);
            $size = mysqli_real_escape_string($this->connection, $size);
            $tv = mysqli_real_escape_string($this->connection, $tv);

            $query = "INSERT INTO room (ROOMNAME, BEDS, SIZE, TV) VALUES (?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssss", $roomname, $beds, $size, $tv);
            $stmt->execute();
            $ret['status'] = 'OK';
            $ret['inserted'] = 1;
            return $ret;
        }
        else
        {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        $ret['status'] = 'ERROR';
        $ret['error'] = $error;
        return $ret;
    }

    /**
     * All rooms.
     * @return array
     */
    public function allRooms() {
        $ret = array();
        $error = '';
        if($this->isDbAlive()) {
            $query = "SELECT * FROM room";
            $stmt = $this->connection->query($query);
            $result = array();
            while($row = $stmt->fetch_object()) {
                array_push($result, $row);
            }

            $ret['status'] = 'OK';
            $ret['rooms'] = $result;
            return $ret;
        }
        else
        {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        $ret['status'] = 'ERROR';
        $ret['error'] = $error;
        return $ret;
    }

    /**
     * Modifying room.
     * @param $roomname
     * @param $beds
     * @param $size
     * @param $tv
     * @return array
     */
    public function modifyRoom($roomname, $beds, $size, $tv, $id) {
        $ret = array();
        $error = '';
        if($this->isDbAlive())
        {
            $roomname = mysqli_real_escape_string($this->connection, $roomname);
            $beds = mysqli_real_escape_string($this->connection, $beds);
            $size = mysqli_real_escape_string($this->connection, $size);
            $tv = mysqli_real_escape_string($this->connection, $tv);
            $id = mysqli_real_escape_string($this->connection, $id);

            $query = "UPDATE room SET ROOMNAME = ?, BEDS = ?, SIZE = ?, TV = ? WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("sssss", $roomname, $beds, $size, $tv, $id);
            $stmt->execute();
            echo $stmt->error;
            echo $this->connection->error;

            $ret['status'] = 'OK';
            $ret['updated'] = 1;
            return $ret;
        }
        else
        {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        $ret['status'] = 'ERROR';
        $ret['error'] = $error;
        return $ret;
    }

    public function deleteRoom($id) {
        $ret = array();
        $error = '';
        if($this->isDbAlive())
        {
            $query = "DELETE FROM room WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();


            $ret['status'] = 'OK';
            $ret['deleted'] = 1;
            return $ret;
        }
        else
        {
            $error .= 'Konekcija ka bazi podataka nije otvorena.';
        }
        $ret['status'] = 'ERROR';
        $ret['error'] = $error;
        return $ret;
    }
}

?>