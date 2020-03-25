<?php

namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;
use model\DAO\ProductDAO;
use model\DAO\RatingDAO;
use helpers\Request;

class ratingController extends AbstractController
{
    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function rate()
    {
        $msg = '';
        if (!empty($this->request->postParam('save'))) {
            if (empty($this->request->postParam('comment')) || empty($this->request->postParam('rating'))) {
                $msg = 'All fields are required!';
            } elseif ($this->commentValidation($this->request->postParam('comment'))) {
                $msg = 'Invalid comment!';
            } elseif ($this->ratingValidation($this->request->postParam('rating'))) {
                $msg = 'Invalid rating!';
            }
            $productDAO = new ProductDAO();
            if ($productDAO->findProduct($this->request->postParam('product_id'))) {
                if ($msg == '') {
                    $ratingDAO = new RatingDAO();
                    $ratingDAO->addRating(
                        $this->session->getSessionParam('logged_user_id'),
                        $this->request->postParam('product_id'),
                        $this->request->postParam('rating'),
                        $this->request->postParam('comment')
                    );
                    header('Location: product/' . $this->request->postParam('product_id'));
                } else {
                    throw new BadRequestException($msg);
                }
            } else {
                throw new NotAuthorizedException('Not authorized for this operation!');
            }
        }
    }

    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function editRate()
    {
        $msg = '';
        $postParams = $this->request->postParams();
        if (!empty($postParams['saveChanges'])) {
            if (empty($postParams['comment']) || empty($postParams['comment'])) {
                $msg = 'All fields are required!';
            } elseif ($this->commentValidation($postParams['comment'])) {
                $msg = 'Invalid comment!';
            } elseif ($this->ratingValidation($postParams['rating'])) {
                $msg = 'Invalid rating!';
            }
            $ratingDAO = new RatingDAO();
            $rating = $ratingDAO->getRatingById($postParams['rating_id']);
            if ($rating->user_id !== $this->session->getSessionParam('logged_user_id')) {
                throw new NotAuthorizedException('Not authorized for this operation!');
            } elseif ($msg == '') {
                $ratingDAO = new RatingDAO();
                $ratingDAO->editRating(
                    $postParams['rating_id'],
                    $postParams['rating'],
                    $postParams['comment']
                );
                header('Location: /ratedProducts');
            }
        } else {
            throw new BadRequestException($msg);
        }
    }

    /**
     * @param int $product_id
     *
     * @return array
     */
    public function showStars($product_id)
    {
        $ratingDAO = new RatingDAO();
        $product_stars = $ratingDAO->getStarsCount($product_id);
        $starsCountArr = [];
        for ($i = 1; $i <= 5; $i++) {
            $isZero = true;
            foreach ($product_stars as $product_star) {
                if ($product_star['stars'] == $i) {
                    $starsCountArr[$i] = $product_star['stars_count'];
                    $isZero = false;
                }
            }
            if ($isZero) {
                $starsCountArr[$i] = 0;
            }
        }

        return $starsCountArr;
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    public function commentValidation($comment)
    {
        $err = false;
        if (strlen($comment) < 4 || strlen($comment) > 200) {
            $err = true;
        }

        return $err;
    }

    /**
     * @param int $rating
     *
     * @return bool
     */
    public function ratingValidation($rating)
    {
        $err = false;
        if (!is_numeric($rating) || !preg_match('/^[1-5]+$/', $rating)) {
            $err = true;
        }

        return $err;
    }

    /**
     * Show My Rated Products Page
     */
    public function myRated()
    {
        $ratingDAO = new RatingDAO();
        $myRatings = $ratingDAO->showMyRated($this->session->getSessionParam('logged_user_id'));
        include_once 'view/myRated.php';
    }

    /**
     * Show Rating Product Page
     */
    public function rateProduct()
    {
        $getParams = $this->request->getParams();
        include_once 'view/rateProduct.php';
    }

    /**
     * Show Edit Product Rating Page
     */
    public function editRatedPage()
    {
        include_once 'view/editRatedProduct.php';
    }
}