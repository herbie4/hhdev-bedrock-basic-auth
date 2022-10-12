<?php
/*
Plugin Name: HHdev Bedrock Basic Auth
Plugin URI: https://haha.nl
Description: Basic auth for the Bedrock WordPress framework. Optional authenticate whole site or just login. Works with default wp-login.php or with WPS Hide Login plug-in changed url.
Author: herbert hoekstra - haha!
Version: 1.0.2
Author URI: https://haha.nl
*/

// temp fix on CGI systems
// also needs this in htaccess
/*
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>
*/
/*
// use HHdev Bedrock authentication
Config::define('HTTP_AUTH_USER_NAME', 'toegang' );
Config::define('HTTP_AUTH_USER_PASS', 'toegestaan' );
Config::define('HTTP_AUTH_ENABLED', true ); // use true or false
//Config::define('HTTP_AUTH_AREA', 'login' ); // use 'site' or 'login'
*/

function hhdev_requireAuth($user, $pass) {

    header('Cache-Control: no-cache, must-revalidate, max-age=0');

    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

    $is_not_authenticated = (
        !$has_supplied_credentials ||
        $_SERVER['PHP_AUTH_USER'] != $user ||
        $_SERVER['PHP_AUTH_PW']   != $pass
    );

    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        wp_die(
            'Access denied.',
            'Authorization Required',
            array('response' => 401)
        );
    }
}


function hhdev_initAuth() {

    if (defined('HTTP_AUTH_ENABLED') && HTTP_AUTH_ENABLED && php_sapi_name() !== "cli") {

      $admin_slug = '/wp/wp-login.php'; // default login

      // check to use other login slug via WPS Hide Login
      if(file_exists(WP_PLUGIN_DIR . '/wps-hide-login/wps-hide-login.php')) $admin_slug = '/' . get_option( 'whl_page' ).'/';

        if (defined('HTTP_AUTH_USER_NAME') && defined('HTTP_AUTH_USER_PASS')) {
            // whole website
            if (strcasecmp(HTTP_AUTH_LEVEL, 'site') === 0) {
                hhdev_requireAuth(HTTP_AUTH_USER_NAME, HTTP_AUTH_USER_PASS);
            // login only
            } elseif (strcasecmp(HTTP_AUTH_LEVEL, 'login') === 0 && trim(strtok($_SERVER['REQUEST_URI'], '?')) == $admin_slug) {
                hhdev_requireAuth(HTTP_AUTH_USER_NAME, HTTP_AUTH_USER_PASS);
            }
        }
    }
}

hhdev_initAuth();
