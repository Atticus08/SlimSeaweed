<?php namespace DbStdFunctions;

class DbConnect
{
    // Method to create connection link
    public function connect()
    {
        // Include Constants file if not already included
        include_once dirname(__FILE__) . '/constants.php';

        // Create connection, and verify connection to DB is established
        $this->db = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to database: " . mysqli_connect_error();
            return 0;
        }

        // Turn off auto commit mode for queries on database connection
        $this->db->autocommit(false);

        // Return Connection Link
        return $this->db;
    }
}
