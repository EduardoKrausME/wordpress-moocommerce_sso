<?php
/**
 * User: Eduardo Kraus
 * Date: 14/09/2020
 * Time: 17:22
 */

add_action ( 'admin_menu', 'moocommerce_sso_admin_menu' );
function moocommerce_sso_admin_menu ()
{
    // Setup
    $page = add_menu_page ( 'MooCommerce SSO', 'MooCommerce SSO', 'manage_options', 'moocommerce_sso_settings', 'moocommerce_sso_admin_settings_menu', 'dashicons-admin-network' );
    add_action ( 'admin_init', 'moocommerce_sso_admin_settings' );
}

function moocommerce_sso_admin_settings ()
{
    register_setting ( 'moocommerce_sso_settings_group', 'moocommerce_sso_settings', 'moocommerce_sso_admin_settings_validate' );
}

function moocommerce_sso_admin_settings_validate ( $settings )
{
    $sanitzed_settings                  = array();
    $sanitzed_settings [ 'url_moodle' ] = trim ( $settings [ 'url_moodle' ] );
    $sanitzed_settings [ 'api_key' ]    = trim ( $settings [ 'api_key' ] );

    return $sanitzed_settings;
}

function moocommerce_sso_admin_settings_menu ()
{
    $settings = get_option ( 'moocommerce_sso_settings' );

    ?>
    <h2>MooCommerce SSO</h2>

    <form method="post" action="options.php">
        <?php
        settings_fields ( 'moocommerce_sso_settings_group' );
        ?>
        <table class="form-table">
            <tr class="sso-row sso-row-even">
                <td class="sso-col sso-col-label">
                    <label for="url_moodle">URL do seu Moodle</label>
                </td>
                <td class="sso-col sso-col-value">
                    <input size="48" type="text" id="url_moodle"
                           name="moocommerce_sso_settings[url_moodle]"
                           value="<?php echo ( isset ( $settings [ 'url_moodle' ] ) ? htmlspecialchars ( $settings [ 'url_moodle' ] ) : '' ); ?>"/>
                </td>
            </tr>
            <tr class="sso-row sso-row-odd">
                <td class="sso-col sso-col-label">
                    <label for="moocommerce_sso_api_key">API do SSO MooCommerce</label>
                </td>
                <td class="sso-col sso-col-value">
                    <input size="48" type="text" id="moocommerce_sso_api_key"
                           name="moocommerce_sso_settings[api_key]"
                           value="<?php echo ( isset ( $settings [ 'api_key' ] ) ? htmlspecialchars ( $settings [ 'api_key' ] ) : '' ); ?>"/>
                </td>
            </tr>
        </table>
        <p class="sso-submit">
            <input type="hidden" name="page" value="setup"/>
            <input type="submit" class="sso-save"
                   value="Salvar"/>
        </p>
    </form>
    <?php
}