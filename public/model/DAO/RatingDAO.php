<?php

namespace model\DAO;

use PDO;

class RatingDAO extends AbstractDAO
{
    /**
     * @param int $ratingId
     *
     * @return array
     */
    public function getRatingById($ratingId)
    {
        $params = ['ratingId' => $ratingId];
        $sql = '
            SELECT 
                *
            FROM 
                user_rate_products 
            WHERE 
                id = :ratingId
        ';

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param int $userId
     * @param int $productId
     * @param int $rating
     * @param string $comment
     */
    public function addRating(
        $userId,
        $productId,
        $rating,
        $comment
    ) {
        $params = [
            'userId' => $userId,
            'productId' => $productId,
            'rating' => $rating,
            'comment' => $comment
        ];
        $sql = '
            INSERT INTO
                user_rate_products 
                (
                    user_id,
                    product_id,
                    stars,
                    text,
                    date_created
                 ) 
            VALUES 
                (
                    :userId,
                    :productId,
                    :rating,
                    :comment,
                     now()
                 )
        ';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     * @param int $rating
     * @param string $comment
     */
    public function editRating(
        $id,
        $rating,
        $comment
    ) {
        $params = [
            'rating' => $rating,
            'comment' => $comment,
            'id' => $id
        ];
        $sql = '
            UPDATE
                user_rate_products 
            SET 
                stars = :rating,
                text = :comment 
            WHERE 
                id = :id 
        ';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function showMyRated($userId)
    {
        $params = ['userId' => $userId];
        $sql = '
            SELECT 
                p.id AS productId,
                p.name AS product_name,
                p.image_url,
                urp.id AS ratingId,
                urp.stars,
                urp.text
            FROM 
                user_rate_products AS urp
                JOIN products AS p ON (p.id = urp.product_id)
            WHERE
                urp.user_id = :userId
            ORDER BY 
                urp.date_created DESC
        ';

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getReviewsNumber($productId)
    {
        $params = ['productId' => $productId];
        $sql = '
            SELECT 
                round(avg(stars),2) AS avgStars,
                count(id) AS reviewsCount 
            FROM 
                user_rate_products 
            WHERE 
                product_id = :productId
        ';

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getStarsCount($productId)
    {
        $params = ['productId' => $productId];
        $sql = '
            SELECT 
                stars,
                count(stars) AS starsCount 
            FROM 
                user_rate_products 
            WHERE 
                product_id = :productId
            GROUP BY 
                stars
            ORDER BY
                stars
        ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getComments($productId)
    {
        $params = ['productId' => $productId];
        $sql = '
            SELECT 
                CONCAT
                (
                    u.first_name,
                     " ",
                    u.last_name
                 ) AS fullName,
                 urp.stars,
                 urp.text,
                cast(urp.date_created AS date) AS date 
            FROM 
                users AS u
                JOIN user_rate_products AS urp ON (u.id=urp.user_id) 
            WHERE
                product_id = :productId
        ';

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }
}