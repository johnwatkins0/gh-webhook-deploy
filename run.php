<?php

// Make sure the file isn't accessed directly.
if ( ! defined( 'GH_WEBHOOK_DEPLOY' ) ) {
    return '';
}

if ( ! file_exists( __DIR__ . '/repos.php' ) ) {
    return 'config file doesn\'t exist';
}

$repos  = include __DIR__ . '/repos.php';

/**
 * Verifies GitHub secret key against the one entered in secret.php.
 *
 * @return boolean
 */
function hash_matches() {
    if ( ! file_exists( __DIR__ . '/secret.php' ) ) {
        return false;
    }

    $secret = include __DIR__ . '/secret.php';

    if ( ! $secret ) {
        return false;
    }

    if ( ! isset( $_SERVER ) || ! isset( $_SERVER['HTTP_X_HUB_SIGNATURE'] ) ) {
        return false;
    }

    return hash_equals(
        'sha1=' . hash_hmac( 'sha1', strval( file_get_contents( 'php://input' ) ), $secret ),
        strval( $_SERVER['HTTP_X_HUB_SIGNATURE'] )
    );
}

if ( ! hash_matches() ) {
    return 'GH secret did not match';
}

$payload = file_get_contents( 'php://input' );
if ( strpos( $payload, 'payload=' ) === 0 ) {
    $payload = substr( urldecode( $payload ), 8 );
}

if ( is_string( $payload ) ) {
    $payload = json_decode( $payload, true );
}

if ( ! isset( $payload['ref'] ) ) {
    return 'ref not in post data';
}

if ( ! isset( $payload['repository'] ) ) {
    return 'repository not in post data';
}

if ( ! isset( $payload['repository']['full_name'] ) ) {
    return 'full_name not in post repository data';
}

foreach ( $repos as $repo => $data ) {
    if ( $repo !== $payload['repository']['full_name'] ) {
        continue;
    }

    if ( ! isset( $data['branch'] ) || 'refs/heads/' . $data['branch'] !== $payload['ref'] ) {
        continue;
    }

    if ( ! isset( $data['dir'] ) || ! file_exists( $data['dir'] ) ) {
        continue;

    }

    $command = 'cd ' . $data['dir'] . '&& git fetch && git checkout ' . $data['branch'] . ' && git pull';
    $output[] = $command;
    $output[] = shell_exec( $command );

    return $output;
}

$output = [ 'Pushed repo not in config.' ];