<?php namespace NamespacePath\DbControllers;

require_once('FilePath/DbController.php');
use \PDO;

class UserDb extends DbController
{    
    public function getMyUserInfo($username)
    {
        $stmt = $this->db->prepare('CALL Procedure (:username)');
        $stmt->bindParam(':username', $username);
        $stmtResult = $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return array(
            'stmtResult' => $stmtResult,
            'data' => $user
        );
    }


    public function createUser($email, $firstName, $lastName, $username, $hashedPassword, $bioText)
    {
        $stmt = $this->db->prepare('CALL Procedure(:email, :firstName, :lastName, :password, :username, :bioText)');
        $stmtResult = $stmt->execute(array(
            ':email' => $email,
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':bioText' => $bioText
        ));
        return array(
            'stmtResult' => $stmtResult,
            'data' => null
        );
    }

    public function updateUser($query)
    {
        $stmt = $this->db->prepare($query);
        $stmtResult = $stmt->execute();
        return array(
            'stmtResult' => $stmtResult,
            'data' => null
        );
    }

    public function followUser($userId, $followingId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId,:followingId)');
        $stmtResult = $stmt->execute(array(
            ':userId' => $userId,
            ':followingId' => $followingId
        ));
        return array(
            'stmtResult' => $stmtResult,
            'data' => null
        );
    }

    public function unfollowUser($userId, $followingId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId,:followingId)');
        $stmtResult = $stmt->execute(array(
            ':userId' => $userId,
            ':followingId' => $followingId
        ));
        return array(
            'stmtResult' => $stmtResult,
            'data' => null
        );
    }

    public function getWhoToFollow($userId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId)');
        $stmt->bindParam(':userId', $userId);
        $stmtResult = $stmt->execute();
        $user = $stmt->fetchAll();
        return array(
            'stmtResult' => $stmtResult,
            'data' => $user
        );
    }

    public function getFollowers($userId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId)');
        $stmt->bindParam(':userId', $userId);
        $stmtResult = $stmt->execute();
        $followers = $stmt->fetchAll();
        return array(
            'stmtResult' => $stmtResult,
            'data' => $followers
        );
    }

    public function getFollowing($userId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId)');
        $stmt->bindParam(':userId', $userId);
        $stmtResult = $stmt->execute();
        $following = $stmt->fetchAll();
        return array(
            'stmtResult' => $stmtResult,
            'data' => $following
        );
    }

    public function getFollowCount($userId)
    {
        $stmt = $this->db->prepare('CALL Procedure(:userId)');
        $stmt->bindParam(':userId', $userId);
        $stmtResult = $stmt->execute();
        $followCount = $stmt->fetchAll();
        return array(
            'stmtResult' => $stmtResult,
            'data' => $followCount
        );
    }
}
