<?php namespace NamespacePath\Middleware;

/*
 Would definitely not put this into production, but this is a 
 very simple authentication middleware scheme.
*/

class Authentication
{
    protected $db;
    private $whiteList;
        
    public function __construct($db)
    {
        $this->db = $db;
        //Define the urls that you want to exclude from Authentication
        $this->whiteList = array('URL1', 'URL2');
    }

    public function __invoke($request, $response, $next)
    {
        // Obtain URI, and verify if the request needs to be authenticated.
        $uri = $request->getUri();
        if ($this->isAuthenticationReq($uri)) {
            if ($request->hasHeader('Authorization')) {
                // Retreive Authorization Bearer Token.
                $auth = $request->getHeader('Authorization');
                $_apiKey = $auth[0];
                $apiKey = substr($_apiKey, strpos($_apiKey, ' '));

                // Verify auth key is valid.
                $validKey = $this->authenticate($apiKey);
                if ($validKey) {
                    $response = $next($request, $response);
                } else {
                    $this->denyAccess($response);
                    return $response->withHeader('Content-Type', 'application/json')
                    ->write(json_encode("Key is not valid"));
                }
            } else {
                // Deny the user access because the request did not contain a header with an
                // authorization key.
                $this->denyAccess($response);
                return $response->withHeader('Content-Type', 'application/json')
                    ->write(json_encode("An Authorization header needs to exist on request!"));
            }
        } else {
            // If the route does not need to be authorized, move to the next link in the request.
            $response = $next($request, $response);
        }
        return $response;
    }

    /**
     * Deny Access
     */
    private function denyAccess(&$response)
    {
        $response->withStatus(401);
    }

    /**
     * Check to see if the API Key is Valid
     *
     * @param string $apiKey
     * @return bool
     */
    private function authenticate($apiKey)
    {
        return $this->db->authenticate($apiKey);
    }

    /**
     * This function will compare the provided url against the whitelist and
     * return wether the url needs to be authenticated or not.
     *
     * @param string $url
     * @return bool
     */
    private function isAuthenticationReq($uri)
    {
        $patterns_flattened = implode('|', $this->whiteList);
        $matches = null;
        preg_match('/' . $patterns_flattened . '/', $uri, $matches);
        return (count($matches) == 0);
    }
}
