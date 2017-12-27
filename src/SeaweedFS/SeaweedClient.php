<?php namespace NamespacePath\SeaweedFS;

use FilePath\Curl;

/**
 *
 * PHP Client for the application's Seaweed Distributed File System
 *
 *
 * @author Tom Fritz
 *
 */

class SeaweedClient
{

    /* @var Curl session */
    protected $curl;
    
    /* @var string for master server "http://ip:port" */
    protected $master;

    /**
     * Construct SeaweedFS client with cURL and master served location
     *
     * @param string $master
     */

    public function __construct($master)
    {
        $this->curl = new Curl();
        $this->master = $master;
    }

    /**
     * Returns System Status
     *
     * @return mixed response from cURL
     */
    public function sysStatus()
    {
        $url = $this->master . 'dir/status';
        
        $response = $this->curl->get($url);
        $this->curl->close();
        
        return $response;
    }

     /**
     * Returns status of a volume server
     *
     * @param string $volUrl - volume server address
     * @return mixed response from cURL
     */
    public function volServerStatus($volUrl)
    {
        $url = 'http://' . $volUrl . '/status';
        
        $response = $this->curl->get($url);
        $this->curl->close();
        
        return $response;
    }

     /**
     * Returns status of volumes
     *
     * @return mixed response from cURL
     */
    public function volStatus()
    {
        $url = $this->master . '/status';
        
        $response = $this->curl->get($url);
        $this->curl->close();
        
        return $response;
    }

    /**
     * Retrieve the fid and volume server location from master server
     *
     * @param number $count
     * @param string $replication - For options on replication, refer to Github documentation on Seaweed.fs - https://github.com/chrislusf/seaweedfs
     * @return mixed response from cURL
     */
    public function volAssign($count = 1, $replication = null)
    {
        $url = $this->master . 'dir/assign';
        $url .= '?count=' . intval($count);

        if (!is_null($replication)) {
            $url .= '&replication=' . $replication;
        }

        $response = $this->curl->get($url);

        $this->curl->close();

        // {"count":number,"fid":string,"url":string, "publicUrl":string}
        return $response;
    }

    /**
     * Returns the URL for the Volume server that the volume ID is on
     *
     * @param string $volId - Volume ID for File
     * @return mixed response from cURL
     */
    public function lookupVol($volId)
    {
        $url = $this->master . '/dir/lookup?volumeId=' . $volId;
        
        $response = $this->curl->get($url);
        $this->curl->close();
        
        return $response;
    }

    /**
     * Store File in Store
     *
     * @param string $volUrl - volume server address
     * @param string $fid - file id
     * @param data $data - file data
     *
     * @return mixed response from cURL
     */
    public function postFile($volUrl, $fid, $data)
    {
        $url = 'http://' . $volUrl . '/' . $fid;
        $params = array('file' => "@$data");
        $response = $this->curl->post($url, $params);
        $this->curl->close();

        return $response;
    }

     /**
     *
     * Store multiple files in store at once
     *
     * @param string $volUrl - volume server address
     * @param string $fid - file id
     * @param array $files
     * @return mixed responce from cURL
     */
    public function postMultipleFiles($volUrl, $fid, array $files)
    {
        $count = count($files);
        
        $url = 'http://' . $volUrl . '/' . $fid;

        // TODO: - REWRITE THIS CODE AFTER IT'S BEEN TESTED
        $response = array();
        for ($i = 1; $i <= $count; $i++) {
            $parameters = array('file'=>$files[$i-1]);
            
            $response[] = $this->curl->post($url, $parameters);
            
            $url = 'http://' . $volUrl . '/' . $fid . '_' . $i;
        }
        
        $this->curl->close();
        return $response;
    }

     /**
     * Retrieve a file from a specific volume server by fid
     *
     * @param string $volUrl - volume server address
     * @param string $fid - file id
     * @return mixed response from cURL
     */
    public function getFile($volUrl, $fid)
    {
        $url = 'http://' . $volUrl . '/' . $fid;
        
        $response = $this->curl->get($url);
        $this->curl->close();

        return $response;
    }

     /**
     * Delete a file by fid on specified volume server
     *
     * @param string $volUrl - volume server address
     * @param string $fid - file id
     * @return mixed response from cURL
     */
    public function deleteFile($volUrl, $fid)
    {
        $url = 'http://' . $volUrl . '/' . $fid;
        
        $response = $this->curl->custom($url, 'DELETE');
        $this->curl->close();

        return $response;
    }
}
