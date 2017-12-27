<?php namespace NamespacePath\Middleware;

class ImageRemoveExif
{
    protected $photoStoreController;
        
    public function __construct($photoStoreController)
    {
        $this->photoStoreController = $photoStoreController;
    }

    public function __invoke($request, $response, $next)
    {
        $files = $request->getUploadedFiles();
        if ($files) {
            $newFile = $files['file'];
            if ($newFile->getError() === UPLOAD_ERR_OK) {
                $newFileType = $newFile->getClientMediaType();
                if ('image/jpeg' == $newFileType) {
                    $modifiedPhoto = $this->removeExif($newFile);
                    if (!is_null($modifiedPhoto)) {
                        $storeResp = $this->photoStoreController->storePhoto($modifiedPhoto);
                        $fid = $storeResp['fid'];
                        unlink($modifiedPhoto);
                    }
                }
            }
        }
        $response = $next ($request->withAttribute('imageURL', $fid), $response);
        return $response;
    }

    private function removeExif(&$file)
    {
        $filename = $file->getClientFileName();
        $file->moveTo(ROOT . EXIF_PATH . $filename);
        $_img = imagecreatefromjpeg(ROOT . EXIF_PATH . $filename);
        $path = ROOT . EXIF_PATH . 'temp' . $filename;
        if (!imagejpeg($_img, $path)) {
            $path = null;
        }
        unlink(ROOT . EXIF_PATH . $filename);
        return $path;
    }
}
