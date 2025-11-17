<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

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

    /**
     * Controlli Elementor
     */
    protected function register_controls() {

        /*
         * TAB CONTENUTO → Query prodotti
         */
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
                'label'   => 'Numero massimo prodotti (totale)',
                'type'    => Controls_Manager::NUMBER,
                'default' => 24,
                'min'     => 1,
                'max'     => 200,
            ]
        );

        $this->end_controls_section();

        /*
         * TAB CONTENUTO → Layout & Slider
         */
        $this->start_controls_section(
            'section_layout',
            [
                'label' => 'Layout & Slider',
            ]
        );

        $this->add_control(
            'enable_slider',
            [
                'label'        => 'Abilita slider',
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Sì',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => 'Colonne (desktop)',
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 6,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label'   => 'Righe per pagina',
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 6,
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label'     => 'Posizione frecce slider',
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bottom_center',
                'options'   => [
                    'bottom_center' => 'In basso centrato',
                    'top_center'    => 'In alto centrato',
                    'center_sides'  => 'Centro ai lati',
                ],
                'condition' => [
                    'enable_slider' => 'yes',
                ],
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

        /*
         * TAB STILE → Card prodotto
         */
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => 'Card prodotto',
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .edlu-product-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .edlu-product-item',
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => 'Sfondo card',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => 'Padding card',
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*
         * TAB STILE → Titolo prodotto
         */
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => 'Titolo prodotto',
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .edlu-product-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => 'Colore titolo',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-title, {{WRAPPER}} .edlu-product-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label'     => 'Allineamento titolo',
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => 'Sinistra',
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Centro',
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => 'Destra',
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-title'    => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .edlu-product-title a'  => 'text-align: {{VALUE}}; display: inline-block;',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label'      => 'Spazio sotto il titolo',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*
         * TAB STILE → Prezzo
         */
        $this->start_controls_section(
            'section_style_price',
            [
                'label' => 'Prezzo',
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .edlu-product-price',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => 'Colore prezzo',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_align',
            [
                'label'     => 'Allineamento prezzo',
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => 'Sinistra',
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Centro',
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => 'Destra',
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-price' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_margin_bottom',
            [
                'label'      => 'Spazio sotto il prezzo',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*
         * TAB STILE → Pulsante
         */
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => 'Pulsante',
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .edlu-product-add-to-cart .button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => 'Colore testo',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-add-to-cart .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => 'Colore sfondo',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-add-to-cart .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .edlu-product-add-to-cart .button',
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label'     => 'Allineamento pulsante',
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => 'Sinistra',
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Centro',
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => 'Destra',
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-add-to-cart' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin_top',
            [
                'label'      => 'Spazio sopra il pulsante',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-add-to-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Recupera le categorie prodotto WooCommerce per il select.
     */
    private function get_product_categories() {
        if ( ! taxonomy_exists( 'product_cat' ) ) {
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

    /**
     * Render frontend.
     */
    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p>WooCommerce non è attivo.</p>';
            return;
        }

        $settings = $this->get_settings_for_display();

        $posts_per_page = ! empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 24;
        $columns        = ! empty( $settings['columns'] ) ? max( 1, intval( $settings['columns'] ) ) : 4;
        $rows           = ! empty( $settings['rows'] ) ? max( 1, intval( $settings['rows'] ) ) : 2;
        $per_page       = $columns * $rows;
        $enable_slider  = ( isset( $settings['enable_slider'] ) && 'yes' === $settings['enable_slider'] );
        $nav_position   = ! empty( $settings['nav_position'] ) ? $settings['nav_position'] : 'bottom_center';

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $posts_per_page,
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

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            echo '<p>Nessun prodotto trovato.</p>';
            return;
        }

        $total_products = $query->found_posts;
        $total_in_loop  = min( $total_products, $posts_per_page );
        $total_pages    = ( $per_page > 0 ) ? ceil( $total_in_loop / $per_page ) : 1;

        $wrapper_attrs = [
            'class'                   => 'edlu-product-slider-wrapper',
            'data-enable-slider'      => $enable_slider ? 'yes' : 'no',
            'data-products-per-page'  => $per_page,
            'data-total-pages'        => $total_pages,
        ];

        $attr_html = '';
        foreach ( $wrapper_attrs as $key => $value ) {
            $attr_html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }

        echo '<div' . $attr_html . '>';
        echo '<div class="edlu-product-slider-inner">';

        $count      = 0;
        $page_index = 0;

        echo '<div class="edlu-product-grid edlu-product-page is-active" data-page="0" style="grid-template-columns: repeat(' . esc_attr( $columns ) . ', 1fr);">';

        while ( $query->have_posts() ) {
            $query->the_post();

            global $product;
            if ( ! $product ) {
                continue;
            }

            if ( $count > 0 && 0 === $count % $per_page ) {
                echo '</div>'; // chiudo pagina precedente
                $page_index++;
                echo '<div class="edlu-product-grid edlu-product-page" data-page="' . esc_attr( $page_index ) . '" style="grid-template-columns: repeat(' . esc_attr( $columns ) . ', 1fr);">';
            }

            $product_id    = $product->get_id();
            $product_title = get_the_title();
            $product_link  = get_permalink();
            $product_price = $product->get_price_html();
            $product_thumb = get_the_post_thumbnail( $product_id, 'medium' );

            echo '<div class="edlu-product-item">';

            if ( $product_thumb ) {
                echo '<a href="' . esc_url( $product_link ) . '" class="edlu-product-thumb">';
                echo $product_thumb;
                echo '</a>';
            }

            echo '<h3 class="edlu-product-title">';
            echo '<a href="' . esc_url( $product_link ) . '">' . esc_html( $product_title ) . '</a>';
            echo '</h3>';

            if ( 'yes' === $settings['show_price'] && $product_price ) {
                echo '<div class="edlu-product-price">' . wp_kses_post( $product_price ) . '</div>';
            }

            if ( 'yes' === $settings['show_add_to_cart'] ) {
                echo '<div class="edlu-product-add-to-cart">';
                woocommerce_template_loop_add_to_cart();
                echo '</div>';
            }

            echo '</div>'; // .edlu-product-item

            $count++;
        }

        wp_reset_postdata();

        echo '</div>'; // ultima pagina
        echo '</div>'; // .edlu-product-slider-inner

        if ( $enable_slider && $total_pages > 1 ) {
            $nav_class = 'nav-pos-' . esc_attr( $nav_position );
            echo '<div class="edlu-product-slider-nav ' . $nav_class . '">';
            echo '<button type="button" class="edlu-product-slider-arrow edlu-prev" aria-label="Precedente">&#10094;</button>';
            echo '<button type="button" class="edlu-product-slider-arrow edlu-next" aria-label="Successivo">&#10095;</button>';
            echo '</div>';
        }

        echo '</div>'; // .edlu-product-slider-wrapper
    }
}
