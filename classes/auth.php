<?php
require_once '../db/db_config.php';
require_once '../sendmail.php';

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

            if ($row['email_verified_at'] === null) {
                return ['is_authenticated' => false, 'message' => 'Please verify your email.'];
            }
            if ($row['password'] == md5($password)) {
                // Success

                if ($type == 2 && $row['is_verified'] == 0) {
                    return ['is_authenticated' => false, 'message' => 'Your account is not yet verified by the admin.'];
                }
                $_SESSION['admin'] = $val;
                $_SESSION['id'] = $row['id'];
                $_SESSION['user_login'] = true;
                $_SESSION['user_info'] = [
                    'full_name' => $row['first_name'] . ' ' . $row['last_name'],
                    'email' => $row['email'],
                    'picture' => $row['picture'],
                    'type' => $type
                ];
                $_SESSION['auth_user'] = $row;
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

    public static function updateAdminInformation($data)
    {
        global $conn;
        $data = array_map(function ($value) use ($conn) {
            return $conn->real_escape_string($value);
        }, $data);
        extract($data);

        if ($_SESSION['user_info']['email'] != $email && ($conn->query("SELECT * FROM admins where email = '$email'")->num_rows > 0)) {
            return false;
        }

        $sql = "UPDATE `admins` SET `first_name`='$first_name',`middle_name`='$middle_name',`last_name`='$last_name',`extension_name`='$extension_name',`email`='$email',`username`='$username' WHERE id = " . $_SESSION['id'];

        if ($conn->query($sql)) {
            $sql = "SELECT * FROM admins WHERE email = '$email'";

            $_SESSION['auth_user'] = $conn->query($sql)->fetch_assoc();
            $_SESSION['user_info']['full_name'] = $first_name . ' ' . $last_name;
            $_SESSION['user_info']['email'] = $email;

            return true;
        }

        return false;
    }

    public static function updateAdminPassword($password)
    {
        global $conn;

        $sql = "UPDATE `admins` SET `password` = '$password' WHERE id = " . $_SESSION['id'];

        if ($conn->query($sql)) {
            $_SESSION['auth_user']['password'] = $password;

            return true;
        }

        return false;
    }

    public static function findEmail($email, $type)
    {
        global $conn;

        if ($conn->query("SELECT * FROM $type where email = '$email' AND is_verified = 1")->num_rows > 0) {
            return true;
        }

        return false;
    }

    public static function sendEmailCode($email, $type)
    {
        global $conn;
        $code = rand(100000, 999999);
        $_SESSION['password_reset_code'] = $code;
        $name = $conn->query("SELECT * FROM $type where email = '$email'")->fetch_assoc()['first_name'];

        if (setData('Password Reset', 'Hello ' . $name . ', this is your password reset code. Kindly use this so we can give you your new password.<br><b>Code: </b>' . $code, $email, $name)) {
            return true;
        }

        return false;
    }

    public static function sendNewPass($email, $type)
    {
        global $conn;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        $pass = md5($result);
        $name = $conn->query("SELECT * FROM $type where email = '$email'")->fetch_assoc()['first_name'];
        $conn->query("UPDATE $type SET password = '$pass' where email = '$email'");

        if (setData('Password Reset', 'Hello ' . $name . ', we have a temporary password for you. Please change it immediately<br><b>Password: </b>' . $result, $email, $name)) {
            return true;
        }

        return false;
    }
}
