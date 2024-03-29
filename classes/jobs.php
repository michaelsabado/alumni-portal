<?php
require_once '../db/db_config.php';
require_once '../notifier.php';

class Jobs
{
    public static function getAllJobsAdmin($limit = 0)
    {
        global $conn;
        if ($limit != 0) {
            $sql = "SELECT * FROM jobs  ORDER BY date_posted DESC LIMIT $limit";
        } else {
            $sql = "SELECT * FROM jobs  ORDER BY date_posted DESC";
        }

        $results = $conn->query($sql);
        return $results;
    }
    public static function getAllJobs($limit = 0)
    {
        global $conn;
        if ($limit != 0) {
            $sql = "SELECT * FROM jobs WHERE status = 1 ORDER BY date_posted DESC LIMIT $limit";
        } else {
            $sql = "SELECT * FROM jobs WHERE status = 1 ORDER BY date_posted DESC";
        }

        $results = $conn->query($sql);
        return $results;
    }

    public static function getAllArchived($limit = 0)
    {
        global $conn;
        if ($limit != 0) {
            $sql = "SELECT * FROM jobs WHERE status = 2  ORDER BY date_posted DESC LIMIT $limit";
        } else {
            $sql = "SELECT * FROM jobs  WHERE status = 2 ORDER BY date_posted DESC";
        }

        $results = $conn->query($sql);
        return $results;
    }


    public static function getJob($id)
    {
        global $conn;
        $sql = "SELECT * FROM jobs WHERE id = $id";
        $results = $conn->query($sql);
        return $results;
    }

    public static function setStatus($id, $val)
    {
        global $conn;
        $sql = "UPDATE jobs SET `status` = $val WHERE id = $id";
        if ($conn->query($sql)) {
            return true;
        } else return false;
    }

    public static function create($title, $description, $company, $type, $email, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = '';
        } else {
            $target_dir = "../uploads/jobs/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
        }

        $sql = "INSERT INTO `jobs`(`status`, `type`, `title`, `description`, `company`, `email`, `picture`) VALUES ('1','$type','$title','$description','$company', '$email', '$img')";
        if ($conn->query($sql)) {
            $id = $conn->insert_id;
            notify(4, $id);
            return true;
        }
        return false;
    }
    public static function update($id, $title, $description, $company,  $type, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = 'default.jpg';
            $sql = "UPDATE `jobs` SET `type`='$type',`title`='$title',`description`='$description',`company`='$company' WHERE id = $id";
        } else {
            $target_dir = "../uploads/jobs/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
            $sql = "UPDATE `jobs` SET `type`='$type',`title`='$title',`description`='$description',`company`='$company', `picture` = '$img' WHERE id = $id";
        }

        if ($conn->query($sql)) return true;
        return false;
    }
    public static function destroy($id)
    {
        global $conn;
        $sql = "DELETE FROM `jobs` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }

    public static function destroyBulk($arr)
    {
        global $conn;
        $ids = implode(',', $arr);
        $sql = "DELETE FROM `jobs` WHERE id IN ($ids)";
        if ($conn->query($sql)) return true;
        return false;
    }
}
