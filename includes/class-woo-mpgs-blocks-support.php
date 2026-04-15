<?php
/**
 * WooCommerce Blocks support for MPGS.
 *
 * @package WooMPGS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WOO_MPGS_Blocks_Support extends AbstractPaymentMethodType {

    /**
     * Payment method name.
     *
     * @var string
     */
    protected $name = 'woo_mpgs';

    /**
     * Gateway settings.
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Load the gateway settings.
     */
    public function initialize() {
        $this->settings = get_option( 'woocommerce_' . $this->name . '_settings', array() );
    }

    /**
     * Check if the gateway is active.
     *
     * @return bool
     */
    public function is_active() {
        return 'yes' === $this->get_setting( 'enabled', 'yes' );
    }

    /**
     * Register the checkout blocks integration script.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $script_handle = 'woo-mpgs-blocks-integration';

        if ( ! wp_script_is( $script_handle, 'registered' ) ) {
            wp_register_script(
                $script_handle,
                plugins_url( 'assets/js/woo-mpgs-blocks.js', WOO_MPGS_FILE ),
                array(
                    'wc-blocks-registry',
                    'wc-settings',
                    'wp-element',
                    'wp-html-entities',
                ),
                WOO_MPGS_VERSION,
                true
            );
        }

        return array( $script_handle );
    }

    /**
     * Get data exposed to the checkout blocks frontend.
     *
     * @return array
     */
    public function get_payment_method_data() {
        $title = $this->get_setting( 'title', __( 'Credit Card', 'woo-mpgs' ) );
        $icon_url = $this->get_setting( 'mpgs_icon', '' );

        if ( empty( $icon_url ) ) {
            $icon_url = plugins_url( 'assets/images/mastercard.png', WOO_MPGS_FILE );
        }

        return array(
            'title'       => $title,
            'description' => $this->get_setting( 'description', __( 'Pay securely by Credit/Debit Card.', 'woo-mpgs' ) ),
            'icon_url'    => $icon_url,
            'supports'    => array( 'products' ),
        );
    }

    /**
     * Read a gateway setting value.
     *
     * @param string $key Setting key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    protected function get_setting( $key, $default = '' ) {
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
    }
}
