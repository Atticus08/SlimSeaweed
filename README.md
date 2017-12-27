# SlimSeaweed

## Overview
This repository is to hold "setup" files for any PHP Slim Framework projects I may need to implement in the future. Along with files that can be reused if a cURL client is needed, and the project is using SeaweedFS as a Distributed File System. 

### Brief History
To become familiar with Lamp stack development, I came up with a small project, which would accomplish the following:
1. Users can sign up and sign in with full name, username, email, and password.
    + A very slimmed down authentication model was implemented. The skeleton middleware code can be reused, but it was not intended to be used for production. It was more for studying and understanding the OAuth2.0 protocol.
2. Users can update their profiles and profile images.
3. Users will be able to unfollow/follow other users, and see who is currently following them.
4. Users can take photos, and upload them to the server.

 The MySQL database, and all Apache server and web API files ran on my Mac (so, I guess you can say I actually did MAMP). I used the Slim Framework (version 3.8) for PHP to develop the routes and endpoints for the web API. I also integrated SeaweedFS to act as a scalable distributed file system, which would host all of the photos that were uploaded by users. The FS virtual volume servers and host server resided on my Mac as well. 

The project was intended to be used ONLY for mobile-clients. An iOS application was developed for testing everything. 

With all of that said, the code on here (especially the middleware for Authentication) should probably not be used for production straight out of the box. The SeaweedFS client, cURL, Photostore, and Database controllers are set up to "drop, go, and play". Along with the the public html, dependencies, settings, and routing files as well. 