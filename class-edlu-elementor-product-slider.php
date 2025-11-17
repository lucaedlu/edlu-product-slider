<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class EDLU_Elementor_Product_Slider extends Widget_Base {

    public function get_name() {
        return 'edlu_product_slider';
    }

    public function get_title() {
        return 'Catalogo Prodotti (EDLU)';
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'prodotti', 'catalogo', 'slider', 'shop' ];
    }

    protected function register_controls() {

        // SEZIONE QUERY
        $this->start_controls_section(
            'section_query',
            [
                'label' => 'Query prodotti',
            ]
        );

        $this->add_control(
            'product_cats',
            [
                'label'       => 'Categorie prodotto',
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_categories(),
                'label_block' => true,
                'description' => 'Lascia vuoto per tutte le categorie.',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => 'Numero prodotti',
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'max'     => 48,
            ]
        );

        $this->end_controls_section();

        // SEZIONE LAYOUT
        $this->start_controls_section(
            'section_layout',
            [
                'label' => 'Layout',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'        => 'Mostra prezzo',
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Sì',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_add_to_cart',
            [
                'label'        => 'Mostra pulsante Aggiungi al carrello',
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Sì',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Recupera le categorie prodotto WooCommerce per il select.
     */
    private function get_product_categories() {
        if ( ! function_exists( 'wc_get_product_category_list' ) ) {
            return [];
        }

        $terms = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ] );

        $options = [];

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
        }

        return $options;
    }

    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p>WooCommerce non è attivo.</p>';
            return;
        }

        $settings = $this->get_settings_for_display();

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => ! empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 8,
            'post_status'    => 'publish',
        ];

        if ( ! empty( $settings['product_cats'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $settings['product_cats'],
                ]
            ];
        }

        $query = new WP_Query( $args );

        if ( ! $query->have_posts() ) {
            echo '<p>Nessun prodotto trovato.</p>';
            return;
        }

        echo '<div class="edlu-product-slider-wrapper">';
        echo '<div class="edlu-product-grid">';

        while ( $query->have_posts() ) {
            $query->the_post();

            global $product;

            if ( ! $product ) {
                continue;
            }

            $product_id    = $product->get_id();
            $product_title = get_the_title();
            $product_link  = get_permalink();
            $product_price = $product->get_price_html();
            $product_thumb = get_the_post_thumbnail( $product_id, 'medium' );

            echo '<div class="edlu-product-item">';

            echo '<a href="' . esc_url( $product_link ) . '" class="edlu-product-thumb">';
            echo $product_thumb;
            echo '</a>';

            echo '<h3 class="edlu-product-title">';
            echo '<a href="' . esc_url( $product_link ) . '">' . esc_html( $product_title ) . '</a>';
            echo '</h3>';

            if ( 'yes' === $settings['show_price'] && $product_price ) {
                echo '<div class="edlu-product-price">' . $product_price . '</div>';
            }

            if ( 'yes' === $settings['show_add_to_cart'] ) {
                echo '<div class="edlu-product-add-to-cart">';
                woocommerce_template_loop_add_to_cart();
                echo '</div>';
            }

            echo '</div>';
        }

        wp_reset_postdata();

        echo '</div>';
        echo '</div>';
    }
}
