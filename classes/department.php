<?php
require_once '../db/db_config.php';

class Department
{

    public static function getAllDepartments()
    {
        global $conn;
        $sql = "SELECT * FROM departments";
        $results = $conn->query($sql);
        return $results;
    }

    public static function create($deparment)
    {
        global $conn;
        $sql = "INSERT INTO `departments`(`description`) VALUES ('$deparment')";
        if ($conn->query($sql)) return true;
        return false;
    }
    public static function destroy($id)
    {
        global $conn;
        $sql = "DELETE FROM `departments` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }
}
