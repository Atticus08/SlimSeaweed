<?php namespace NamespacePath\Controllers;

require_once('FilePath/Controller.php');

class PhotoStoreController extends Controller
{

    public function __construct($FSClient)
    {
        $this->FSClient = $FSClient;
    }

    /**
     * Store Photo in Photo Store
     *
     * @param data - Photo data
     * @return mixed response from cURL
     */
    public function storePhoto($photo, $replication = null)
    {
        $response = $this->FSClient->volAssign(1, $replication);
        $response = json_decode($response, true);
        $volUrl = $response['publicUrl'];
        $fid = $response['fid'];
        $response = $this->FSClient->postFile($volUrl, $fid, $photo);
        
        return array(
            'response' => $response,
            'fid' => $fid
        );
    }

    /**
     * Deliver the Volume Server URL from master server.
     *
     * @param number - Volume ID
     * @param number - File ID
     * @return string - URL Path to Photo
     */
    public function getPhotoUrl($volId)
    {
        $response = $this->FSClient->lookupVol($volId);
        $response = json_decode($response, true);
        $url = $response['locations'][0]['publicUrl'];
        $volUrl = 'http://' . $url . '/';

        return $volUrl;
    }
}
