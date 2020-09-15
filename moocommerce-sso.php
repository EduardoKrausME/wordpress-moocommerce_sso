<?php
/*
Plugin Name: MooCommerce SSO
Description: Integra SSO do Wordpress com usuários do MooCommerce
Version: 1.0.1
Author: Eduardo Kraus
License: Private
 */

function moocommerce_sso_activate ()
{
    if ( !function_exists ( 'register_post_status' ) ) {
        deactivate_plugins ( basename ( dirname ( __FILE__ ) ) . '/' . basename ( __FILE__ ) );
        echo 'Este plugin requer WordPress 3.0s ou mais recente. Atualize sua instalação do WordPress para ativar este plugin.';
        exit;
    }
}

register_activation_hook ( __FILE__, 'moocommerce_sso_activate' );

add_filter ( 'plugin_action_links', 'moocommerce_sso_add_setup_link', 10, 2 );
function moocommerce_sso_add_setup_link ( $links, $file )
{
    static $moocommerce_sso_plugin = null;

    if ( is_null ( $moocommerce_sso_plugin ) ) {
        $moocommerce_sso_plugin = plugin_basename ( __FILE__ );
    }

    if ( $file == $moocommerce_sso_plugin ) {
        $links[] = '<a href="admin.php?page=moocommerce_sso_settings">Configurar o Plugin</a>';
    }

    return $links;
}

require_once dirname ( __FILE__ ) . '/includes/admin.php';
require_once dirname ( __FILE__ ) . '/includes/pass.php';
require_once dirname ( __FILE__ ) . '/includes/acessar_moodle.php';


