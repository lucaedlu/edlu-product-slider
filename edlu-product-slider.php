<?php
/**
 * Plugin Name: EDLU - Product Slider Elementor
 * Description: Widget Elementor per mostrare prodotti WooCommerce in griglia/slider.
 * Version: 0.11.2
 * Author: EDLU
 * Text Domain: edlu-product-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'EDLU_PS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDLU_PS_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Registra il widget su Elementor.
 * IMPORTANTE: includiamo la classe SOLO quando Elementor è pronto,
 * così Elementor\Widget_Base esiste sicuramente.
 */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    // Carichiamo il file della classe del widget
    require_once EDLU_PS_PLUGIN_PATH . 'class-edlu-elementor-product-slider.php';

    // Registriamo il widget
    $widgets_manager->register( new \EDLU_Elementor_Product_Slider() );
} );

/**
 * Carica CSS e JS frontend + editor.
 * Includiamo Swiper.js da CDN e i file del plugin.
 */
function edlu_ps_enqueue_assets() {

    // Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://unpkg.com/swiper@9/swiper-bundle.min.css',
        array(),
        '9.4.1'
    );

    // CSS del plugin
    wp_enqueue_style(
        'edlu-product-slider-css',
        EDLU_PS_PLUGIN_URL . 'edlu-product-slider.css',
        array( 'swiper' ),
        '0.11.2'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://unpkg.com/swiper@9/swiper-bundle.min.js',
        array(),
        '9.4.1',
        true
    );

    // JS del plugin
    wp_enqueue_script(
        'edlu-product-slider-js',
        EDLU_PS_PLUGIN_URL . 'edlu-product-slider.js',
        array( 'swiper', 'jquery' ),
        '0.11.2',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'edlu_ps_enqueue_assets' );
add_action( 'elementor/editor/after_enqueue_scripts', 'edlu_ps_enqueue_assets' );

/**
 * Auto-update da GitHub (Plugin Update Checker).
 *
 * Al momento lo rendiamo "safe":
 * - se il file non esiste → non facciamo nulla
 * - se la classe Puc_v4_Factory NON esiste → NON chiamiamo buildUpdateChecker
 */
if ( file_exists( EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php' ) ) {

    require_once EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php';

    if ( class_exists( 'Puc_v4_Factory' ) ) {
        $edlu_ps_update_checker = Puc_v4_Factory::buildUpdateChecker(
            'https://github.com/lucaedlu/edlu-product-slider',
            __FILE__,
            'edlu-product-slider'
        );

        // Branch principale
        $edlu_ps_update_checker->setBranch( 'main' );
    }
    // Se class_exists NON è vera, semplicemente NON attiviamo l'auto-update
}
