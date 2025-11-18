<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

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
     * CONTROLLI ELEMENTOR
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

        // Colonne responsive (desktop / tablet / mobile)
        $this->add_responsive_control(
            'columns',
            [
                'label'   => 'Colonne',
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 6,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        // Righe responsive (desktop / tablet / mobile)
        $this->add_responsive_control(
            'rows',
            [
                'label'          => 'Righe per pagina',
                'type'           => Controls_Manager::NUMBER,
                'default'        => 2,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'min'            => 1,
                'max'            => 6,
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
                    '{{WRAPPER}} .edlu-product-title'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .edlu-product-title a' => 'text-align: {{VALUE}}; display: inline-block;',
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

        /*
         * TAB STILE → Frecce slider
         */
        $this->start_controls_section(
            'section_style_arrows',
            [
                'label'     => 'Frecce slider',
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label'   => 'Icona freccia sinistra',
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'eicon-chevron-left',
                    'library' => 'elementor',
                ],
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label'   => 'Icona freccia destra',
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'eicon-chevron-right',
                    'library' => 'elementor',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label'     => 'Colore icona',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-slider-arrow'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .edlu-product-slider-arrow i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .edlu-product-slider-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label'      => 'Dimensione icona',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 80,
                    ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-arrow-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .edlu-arrow-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label'     => 'Sfondo frecce',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edlu-product-slider-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label'      => 'Raggio bordo (tondo)',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-slider-arrow' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label'      => 'Padding interno freccia',
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-slider-arrow' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Toggle frecce su tablet
        $this->add_control(
            'show_arrows_tablet',
            [
                'label'        => 'Mostra frecce su Tablet',
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Sì',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // Toggle frecce su mobile
        $this->add_control(
            'show_arrows_mobile',
            [
                'label'        => 'Mostra frecce su Mobile',
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Sì',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // Regolazione frecce "Centro ai lati"
        $this->add_responsive_control(
            'arrows_vertical_pos',
            [
                'label'      => 'Altezza frecce (solo “Centro ai lati”)',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-slider-nav.nav-pos-center_sides' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'nav_position' => 'center_sides',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_offset_x',
            [
                'label'      => 'Distanza freccia sinistra verso l’esterno',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-slider-nav.nav-pos-center_sides .edlu-prev' =>
                        'transform: translateX(-{{SIZE}}{{UNIT}}) translateY(-50%);',
                ],
                'condition'  => [
                    'nav_position' => 'center_sides',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_offset_x',
            [
                'label'      => 'Distanza freccia destra verso l’esterno',
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .edlu-product-slider-nav.nav-pos-center_sides .edlu-next' =>
                        'transform: translateX({{SIZE}}{{UNIT}}) translateY(-50%);',
                ],
                'condition'  => [
                    'nav_position' => 'center_sides',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Recupera categorie prodotto WooCommerce
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
     * Render frontend
     */
    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p>WooCommerce non è attivo.</p>';
            return;
        }

        $settings = $this->get_settings_for_display();

        $posts_per_page = ! empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 24;

        // Colonne (desktop / tablet / mobile)
        $columns_desktop = isset( $settings['columns'] ) ? max( 1, intval( $settings['columns'] ) ) : 4;
        $columns_tablet  = isset( $settings['columns_tablet'] ) ? max( 1, intval( $settings['columns_tablet'] ) ) : $columns_desktop;
        $columns_mobile  = isset( $settings['columns_mobile'] ) ? max( 1, intval( $settings['columns_mobile'] ) ) : 1;

        // Righe (desktop / tablet / mobile)
        $rows_desktop = isset( $settings['rows'] ) ? max( 1, intval( $settings['rows'] ) ) : 2;
        $rows_tablet  = isset( $settings['rows_tablet'] ) ? max( 1, intval( $settings['rows_tablet'] ) ) : $rows_desktop;
        $rows_mobile  = isset( $settings['rows_mobile'] ) ? max( 1, intval( $settings['rows_mobile'] ) ) : 1;

        $enable_slider = ( isset( $settings['enable_slider'] ) && 'yes' === $settings['enable_slider'] );
        $nav_position  = ! empty( $settings['nav_position'] ) ? $settings['nav_position'] : 'bottom_center';

        $show_arrows_tablet = isset( $settings['show_arrows_tablet'] ) ? $settings['show_arrows_tablet'] : 'yes';
        $show_arrows_mobile = isset( $settings['show_arrows_mobile'] ) ? $settings['show_arrows_mobile'] : 'yes';

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

        // Numero di prodotti visibili per "pagina" desktop (per le frecce)
        $group_desktop = max( 1, $columns_desktop * $rows_desktop );
        $total_pages   = ( $enable_slider ) ? max( 1, ceil( $total_in_loop / $group_desktop ) ) : 1;

        /*
         * WRAPPER ESTERNO
         */
        $wrapper_classes = [ 'edlu-product-slider-wrapper' ];

        if ( 'yes' !== $show_arrows_tablet ) {
            $wrapper_classes[] = 'edlu-hide-arrows-tablet';
        }
        if ( 'yes' !== $show_arrows_mobile ) {
            $wrapper_classes[] = 'edlu-hide-arrows-mobile';
        }

        $wrapper_attrs = [
            'class'              => implode( ' ', $wrapper_classes ),
            'data-enable-slider' => $enable_slider ? 'yes' : 'no',
            'data-cols-desktop'  => $columns_desktop,
            'data-cols-tablet'   => $columns_tablet,
            'data-cols-mobile'   => $columns_mobile,
            'data-rows-desktop'  => $rows_desktop,
            'data-rows-tablet'   => $rows_tablet,
            'data-rows-mobile'   => $rows_mobile,
        ];

        $attr_html = '';
        foreach ( $wrapper_attrs as $key => $value ) {
            $attr_html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }

        echo '<div' . $attr_html . '>';

        /*
         * CASO 1: SLIDER DISABILITATO → semplice griglia unica
         */
        if ( ! $enable_slider ) {
            echo '<div class="edlu-product-grid">';

            while ( $query->have_posts() ) {
                $query->the_post();

                global $product;
                if ( ! $product ) {
                    continue;
                }

                $this->render_product_card( $product, $settings );
            }

            wp_reset_postdata();

            echo '</div>'; // .edlu-product-grid
            echo '</div>'; // .wrapper
            return;
        }

        /*
         * CASO 2: SLIDER ATTIVO → markup SWIPER (1 slide = 1 prodotto)
         * Swiper gestisce da solo righe/colonne con "grid" e "breakpoints".
         */
        echo '<div class="edlu-product-slider-inner">';
        echo '<div class="swiper edlu-product-swiper">';
        echo '<div class="swiper-wrapper">';

        while ( $query->have_posts() ) {
            $query->the_post();

            global $product;
            if ( ! $product ) {
                continue;
            }

            echo '<div class="swiper-slide">';
            $this->render_product_card( $product, $settings );
            echo '</div>';
        }

        wp_reset_postdata();

        echo '</div>'; // .swiper-wrapper
        echo '</div>'; // .swiper
        echo '</div>'; // .edlu-product-slider-inner

        /*
         * NAVIGAZIONE FRECCE (solo se c'è più di una "pagina" desktop)
         */
        if ( $total_pages > 1 ) {
            $nav_class = 'nav-pos-' . esc_attr( $nav_position );

            $prev_icon = ! empty( $settings['prev_icon'] ) ? $settings['prev_icon'] : null;
            $next_icon = ! empty( $settings['next_icon'] ) ? $settings['next_icon'] : null;

            echo '<div class="edlu-product-slider-nav ' . $nav_class . '">';

            // Freccia sinistra
            echo '<button type="button" class="edlu-product-slider-arrow edlu-prev" aria-label="Precedente">';
            echo '<span class="edlu-arrow-icon">';
            if ( $prev_icon && ! empty( $prev_icon['value'] ) ) {
                Icons_Manager::render_icon( $prev_icon, [ 'aria-hidden' => 'true' ] );
            } else {
                echo '&#10094;';
            }
            echo '</span>';
            echo '</button>';

            // Freccia destra
            echo '<button type="button" class="edlu-product-slider-arrow edlu-next" aria-label="Successivo">';
            echo '<span class="edlu-arrow-icon">';
            if ( $next_icon && ! empty( $next_icon['value'] ) ) {
                Icons_Manager::render_icon( $next_icon, [ 'aria-hidden' => 'true' ] );
            } else {
                echo '&#10095;';
            }
            echo '</span>';
            echo '</button>';

            echo '</div>';
        }

        echo '</div>'; // .wrapper
    }

    /**
     * Render di una singola card prodotto
     */
    private function render_product_card( $product, $settings ) {
        $product_id    = $product->get_id();
        $product_title = get_the_title( $product_id );
        $product_link  = get_permalink( $product_id );
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
    }
}
