<?php
require_once '../db/db_config.php';
require_once '../notifier.php';

class Events
{

    public static function getAllEvents($limit = 0)
    {
        global $conn;
        if ($limit != 0) {
            $sql = "SELECT * FROM events ORDER BY date_posted DESC LIMIT $limit";
        } else {
            $sql = "SELECT * FROM events ORDER BY date_posted DESC";
        }

        $results = $conn->query($sql);
        return $results;
    }
    public static function getEvent($id)
    {
        global $conn;
        $sql = "SELECT * FROM events WHERE id = $id";
        $results = $conn->query($sql);
        return $results;
    }

    public static function create($title, $description, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = 'default.jpg';
        } else {
            $target_dir = "../uploads/events/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
        }

        $sql = "INSERT INTO `events`( `picture`, `title`, `description`) VALUES ('" . $img . "','$title', '$description')";
        if ($conn->query($sql)) {
            $id = $conn->insert_id;
            notify(2, $id);
            return true;
        }

        return false;
    }
    public static function update($id, $title, $description, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = 'default.jpg';
            $sql = "UPDATE `events` SET `title`='$title',`description`='$description' WHERE id = $id";
        } else {
            $target_dir = "../uploads/events/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
            $sql = "UPDATE `events` SET `picture`='$img',`title`='$title',`description`='$description' WHERE id = $id";
        }

        if ($conn->query($sql)) return true;
        return false;
    }
    public static function destroy($id)
    {
        global $conn;
        $sql = "DELETE FROM `events` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }

    public static function destroyBulk($arr)
    {
        global $conn;
        $ids = implode(',', $arr);
        $sql = "DELETE FROM `events` WHERE id IN ($ids)";
        if ($conn->query($sql)) return true;
        return false;
    }
}
