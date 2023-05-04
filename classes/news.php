<?php
require_once '../db/db_config.php';
require_once '../notifier.php';

class News
{

    public static function getAllNews($limit = 0)
    {
        global $conn;
        if ($limit != 0) {
            $sql = "SELECT * FROM news ORDER BY id DESC LIMIT $limit";
        } else {
            $sql = "SELECT * FROM news ORDER BY id DESC ";
        }

        $results = $conn->query($sql);
        return $results;
    }
    public static function getNews($id)
    {
        global $conn;
        $sql = "SELECT * FROM news WHERE id = $id";
        $results = $conn->query($sql);
        return $results;
    }

    public static function create($title, $author, $description, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = 'default.jpg';
        } else {
            $target_dir = "../uploads/news/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
        }

        $sql = "INSERT INTO `news`( `picture`, `title`, `author`, `description`) VALUES ('" . $img . "','$title','$author','$description')";
        if ($conn->query($sql)) {
            $id = $conn->insert_id;
            notify(1, $id);
            return true;
        }


        return false;
    }
    public static function update($id, $title, $author, $description, $image)
    {
        global $conn;

        if ($image['size'] == 0) {
            $img = 'default.jpg';
            $sql = "UPDATE `news` SET `title`='$title',`author`='$author',`description`='$description' WHERE id = $id";
        } else {
            $target_dir = "../uploads/news/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
            $sql = "UPDATE `news` SET `picture`='$img',`title`='$title',`author`='$author',`description`='$description' WHERE id = $id";
        }

        if ($conn->query($sql)) return true;
        return false;
    }
    public static function destroy($id)
    {
        global $conn;
        $sql = "DELETE FROM `news` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }

    public static function destroyBulk($arr)
    {
        global $conn;
        $ids = implode(',', $arr);
        $sql = "DELETE FROM `news` WHERE id IN ($ids)";
        if ($conn->query($sql)) return true;
        return false;
    }
}
