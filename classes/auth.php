<?php
require_once '../db/db_config.php';

class Auth
{
    public static function authenticate($email, $password, $type)
    {
        global $conn;
        switch ($type) {
            case 1:
                $str = 'admins';
                $val = true;
                break;
            case 2:
                $str = 'users';
                $val = false;
                break;
        }
        $sql = "SELECT * FROM $str WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['password'] == md5($password)) {
                // Success
                $_SESSION['admin'] = $val;
                $_SESSION['id'] = $row['id'];
                $_SESSION['user_login'] = true;
                $_SESSION['user_info'] = [
                    'full_name' => $row['first_name'] . ' ' . $row['last_name'],
                    'email' => $row['email'],
                    'picture' => $row['picture'],
                    'type' => $type
                ];
                return ['is_authenticated' => true, 'message' => 'Success', 'data' => $row];
            } else {
                // Incorrect pass
                return ['is_authenticated' => false, 'message' => 'Incorrect Password'];
            }
        } else {
            // Invalid User ID
            return ['is_authenticated' => false, 'message' => 'Account not found'];
        }
    }
    public static function checkLogin()
    {
        if (isset($_SESSION['user_login'])) {
            return $_SESSION['user_info']['type'];
        } else {
            return false;
        }
    }
}
