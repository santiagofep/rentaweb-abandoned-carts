<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class RentawebAbandonedCart
{
    public $version = '0.0.1';
    static $instance = false;

    private function __construct()
    {
        add_action('plugins_loaded', array($this, 'textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'public_scripts'));
        add_action('wp_ajax_nopriv_rentaweb_update_cart', array($this, 'rentaweb_update_cart'));
        add_action('wp_ajax_rentaweb_update_cart', array($this, 'rentaweb_update_cart'));
        add_action('woocommerce_new_order', array($this, 'woocommerce_new_order'));
        add_action('woocommerce_order_status_processing', array($this, 'woocommerce_order_status_processing'));
        $this->includes();
    }

    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
    }

    public function textdomain()
    {
        load_plugin_textdomain('rentaweb-abandoned-carts', false, dirname(plugin_basename(__FILE__)) . '/../languages/');
    }

    public function includes()
    {
        require_once dirname(__FILE__) . '/admin.php';
    }

    public function public_scripts()
    {
        $js_ver  = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . '../dist/public.js'));
        wp_enqueue_script('rw_abandoned', plugins_url('../dist/public.js', __FILE__), array('jquery'), $js_ver, 'all');
        wp_localize_script(
            'rw_abandoned',
            'rw_abandoned_obj',
            array('ajax_url' => admin_url('admin-ajax.php'))
        );
    }

    public function rentaweb_update_cart()
    {
        $rentaweb_cart = WC()->session->get('rentaweb-cart');
        if (is_null($rentaweb_cart)) {
            $rentaweb_cart = uniqid();
            WC()->session->set('rentaweb-cart', $rentaweb_cart);
        }

        $options = get_option('rentaweb_abandoned_cart_data');
        $cart = WC()->cart;
        $items = $cart->get_cart_contents();
        $data = array(
            'secret' => $options['secret'],
            'items' => $items,
            'customer' => array(
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
            ),
            'cart' => $rentaweb_cart,
        );
        $json = json_encode($data);
        $args = array(

            'body'  => $json,
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
        );
        if ($options['is_test'] == true) {
            $url = 'https://moship.ngrok.io/marketing/api/checkouts/';
        } else {
            $url = 'https://mi.gruporentaweb.com/marketing/api/checkouts/';
        }

        $response = wp_remote_post($url . $options['id'] . '/', $args);

        print_r($cart->get_cart_hash());

        wp_die();
    }

    public function woocommerce_new_order($order_id)
    {

        $rentaweb_cart = WC()->session->get('rentaweb-cart');
        WC()->session->__unset('rentaweb-cart');
        $order = wc_get_order($order_id);
        $order->update_meta_data('_rentaweb_cart', $rentaweb_cart);
        $order->save();
    }

    public function woocommerce_order_status_processing($order_id)
    {
        $order = wc_get_order($order_id);
        $rentaweb_cart = $order->get_meta('_rentaweb_cart', true);
        $options = get_option('rentaweb_abandoned_cart_data');
        $data = array(
            'secret' => $options['secret'],
            'cart' => $rentaweb_cart,
            'order_id' => $order_id,
        );
        $json = json_encode($data);
        $args = array(

            'body'  => $json,
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
            'method' => 'PUT'
        );

        if ($options['is_test'] == true) {
            $url = 'https://moship.ngrok.io/marketing/api/checkouts/';
        } else {
            $url = 'https://mi.gruporentaweb.com/marketing/api/checkouts/';
        }

        $response = wp_remote_request($url . $options['id'] . '/', $args);
    }
}
