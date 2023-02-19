<?php
require_once '../db/db_config.php';

class Posts
{

    public static function getAllPosts($limit = 0)
    {
        global $conn;
        $id = $_SESSION['id'];
        if ($limit != 0) {
            $sql = "SELECT *, p.id as post_id FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.status != 0 OR p.user_id = $id ORDER BY p.id DESC LIMIT $limit";
        } else {
            $sql = "SELECT *, p.id as post_id FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.status != 0 OR p.user_id = $id ORDER BY p.id DESC ";
        }

        $results = $conn->query($sql);
        return $results;
    }

    public static function getAllUserPosts($id)
    {
        global $conn;
        $cid = $_SESSION['id'];
        $sql = "SELECT *, p.id as post_id FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.user_id = $id AND (p.status != 0 OR p.user_id = $cid) ORDER BY p.id DESC ";


        $results = $conn->query($sql);
        return $results;
    }
    public static function getPost($id)
    {
        global $conn;
        $sql = "SELECT *, p.id as post_id FROM posts  p INNER JOIN users u ON p.user_id = u.id WHERE p.id = $id";
        $results = $conn->query($sql);
        return $results;
    }

    public static function createComment($id, $comment)
    {
        global $conn;
        $userId = $_SESSION['id'];
        $sql = "INSERT INTO `comments`( `user_id`, `post_id`, `description`) VALUES ('$userId','$id','$comment')";
        if ($conn->query($sql)) {
            return true;
        } else return false;
    }

    public static function getComments($id)
    {
        global $conn;
        $sql = "SELECT * FROM comments p INNER JOIN users u ON p.user_id = u.id WHERE post_id = $id";
        $results = $conn->query($sql);
        return $results;
    }
    public static function create($title, $description, $image)
    {
        global $conn;
        $userId = $_SESSION['id'];
        if ($image['size'] == 0) {
            $img = null;
        } else {
            $target_dir = "../uploads/posts/";
            $img = uniqid()  . basename($image["name"]);
            $target_file = $target_dir . $img;
            move_uploaded_file($image["tmp_name"], $target_file);
        }

        $sql = "INSERT INTO `posts`( `user_id`, `picture`, `title`, `description`, `status`) VALUES ('$userId','$img' ,'$title','$description', 0)";
        if ($conn->query($sql)) return true;
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
        $sql = "DELETE FROM `posts` WHERE id = $id";
        if ($conn->query($sql)) return true;
        return false;
    }

    public static function destroyBulk($arr)
    {
        global $conn;
        $ids = implode(',', $arr);
        $sql = "DELETE FROM `posts` WHERE id IN ($ids)";
        if ($conn->query($sql)) return true;
        return false;
    }
}
