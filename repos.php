<?php

// Prevent direct access.
if ( ! defined( 'GH_WEBHOOK_DEPLOY' ) ) {
    return;
}

/**
 * Add details for as many repos as you want. They will automatically sync
 * when you push to the specified branch in GitHub.
 */
return [
    'my-name-or-organization/my-theme' => [ // Full repo name.
        'branch' => 'staging', // The branch to sync. Required.
        'dir'    => '/srv/www/public_html/wp-content/themes/my-theme', // The path on the web server. Required.
    ],
];