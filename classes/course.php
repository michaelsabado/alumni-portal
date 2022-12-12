<?php
require_once '../db/db_config.php';

class Course
{
    public static function getAllCourses()
    {
        global $conn;
        $sql = "SELECT a.id,a.description, COUNT(b.id) as count FROM courses a LEFT JOIN users b ON a.id = b.course GROUP BY a.id";
        $results = $conn->query($sql);
        return $results;
    }
    public static function create($deparmentId, $course)
    {
        global $conn;
        $sql = "INSERT INTO `courses`(`description`, `department_id`) VALUES ('$course','$deparmentId')";
        if ($conn->query($sql)) return true;
        return false;
    }
    public static function destroy($id)
    {
        global $conn;
        $sql = "DELETE FROM `courses` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }
}
