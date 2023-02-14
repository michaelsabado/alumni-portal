<?php
require_once '../db/db_config.php';

class User
{

    public static function getAllUsers()
    {

        global $conn;
        $sql = "SELECT a.*, b.description as course, c.description as department FROM users a INNER JOIN courses b ON a.course = b.id INNER JOIN departments c ON b.department_id = c.id WHERE a.email_verified_at IS NOT NULL";
        $results = $conn->query($sql);
        return $results;
    }
    public static function getCustomUsers($type)
    {

        global $conn;

        $where = 'WHERE a.email_verified_at IS NOT NULL ';

        if ($type == 'registered') {
            $where .= 'AND is_verified = 1';
        } elseif ($type == 'unverified') {
            $where .= 'AND is_verified = 0';
        } elseif ($type == 'employed') {
            $where .= 'AND employment_status != 2';
        } elseif ($type == 'unemployed') {
            $where .= 'AND employment_status = 2';
        }
        $sql = "SELECT a.*, b.description as course, c.description as department FROM users a INNER JOIN courses b ON a.course = b.id INNER JOIN departments c ON b.department_id = c.id $where ";
        $results = $conn->query($sql);
        return $results;
    }

    public static function getBatches()
    {
        global $conn;
        $sql = "SELECT DISTINCT(batch) FROM users WHERE email_verified_at IS NOT NULL ORDER BY batch";
        $results = $conn->query($sql);
        return $results;
    }
    public static function getUser($id)
    {
        global $conn;
        $sql = "SELECT a.*, b.description as course, c.description as department FROM users a INNER JOIN courses b ON a.course = b.id INNER JOIN departments c ON b.department_id = c.id WHERE a.id = $id";
        $results = $conn->query($sql);
        return $results;
    }
    public function createUser($values)
    {
        global $conn;
        $sql = "INSERT INTO 
                    `users`(
                        `first_name`, 
                        `last_name`, 
                        `birth_date`, 
                        `contact`, 
                        `email_address`, 
                        `user_id`, 
                        `password`, 
                        `profile_picture`
                        ) 
                VALUES (
                    '" . $values['first_name'] . "',
                    '" . $values['last_name'] . "',
                    '" . $values['birth_date'] . "',
                    '" . $values['contact'] . "',
                    '" . $values['email_address'] . "',
                    '" . $values['user_id'] . "',
                    '" . $values['password'] . "',
                    '" . $values['profile_picture'] . "'
                    )";
        $conn->query($sql);
    }

    public static function verifyUser($id)
    {
        global $conn;
        $sql = "UPDATE users set is_verified = 1 WHERE id = $id";
        if ($conn->query($sql)) return true;
        else return false;
    }

    public static function updateUserInformation($fname, $mname, $lname, $ename, $civil)
    {
        global $conn;
        $sql = "UPDATE `users` SET `first_name`='$fname',`middle_name`='$mname',`last_name`='$lname',`extension_name`='$ename',`civil_status`='$civil' WHERE id = " . $_SESSION['id'];
        $_SESSION['user_info']['full_name'] = $fname . ' ' . $lname;
        if ($conn->query($sql)) return true;
        else return false;
    }

    public static function updateContactInformation($address, $muncity, $province, $zip, $contact)
    {
        global $conn;
        $sql = "UPDATE `users` SET `address_line`='$address',`muncity`='$muncity',`province`='$province',`zip_code`='$zip',`contact`='$contact' WHERE id = " . $_SESSION['id'];
        if ($conn->query($sql)) return true;
        else return false;
    }


    public static function updateEmploymentInformation($status, $date, $position)
    {
        global $conn;
        $sql = "UPDATE `users` SET `employment_status`='$status',`employment_date_current`='$date',`current_position`='$position' WHERE id = " . $_SESSION['id'];
        if ($conn->query($sql)) return true;
        else return false;
    }


    public static function updatePassword($pass)
    {
        global $conn;
        $sql = "UPDATE `users` SET `password`='" . md5($pass) . "'  WHERE id = " . $_SESSION['id'];
        if ($conn->query($sql)) return true;
        else return false;
    }

    public static function updateProfile($image)
    {
        $target_dir = "../uploads/profile/";
        $img = uniqid() . basename($image["name"]);
        $target_file = $target_dir . $img;

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            global $conn;
            $sql = "UPDATE `users` SET `picture` = '$img'  WHERE id = " . $_SESSION['id'];
            if ($conn->query($sql)) {
                $_SESSION['user_info']['picture'] = $img;
                return true;
            } else return false;
        } else {
            return false;
        }
    }
}
