<?php
/**
 * User: Eduardo Kraus
 * Date: 14/09/2020
 * Time: 18:53
 */

function pass_encode ( $payload, $key, $expires = 300 )
{
    $alg = pass_algorithm ();
    if ( $alg == null ) {
        return 'ops';
    }

    $header = array(
        'typ' => 'JWT',
        'alg' => strtoupper ( $alg ),
        'by'  => 'wordpress-moocommerce-sso'
    );

    if ( $expires ) {
        $payload[ 'iat' ] = time ();
        $payload[ 'nbf' ] = time ();
        $payload[ 'exp' ] = time () + $expires;
    }

    $segments      = array();
    $segments[]    = pass_urlSafe ( json_encode ( $header ) );
    $segments[]    = pass_urlSafe ( json_encode ( $payload ) );
    $signing_input = implode ( '.', $segments );

    $signature  = pass_crypt ( $alg, $signing_input, $key );
    $segments[] = pass_urlSafe ( $signature );

    return implode ( '.', $segments );
}

function pass_algorithm ()
{
    $msg = $key = "teste";

    if ( function_exists ( "hash_hmac" ) ) {
        $data = hash_hmac ( "sha512", $msg, $key, true );
        if ( $data !== false ) {
            return 'HS512';
        }
        $data = hash_hmac ( "sha256", $msg, $key, true );
        if ( $data !== false ) {
            return 'HS256';
        }
        $data = hash_hmac ( "sha384", $msg, $key, true );
        if ( $data !== false ) {
            return 'HS384';
        }
    } elseif ( function_exists ( "sha1" ) ) {
        // Caso não possua Suporte a hash_hmac
        return 'HS1';
    } elseif ( function_exists ( "md5" ) ) {
        // Caso não possua Suporte a hash_hmac, nem sha1
        return 'HS5';
    }

    return null;
}

function pass_crypt ( $alg, $signing_input, $key )
{
    if ( $alg == "HS512" ) {
        return hash_hmac ( "sha512", $signing_input, $key, true );
    } elseif ( $alg == "HS256" ) {
        return hash_hmac ( "sha256", $signing_input, $key, true );
    } elseif ( $alg == "HS384" ) {
        return hash_hmac ( "sha384", $signing_input, $key, true );
    } elseif ( $alg == "HS1" ) {
        return sha1 ( sha1 ( "kopere_moocommerce{$signing_input}{$key}" ) );
    } elseif ( $alg == "HS5" ) {
        return md5 ( md5 ( "kopere_moocommerce{$signing_input}{$key}" ) );
    }

    return null;
}

function pass_urlSafe ( $input )
{
    return str_replace ( '=', '', strtr ( base64_encode ( $input ), '+/', '-_' ) );
}
