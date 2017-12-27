<?php namespace NamespacePath\Controllers;

class UserController extends Controller
{
    //------------------------------------------
    // User Controller Route Functions
    //------------------------------------------

    // Route to create new user in database
    public function createUser($request, $response)
    {
        // Parse request
        $input = $request->getParsedBody();
        
        // Create data array for response
        $responseData = array();

        // Verify that the username or email doesn't already exist
        $userCheckResult = $this->isUserExists($input['email'], $input['username']);

        // If email or username doesn't already exist, register new user in database
        if ($userCheckResult == 0) {
            // Generate password hash
            $password = $this->generateHashPassword($input['password']);

            // Record user info from POST request
            $email = $input['email'];
            $firstName = $input['firstName'];
            $lastName = $input['lastName'];
            $username = $input['username'];
            $bioText = $input['bioText'];

            // Create MySQL Statement
            $stmt = $this->container->db->prepare('INSERT SQL Statement VALUES (:email, :firstName, :lastName, :password, :username, :bioText)');

            // Bind parameters, and execute MySQL statement
            $result = $stmt->execute(array(
                ':email' => $email,
                ':firstName' => $firstName,
                ':lastName' => $lastName,
                ':username' => $username,
                ':password' => $password,
                ':bioText' => $bioText
            ));

            // Successfully created user
            if ($result) {
                //Making the response error false
                    $responseData = $this->setupResponseMessage(false, 0, "You are successfully registered");
            } else { // Failed to create user
                    $responseData = $this->setupResponseMessage(true, 0, "Oops! An error occurred while registering");
            }
            
        // User already exists
        } elseif ($userCheckResult == 1) {
            $responseData = $this->setupResponseMessage(true, 1, "Sorry, this email already existed");
        } else {
            $responseData = $this->setupResponseMessage(true, 2, "Sorry, this username already existed");
        }
        
            // Encode and display JSON response
            return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($responseData));
    }

    // Route which gets called by client to login user
    public function loginUser($request, $response)
    {
        // Parse request
        $input = $request->getParsedBody();

        $username = $input['username'];
        $password = $input['password'];
        if ($this->db->verifyUserPassword($username, $password)) {
            // Retrieve user info
            $user = $this->getMyUserInfo($username);
            // Generate temporary API key for application, and set expiration data for key.
            $this->generateAPIKey($apiKey, $apiKeyExpiration);
            // Update user's API Key
            $dbResult = $this->db->updateApiKey($apiKey, $apiKeyExpiration, $user['UserID']);

            if ($dbResult['stmtResult']) {
                // Generate Response Data
                $message = array(
                    'error' => false,
                    'userId' => $user['UserID'],
                    'apiKey' => $apiKey,
                    'apiKeyExpire' => $apiKeyExpiration,
                    'email' => $user['Email'],
                    'firstName' => $user['FirstName'],
                    'lastName' => $user['LastName'],
                    'username' => $user['Username'],
                    'followerCount' => $user['FollowerCount'],
                    'followingCount' => $user['FollowingCount'],
                    'profileImageURL' => $user['ProfileImageURL'],
                    'bioText' => $user['BioText'],
                    'message' => "Login Successfull!"
                );
            } else {
                $responseData = $this->setupResponseMessage(true, 0, "Issue with Updating Auth Key");
            }
            $responseData = $this->setupResponseMessage(false, 0, $message);
        } else {
            $responseData = $this->setupResponseMessage(true, 0, "Username or Password is Incorrect");
        }
            // Encode and display JSON response
            return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($responseData));
    }

    // Route which will allow the user to follow another user
    public function followUser($request, $response, $args)
    {
        $userId = $args['userId'];
        $followingId = $args['followingId'];

        try {
            $dbResult = $this->db->followUser($userId, $followingId);
            if ($dbResult['stmtResult']) {
                $error = false;
                $errorCode = 0;
                $message = "Successfully Followed User!";
            } else {
                $error = true;
                $errorCode = 1;
                $message = "Failed to Follow User!";
            }
        } catch (\PDOException $e) {
            $error = true;
            $errorCode = 2;
            $message = "PDO Interface Threw An Exception!";
        }
        
        $responseData = $this->setupResponseMessage($error, $errorCode, $message);
        // Encode and send out JSON response
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($responseData));
    }

    // Route which will allow the user to unfollow another user
    public function unfollowUser($request, $response, $args)
    {
        $userId = $args['userId'];
        $followingId = $args['followingId'];
        try {
            $dbResult = $this->db->unfollowUser($userId, $followingId);
            //Generate Response Data
            if ($dbResult) {
                $error = false;
                $errorCode = 0;
                $message = "Successfully Unfollowed User!";
            } else {
                $error = true;
                $errorCode = 1;
                $message = "Failed to Unfollow User!";
            }
        } catch (\PDOException $e) {
            $error = true;
            $errorCode = 2;
            $message = $e->getMessage();
        }
        
        $responseData = $this->setupResponseMessage(true, $errorCode, $message);
        // Encode and send out JSON response
        return $response->withStatus(202)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($responseData));
    }

    // Route which will load followers for users
    public function getFollowers($request, $response, $args)
    {
        $userId = $args['userId'];
        $dbResult = $this->db->getFollowers($userId);
        if ($dbResult['stmtResult']) {
            $followers = $dbResult['data'];
            $this->generateImageUrl($followers, 'ProfileImageURL');
            $responseData = $this->setupResponseMessage(false, 0, $followers);
        } else {
            $responseData = $this->setupResponseMessage(true, 1, 'Failed to Load Followers');
        }
        // Encode and send out JSON response
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($responseData));
    }

    // Route which will load users the user is following
    public function getFollowing($request, $response, $args)
    {
        $userId = $args['userId'];
        $dbResult = $this->db->getFollowing($userId);
        if ($dbResult['stmtResult']) {
            $following = $dbResult['data'];
            $this->generateImageUrl($following, 'ProfileImageURL');
            $responseData = $this->setupResponseMessage(false, 0, $following);
        } else {
            $responseData = $this->setupResponseMessage(true, 1, 'Failed to Load Following');
        }
        // Encode and send out JSON response
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($responseData));
    }

        // Route which will load followers for users
    public function getFollowCount($request, $response, $args)
    {
        $userId = $args['userId'];
        $dbResult = $this->db->getFollowCount($userId);
        if ($dbResult['stmtResult']) {
            $responseData = $this->setupResponseMessage(false, 0, $dbResult['data']);
        } else {
            $responseData = $this->setupResponseMessage(true, 1, 'Failed to Load Follow Counts');
        }
        // Encode and send out JSON response
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($responseData));
    }

    // Route which updates the users personal information
    public function updateUser($request, $response, $args)
    {
        // Logic to update the user information in the database.
    }

    //------------------------------------------
    // User Controller Private Functions
    //------------------------------------------

    // Method to check whether or not the user already exists
    // 0 = OK, 1 = email exists, 2 = username exists
    private function isUserExists($email, $username)
    {
        // Prepare MySQL statement, and execute
        if (!is_null($email)) {
            $stmt = $this->container->db->prepare('SELECT SQL Query = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 1;
            }
        }
    
        // If no emails matched anyone else in the database, check to make sure the username is
        // unique as well.
        if (!is_null($username)) {
            $stmt = $this->container->db->prepare('SELECT SQL Query = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 2;
            }
        }

        // Return 0 if the username/email are both unique
        return 0;
    }

    /**
     * Grab My User's information, and generate his/her profile image URL.
     *
     * @param string - Username for the info we want to grab.
     * @return mixed - User Information
     */
    private function getMyUserInfo($username)
    {
        $dbResult = $this->db->getMyUserInfo($username);
        $user = $dbResult['data'];
        $this->generateImageUrl($user['ProfileImageURL']);
        return $user;
    }

    // Method to generate unique API Key, and expiration date.
    private function generateAPIKey(&$apiKey, &$apiKeyExpiration)
    {
        $apiKey = md5(uniqid(rand(), true));

        // Set the UTC time for the key's expiration time
        ini_set('date.timezone', 'UTC');
        $apiKeyExpiration = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    }

    // Method to generate password hash
    private function generateHashPassword($password)
    {
        // Hash the password. $hashedPassword will be a 60-character string.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashedPassword;
    }
}
