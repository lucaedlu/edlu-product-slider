<?php
/**
 * Plugin Name: Product Slider
 * Description: Widget per mostrare prodotti WooCommerce in griglia/slider.
 * Version: 0.14
 * Author: EDLU Digital Services
 * Text Domain: edlu-product-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'EDLU_PS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDLU_PS_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Registra il widget su Elementor.
 */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    require_once EDLU_PS_PLUGIN_PATH . 'class-edlu-elementor-product-slider.php';

    $widgets_manager->register( new \EDLU_Elementor_Product_Slider() );
} );

/**
 * Carica CSS e JS frontend + editor.
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
        '0.14'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://unpkg.com/swiper@9/swiper-bundle.min.js',
        array(),
        '9.4.1',
        true
    );

    // JS del plugin (dipende anche da elementor-frontend per l’editor)
    wp_enqueue_script(
        'edlu-product-slider-js',
        EDLU_PS_PLUGIN_URL . 'edlu-product-slider.js',
        array( 'jquery', 'swiper', 'elementor-frontend' ),
        '0.14',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'edlu_ps_enqueue_assets' );
add_action( 'elementor/editor/after_enqueue_scripts', 'edlu_ps_enqueue_assets' );

/**
 * Auto-update da GitHub (Plugin Update Checker).
 *
 * Qui facciamo una cosa furba:
 * 1. includiamo la libreria;
 * 2. cerchiamo una QUALSIASI classe "Factory" della libreria
 *    (Puc_v4_Factory oppure quella namespaced tipo
 *    YahnisElsts\PluginUpdateChecker\v5p6\PucFactory);
 * 3. la usiamo per buildare l'update checker.
 */
if ( file_exists( EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php' ) ) {

    require_once EDLU_PS_PLUGIN_PATH . 'plugin-update-checker/plugin-update-checker.php';

    $factoryClass = null;

    // Caso classico: versione v4 con classe globale.
    if ( class_exists( 'Puc_v4_Factory' ) ) {
        $factoryClass = 'Puc_v4_Factory';
    } else {
        // Altre versioni: cerchiamo una classe che contenga
        // "PluginUpdateChecker" e "PucFactory" nel nome.
        foreach ( get_declared_classes() as $class ) {
            if ( false !== strpos( $class, 'PluginUpdateChecker' ) &&
                 false !== strpos( $class, 'PucFactory' ) ) {

                $factoryClass = $class;
                break;
            }
        }
    }

    if ( $factoryClass && class_exists( $factoryClass ) && method_exists( $factoryClass, 'buildUpdateChecker' ) ) {
        // Costruiamo l'update checker usando la factory trovata.
        $edlu_ps_update_checker = $factoryClass::buildUpdateChecker(
            'https://github.com/lucaedlu/edlu-product-slider',
            __FILE__,
            'edlu-product-slider'
        );

        // Se l’oggetto supporta setBranch, usiamo "main".
        if ( is_object( $edlu_ps_update_checker ) && method_exists( $edlu_ps_update_checker, 'setBranch' ) ) {
            $edlu_ps_update_checker->setBranch( 'main' );
        }
    }
}
