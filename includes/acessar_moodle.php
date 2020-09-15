<?php
/**
 * User: Eduardo Kraus
 * Date: 14/09/2020
 * Time: 18:39
 */

add_filter ( 'woocommerce_account_menu_items', 'moocommerce_sso_account_menu_items' );
function moocommerce_sso_account_menu_items ( $menu_links )
{
    $settings = get_option ( 'moocommerce_sso_settings' );
    if ( !isset( $settings[ 'url_moodle' ][ 10 ] ) ) {
        return $menu_links;
    }

    $new = array( 'acessar_moodle' => 'Acessar o Moodle' );

    $menu_links = array_slice ( $menu_links, 0, 1, true )
        + $new
        + array_slice ( $menu_links, 1, null, true );

    return $menu_links;
}

add_filter ( 'woocommerce_get_endpoint_url', 'moocommerce_sso_get_endpoint_ur', 10, 4 );
function moocommerce_sso_get_endpoint_ur ( $url, $endpoint, $value, $permalink )
{
    if ( $endpoint === 'acessar_moodle' ) {

        $user     = wp_get_current_user ();
        $settings = get_option ( 'moocommerce_sso_settings' );

        $payload = array(
            'email' => $user->data->user_email,
            'name'  => $user->data->display_name
        );
        $token   = pass_encode ( $payload, $settings[ 'api_key' ] );
        $url     = "{$settings ['url_moodle']}/local/kopere_moocommerce/sso.php?pass={$token}";
    }
    return $url;

}