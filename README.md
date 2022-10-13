# Bedrock add basic authentication

 Basic auth for the Bedrock WordPress framework. Optional authentication for whole site or just login. Works with default wp-login.php or with WPS Hide Login plug-in changed login url.

 ## How to use

 Just download and install in mu-plugins folder. Add the config settings to the desired environment file, like staging.php or production.php.

 '''
 // use HHdev Bedrock authentication
 Config::define('HTTP_AUTH_USER_NAME', 'username' );
 Config::define('HTTP_AUTH_USER_PASS', 'password' );
 Config::define('HTTP_AUTH_ENABLED', true ); // use true or false
 Config::define('HTTP_AUTH_AREA', 'login' ); // use 'site' or 'login'
 '''

 If basic authentication doesn't work on some servers, try and use the code below in webroot .htaccess. New WordPress versions already add this in the default rewrite rules. Just check if it is there already. 

 '''
 /*
 <IfModule mod_rewrite.c>
 RewriteEngine On
 RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
 </IfModule>
 */
 '''

 ## Change Log
 * 1.0.0 initial set up
 * 1.0.1 added site and login only switch
 * 1.0.2 added WPS Hide Login compatibility
