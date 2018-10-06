<?php

// No WP UI.
if ( defined( 'ABSPATH' ) ) {
    return;
}

define( 'GH_WEBHOOK_DEPLOY', true );

if ( file_exists( __DIR__ . '/run.php' ) ) {
    $lines = include __DIR__ . '/run.php';
    if ( ! is_array( $lines ) ) {
        $lines = [ $lines ];
    }
    echo json_encode( implode( '<br>', $lines ) );
}
