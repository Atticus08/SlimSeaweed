<?php namespace NamespacePath\Middleware;

class FileFilter
{
    protected $allowedFiles = array('image/jpeg', 'image/png');
            
    public function __invoke($request, $response, $next)
    {
        $files = $request->getUploadedFiles();
        if ($files) {
            $newFile = $files['file'];
            if ($newFile->getError() === UPLOAD_ERR_OK) {
                $newFileType = $newFile->getClientMediaType();
                // Send "Unsupported Media Type" response.
                if (!in_array($newFileType, $this->allowedFiles)) {
                    return $response->withStatus(415);
                }
            } else {
                // Error handling for upload error
            }
        } else {
            // Handling for when no files exist
        }
        $response = $next ($request, $response);

        return $response;
    }
}
