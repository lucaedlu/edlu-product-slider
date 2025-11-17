<?php
/**
 * Plugin Name: EDLU - Product Slider Elementor
 * Description: Widget Elementor per mostrare prodotti WooCommerce in griglia/slider.
 * Version: 0.1
 * Author: EDLU
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AUTO-UPDATE da GitHub con Plugin Update Checker
 */
require plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';

// Usa la factory namespaced della v5
if ( class_exists( '\YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
    $edluUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/lucaedlu/edlu-product-slider/', // URL repo GitHub
        __FILE__,                                           // questo file
        'edlu-product-slider'                               // slug del plugin
    );

    $edluUpdateChecker->setBranch('main'); // branch principale del repo
}

/**
 * Registra il widget con Elementor.
 * Qui carichiamo la classe SOLO quando Elementor ha caricato i suoi widget.
 */
function edlu_ps_register_widget( $widgets_manager ) {

    // Se Elementor non è caricato, non fare nulla
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Carica la classe del widget (stesso livello del file del plugin)
    require_once plugin_dir_path( __FILE__ ) . 'class-edlu-elementor-product-slider.php';

    // Registra il widget
    $widgets_manager->register( new \EDLU_Elementor_Product_Slider() );
}
add_action( 'elementor/widgets/register', 'edlu_ps_register_widget' );

/**
 * Carica il CSS frontend del widget.
 */
function edlu_ps_enqueue_styles() {
    wp_enqueue_style(
        'edlu-product-slider-css',
        plugin_dir_url( __FILE__ ) . 'edlu-product-slider.css',
        array(),
        '0.1'
    );
}
add_action( 'wp_enqueue_scripts', 'edlu_ps_enqueue_styles' );
