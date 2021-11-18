<?php
namespace App\Model;

use App\Datasource\PDOSource;
use PDO;

class Posts
{
    private $db;

    public function __construct()
    {
        $this->db = PDOSource::getConnection();
    }

    public function getUnpublished() :array
    {
        $stmt = $this->db->query("SELECT * FROM posts WHERE publish_status = 0");
        $posts = $stmt->fetchAll();

        print_r($posts);

        return $posts;
    }

    public function setPublished($post) :void
    {
        $stmt = $this->db->prepare("UPDATE posts SET publish_status = 1 WHERE id = :id");
        $stmt->bindParam(':id', $post['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
}