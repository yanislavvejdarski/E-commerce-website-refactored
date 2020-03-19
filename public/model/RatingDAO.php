<?php

namespace model;

use PDO;

class RatingDAO extends AbstractDAO
{
    /**
     * @param $rating_id int
     *
     * @return array
     */
    public function getRatingById($rating_id)
    {
        $params = [];
        $params[] = $rating_id;
        $sql = "SELECT * FROM user_rate_products 
                WHERE id=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param $user_id int
     * @param $product_id int
     * @param $rating int
     * @param $comment string
     */
    public function addRating(
        $user_id,
        $product_id,
        $rating,
        $comment
    ) {
        $params = [];
        $params[] = $user_id;
        $params[] = $product_id;
        $params[] = $rating;
        $params[] = $comment;
        $sql = "INSERT INTO user_rate_products (user_id, product_id, stars,text,date_created) 
                VALUES (?,?,?,?,now());";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     * @param $rating int
     * @param $comment string
     */
    public function editRating(
        $id,
        $rating,
        $comment
    ) {
        $params = [];
        $params[] = $rating;
        $params[] = $comment;
        $params[] = $id;
        $sql = "UPDATE user_rate_products SET stars=?, text=? 
                WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $user_id int
     *
     * @return array
     */
    public function showMyRated($user_id)
    {
        $params = [];
        $params[] = $user_id;
        $sql = "SELECT p.id AS product_id,p.name AS product_name,p.image_url,urp.id AS rating_id,urp.stars,urp.text
                FROM user_rate_products AS urp
                JOIN products AS p ON(p.id=urp.product_id)
                WHERE urp.user_id=?;";

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param $product_id int
     *
     * @return array
     */
    public function getReviewsNumber($product_id)
    {
        $params = [];
        $params[] = $product_id;
        $sql = "SELECT round(avg(stars),2)  AS avg_stars , count(id) AS reviews_count FROM user_rate_products 
                WHERE product_id=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param $product_id int
     *
     * @return array
     */
    public function getStarsCount($product_id)
    {
        $params = [];
        $params[] = $product_id;
        $sql = "SELECT stars,count(stars)  AS stars_count  FROM user_rate_products 
                WHERE product_id=? GROUP BY stars ORDER BY stars;";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $product_id int
     *
     * @return array
     */
    public function getComments($product_id)
    {
        $params = [];
        $params[] = $product_id;
        $sql = "SELECT concat(u.first_name,\" \", u.last_name) AS full_name,
                urp.stars,urp.text, cast(urp.date_created AS date) AS date FROM users AS u
                JOIN user_rate_products AS urp ON(u.id=urp.user_id) 
                WHERE product_id=?";

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }
}