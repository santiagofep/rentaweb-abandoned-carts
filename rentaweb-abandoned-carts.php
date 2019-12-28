<?php

/**
 * Plugin Name: Rentaweb Abandoned Carts
 * Plugin URI:  http://www.moship.io/
 * Description: Connect abandoned carts to Rentaweb App
 * Version:     1.0.1
 * Author:      Moship
 * Author URI:  http://www.moship.io/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rentaweb-abandoned-carts
 * Domain Path: /languages
 *  
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (
    in_array(
        'woocommerce/woocommerce.php',
        apply_filters(
            'active_plugins',
            get_option('active_plugins')
        )
    )
) {
    if (!class_exists('RentawebAbandonedCart')) {
        include_once dirname(__FILE__) . '/includes/class-rentaweb-abandoned-carts.php';
    }
    $RentawebAbandonedCartInsance = RentawebAbandonedCart::getInstance();
}
