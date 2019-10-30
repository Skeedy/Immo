<?php
//service account
define('CLIENT_ID', '407963098975-vlhise79b7373ivpblogubvob6qgrtnd.apps.googleusercontent.com');
define('EMAIL_ADDRESS', '407963098975-vlhise79b7373ivpblogubvob6qgrtnd@developer.gserviceaccount.com');
define('KEY_FILE', dirname(__FILE__).'/75ed13ce0f9b77cd059af7669724b660a6431c67-privatekey.p12');
define('APP_NAME', 'Stats Analytics');
define('GAID', 'ga:'.$_PARAMS['google_analytics_ID']);

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_AnalyticsService.php';


// Initialise the Google Client object
$client = new Google_Client();
$client->setApplicationName(APP_NAME);

$client->setAssertionCredentials(
    new Google_AssertionCredentials(
        EMAIL_ADDRESS,
        array('https://www.googleapis.com/auth/analytics.readonly'),
        file_get_contents(KEY_FILE)
    )
);

// Get this from the Google Console, API Access page
$client->setClientId(CLIENT_ID);
$client->setAccessType('offline_access');
$analytics = new Google_AnalyticsService($client);
