<?php namespace NamespacePath\Controllers;

abstract class Controller
{
    
    protected $db = null;
    protected $photoStoreController = null;

    public function __construct($dbService = null, $photoStoreController = null)
    {
        $this->db = $dbService;
        $this->photoStoreController = $photoStoreController;
    }

    // Method to setup the response messages to URL requests.
    protected function setupResponseMessage($error, $errorCode, $message)
    {
        $responseMessage = array(
            'error' => $error,
            'errorCode' => $errorCode,
            'message' => $message
        );
        return $responseMessage;
    }

    /**
     * Generate Image URLs based on requests to Master Photo Store Server.
     * Elements array is passed by reference, and will be updated within this function.
     *
     * @param any - The elements that need to be updated
     * @param any - The element index that needs to be updated in element array
     */
    protected function generateImageUrl(&$elements, $index = null)
    {
        // If index is null, that means we're only updating the one element/photo passed in.
        if (!is_null($index)) {
            $volIdArray = array();
            foreach ($elements as &$element) {
                if (isset($element[$index])) {
                    $fid = $element[$index];
                    $volId = strtok($element[$index], ',');

                    // Determine the Volume Server Location.
                    // In our design, each volume server will have the same number of volumes.
                    $volServerLoc = (int)(((int)$volId / MAX_VOL_ON_SERVER));
                    
                    // Verify that the fid is not corrupted by checking it's volume ID
                    if ($volServerLoc <= MAX_VOL_SERVERS) {
                        // Only send request if that specific volume server URL hasn't been retrieved yet.
                        if (!array_key_exists($volServerLoc, $volIdArray)) {
                            $volIdArray[$volServerLoc] = $this->photoStoreController->getPhotoUrl($volId);
                        }
                        $element[$index] = $volIdArray[$volServerLoc] . $fid;
                    } else {
                        echo "Volume Server Does Not Exist! ID Corrupted! \n";
                    }
                }
            }
        } else {
            if (isset($elements)) {
                $fid = $elements;
                $volId = strtok($elements, ',');
                // Determine the Volume Server Location.
                // In this project example, each volume server will have the same number of volumes.
                $volServerLoc = (int)(((int)$volId / MAX_VOL_ON_SERVER));
                
                // Verify that the fid is not corrupted by checking it's volume ID
                if ($volServerLoc <= MAX_VOL_SERVERS) {
                    $elements = $this->photoStoreController->getPhotoUrl($volId) . $fid;
                }
            }
        }
    }

    // Left this here to copy and paste within code later. Will figure out implementing benchmarking later.
    protected function benchMark()
    {
        $time_start = microtime(true);
        $time_end = microtime(true);
        return ($time_end - $time_start);
    }
}
