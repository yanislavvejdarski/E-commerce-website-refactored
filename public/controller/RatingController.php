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
        $postParams = $this->request->postParams();

        $paramsAndRules = [
            $postParams['save'] => 'isVariableSet|isEmpty',
            $postParams['comment'] => 'isEmpty',
            $postParams['rating'] => 'isEmpty',

        ];
        if ($this->validator->validate($paramsAndRules)) {
            if ($this->commentValidation($this->request->postParam('comment'))) {
                $msg = 'Invalid comment!';
            } elseif ($this->ratingValidation($this->request->postParam('rating'))) {
                $msg = 'Invalid rating!';
            }
            $productDAO = new ProductDAO();
            if ($productDAO->findProduct($this->request->postParam('productId'))) {
                if ($msg == '') {
                    $ratingDAO = new RatingDAO();
                    $ratingDAO->addRating(
                        $this->session->getSessionParam('loggedUserId'),
                        $this->request->postParam('productId'),
                        $this->request->postParam('rating'),
                        $this->request->postParam('comment')
                    );
                    header('Location: product/' . $this->request->postParam('productId'));
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
        $paramsAndRules = [
            $postParams['saveChanges'] => 'isEmpty',
            $postParams['comment'] => 'isEmpty'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            if ($this->commentValidation($postParams['comment'])) {
                $msg = 'Invalid comment!';
            } elseif ($this->ratingValidation($postParams['rating'])) {
                $msg = 'Invalid rating!';
            }
            $ratingDAO = new RatingDAO();
            $rating = $ratingDAO->getRatingById($postParams['ratingId']);
            if ($rating->userId !== $this->session->getSessionParam('loggedUserId')) {
                throw new NotAuthorizedException('Not authorized for this operation!');
            } elseif ($msg == '') {
                $ratingDAO = new RatingDAO();
                $ratingDAO->editRating(
                    $postParams['ratingId'],
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
     * @param int $productId
     *
     * @return array
     */
    public function showStars($productId)
    {
        $ratingDAO = new RatingDAO();
        $productStars = $ratingDAO->getStarsCount($productId);
        $starsCountArr = [];
        for ($i = 1; $i <= 5; $i++) {
            $isZero = true;
            foreach ($productStars as $productStar) {
                if ($productStar['stars'] == $i) {
                    $starsCountArr[$i] = $productStar['starsCount'];
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
        $paramsAndRules = [
            $comment => 'lessThan:4|biggerThan:200'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $rating
     *
     * @return bool
     */
    public function ratingValidation($rating)
    {
        $paramsAndRules = [
            $rating => 'isNumeric'
        ];
        if (!$this->validator->validate($paramsAndRules) || !preg_match('/^[1-5]+$/', $rating)) {
            return true;
        }

        return false;
    }

    /**
     * Show My Rated Products Page
     */
    public function myRated()
    {
        $ratingDAO = new RatingDAO();
        $myRatings = $ratingDAO->showMyRated($this->session->getSessionParam('loggedUserId'));
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