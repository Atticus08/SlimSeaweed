<?php namespace PicApp\Controllers\DbControllers;

abstract class DbController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
