<?php
/**
* The Shop Product Badges section options.
*
* @package shopkeeper
*/

add_action( 'customize_register', 'shopkeeper_customizer_shop_product_badges_controls' );
/**
 * Adds controls for shop product badges settings section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function shopkeeper_customizer_shop_product_badges_controls( $wp_customize ) {

    // Out of Stock Badge Text.
    $wp_customize->add_setting(
		'out_of_stock_label',
		array(
			'type'       => 'theme_mod',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
			'default'    => esc_html__( 'Out of stock', 'shopkeeper' ),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'out_of_stock_label',
			array(
				'type'        => 'text',
				'label'       => esc_attr__( '\'Out of Stock\' Badge Text', 'shopkeeper' ),
				'section'     => 'product_badges',
				'priority'    => 10,
			)
		)
	);

    // Sale Badge Text.
    $wp_customize->add_setting(
		'sale_label',
		array(
			'type'       => 'theme_mod',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
			'default'    => esc_html__( 'Sale!', 'shopkeeper' ),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'sale_label',
			array(
				'type'        => 'text',
				'label'       => esc_attr__( '\'Sale\' Badge Text', 'shopkeeper' ),
				'section'     => 'product_badges',
				'priority'    => 10,
			)
		)
	);

    // Sale Badge Color.
    $wp_customize->add_setting(
        'sale_badge_color',
        array(
            'type'       => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'    => '#93af76',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'sale_badge_color',
            array(
                'label'    => esc_html__( '\'Sale\' Badge Color', 'shopkeeper' ),
                'section'  => 'product_badges',
                'priority' => 10,
            )
        )
    );

    // Sale Badge Percentage.
    $wp_customize->add_setting(
		'sale_badge_percentage',
		array(
			'type'                 => 'theme_mod',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'shopkeeper_sanitize_checkbox',
			'default'              => false,
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'sale_badge_percentage',
			array(
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Sale Badge Percentage', 'shopkeeper' ),
				'section'  => 'product_badges',
				'priority' => 10,
			)
		)
	);
}
