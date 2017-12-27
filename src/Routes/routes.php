<?php

/*
 Include files that need to be referenced here
 */

$container = $app->getContainer();

$app->group('/user', function () use ($container) {
    $photoC = $container->get('PhotoStoreController');

    $this->post('/loginuser', UserController::class . ':loginUser');
    $this->post('/createuser', UserController::class . ':createUser');
    // Should be PUT, but Slim doesn't like PUT for some reason?
    $this->post('/updateuser/{userId}', UserController::class . ':updateUser')->add(new FileMove())->add(new ImageRemoveExif($photoC))->add(new FileFilter());

    $this->post('/followuser/{userId}/{followingId}', UserController::class . ':followUser');
    $this->get('/whotofollow/{userId}', UserController::class . ':getWhoToFollow');
    $this->get('/getfollowers/{userId}', UserController::class . ':getFollowers');
    $this->get('/getfollowing/{userId}', UserController::class . ':getFollowing');
    $this->get('/getfollowcount/{userId}', UserController::class . ':getFollowCount');
    $this->delete('/unfollowuser/{userId}/{followingId}', UserController::class . ':unfollowUser');
});
