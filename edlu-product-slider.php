<?php
/**
 * Plugin Name: EDLU - Product Slider Elementor
 * Description: Widget Elementor per mostrare prodotti WooCommerce in griglia/slider.
 * Version: 0.13.0
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
 * Includiamo la classe SOLO quando Elementor è pronto,
 * così Elementor\Widget_Base esiste sicuramente.
 */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    require_once EDLU_PS_PLUGIN_PATH . 'class-edlu-elementor-product-slider.php';

    $widgets_manager->register( new \EDLU_Elementor_Product_Slider() );
} );

/**
 * Carica CSS e JS frontend + editor.
 * Includiamo Swiper.js da CDN e il JS del plugin.
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
        '0.13.0'
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
    // Dipende anche da "elementor-frontend" così abbiamo elementorFrontend in editor
    wp_enqueue_script(
        'edlu-product-slider-js',
        EDLU_PS_PLUGIN_URL . 'edlu-product-slider.js',
        array( 'jquery', 'swiper', 'elementor-frontend' ),
        '0.13.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'edlu_ps_enqueue_assets' );
add_action( 'elementor/editor/after_enqueue_scripts', 'edlu_ps_enqueue_assets' );

/**
 * Auto-update da GitHub (Plugin Update Checker) – in modalità "safe".
 * Se la classe non esiste, non facciamo nulla (nessun errore critico).
 */
if ( file_exists( EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php' ) ) {

    require_once EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php';

    if ( class_exists( 'Puc_v4_Factory' ) ) {
        $edlu_ps_update_checker = Puc_v4_Factory::buildUpdateChecker(
            'https://github.com/lucaedlu/edlu-product-slider',
            __FILE__,
            'edlu-product-slider'
        );

        $edlu_ps_update_checker->setBranch( 'main' );
    }
}

/**
 * Aggiunge il link "Controlla aggiornamenti" nella riga del plugin.
 * Cliccandolo porta a Bacheca → Aggiornamenti e forza il check.
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {

    $url   = admin_url( 'update-core.php?force-check=1' );
    $label = __( 'Controlla aggiornamenti', 'edlu-product-slider' );

    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';

    return $links;
} );
