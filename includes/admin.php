<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('admin_init', 'rentaweb_abandoned_cart_for_woocommerce_register_settings');
add_action('admin_menu', 'rentaweb_abandoned_cart_for_woocommerce_options_page');

function rentaweb_abandoned_cart_for_woocommerce_register_settings()
{
    register_setting('rentaweb_abandoned_cart_option_group', 'rentaweb_abandoned_cart_data');
    add_settings_section('rentaweb_abandoned_cart_settings_section', '', 'rentaweb_abandoned_cart_settings_section_cb', 'rentaweb_abandoned_cart_page');

    add_settings_field(
        'rentaweb_abandoned_cart_for_woocommerce_is_test',
        __('Is Test', 'rentaweb-abandoned-carts'),
        'rentaweb_abandoned_cart_for_woocommerce_is_test_cb',
        'rentaweb_abandoned_cart_page',
        'rentaweb_abandoned_cart_settings_section'
    );
    add_settings_field(
        'rentaweb_abandoned_cart_for_woocommerce_token',
        __('Id', 'rentaweb-abandoned-carts'),
        'rentaweb_abandoned_cart_for_woocommerce_id',
        'rentaweb_abandoned_cart_page',
        'rentaweb_abandoned_cart_settings_section'
    );
    add_settings_field(
        'rentaweb_abandoned_cart_for_woocommerce_m_account',
        __('Secret', 'rentaweb-abandoned-carts'),
        'rentaweb_abandoned_cart_for_woocommerce_secret',
        'rentaweb_abandoned_cart_page',
        'rentaweb_abandoned_cart_settings_section'
    );
}

function rentaweb_abandoned_cart_for_woocommerce_options_page()
{
    add_options_page(
        'Rentaweb Abandoned Carts',
        'Rentaweb Abandoned Carts',
        'manage_options',
        'rentaweb_abandoned_cart_for_woocommerce',
        'rentaweb_abandoned_cart_callbackcallback'
    );
}
function rentaweb_abandoned_cart_callbackcallback()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo '<h1>' . __('Rentaweb Abandoned Carts', 'rentaweb-abandoned-carts') . '</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('rentaweb_abandoned_cart_option_group');
    do_settings_sections('rentaweb_abandoned_cart_page');
    submit_button();
    echo '</form></div>';
}
function rentaweb_abandoned_cart_settings_section_cb()
{
}

function rentaweb_abandoned_cart_for_woocommerce_id()
{
    $options = get_option('rentaweb_abandoned_cart_data');
?>
    <label for="">
        <input name="rentaweb_abandoned_cart_data[id]" type='password' value="<?php echo $options['id'] ?>" />
    </label>
<?php
}

function rentaweb_abandoned_cart_for_woocommerce_secret()
{
    $options = get_option('rentaweb_abandoned_cart_data');
?>
    <label for="">
        <input name="rentaweb_abandoned_cart_data[secret]" type='password' value="<?php echo $options['secret'] ?>" />
    </label>
<?php
}

function rentaweb_abandoned_cart_for_woocommerce_is_test_cb()
{
    $options = get_option('rentaweb_abandoned_cart_data');
?>
    <label for="">
        <input type="checkbox" name="rentaweb_abandoned_cart_data[is_test]" value="true" <?php echo ($options['is_test'] == 'true') ? 'checked'  : ''; ?>>
    </label>
<?php
}
