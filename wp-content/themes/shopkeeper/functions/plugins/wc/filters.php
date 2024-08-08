<?php
/**
 * WC filters
 *
 * @package shopkeeper
 */

/**
 * Notification classes
 */
function shopkeeper_notification_class( $classes ) {

    global $post;

    if( Shopkeeper_Opt::getOption( 'notification_mode', '1' ) == '1' ) {
         if( !is_account_page() ) {
            $classes[] = 'gbt_custom_notif';
        } else {
            $classes[] = 'gbt_classic_notif';
        }
    } else {
        $classes[] = 'gbt_classic_notif';
    }

    if( is_product() ) {
        $classes[] = 'product-layout-' . getbowtied_product_layout($post->ID);
    }

    return $classes;
}
add_filter( 'body_class','shopkeeper_notification_class' );

/**
 * WooCommerce remove review tab
 */
function shopkeeper_remove_reviews_tab($tabs) {

	if ( !Shopkeeper_Opt::getOption( 'review_tab', true ) ) {
		unset($tabs['reviews']);
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'shopkeeper_remove_reviews_tab', 98);

/**
 * Change breadcrumbs separator
 */
function shopkeeper_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' <span class="breadcrump_sep">/</span> ';

	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'shopkeeper_change_breadcrumb_delimiter' );

/**
 * Update local storage with cart counter each time
 */
function shopkeeper_shopping_bag_items_number( $fragments ) {
	global $woocommerce;

	ob_start(); ?>

    <span class="shopping_bag_items_number"><?php echo is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''; ?></span>
	<?php
	$fragments['.shopping_bag_items_number'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'shopkeeper_shopping_bag_items_number' );

/**
 * Limit number of cross-sells
 */
function shopkeeper_cart_cross_sells($total) {
	$total = '2';

	return $total;
}
add_filter( 'woocommerce_cross_sells_total', 'shopkeeper_cart_cross_sells' );

/**
 * Custom Sale label
 */
function woocommerce_custom_sale_tag_sale_flash($original, $post, $product) {

    $percentage = '';
    if( Shopkeeper_Opt::getOption( 'sale_badge_percentage', false ) ) {
        if( $product->is_type('variable') ) {
            $percentages = array();
            // Get all variation prices
            $prices = $product->get_variation_prices();
            // Loop through variation prices
            foreach( $prices['price'] as $key => $price ){
                // Only on sale variations
                if( $prices['regular_price'][$key] !== $price ){
                    // Calculate and set in the array the percentage for each variation on sale
                    $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
                }
            }
            // We keep the highest value
            $percentage = max($percentages) . '%';
        } else {
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_sale_price();

            $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
        }
    }

	if ( !empty( Shopkeeper_Opt::getOption( 'sale_label', 'Sale!' ) ) ):
		echo '<span class="onsale">';
        printf( esc_html__( '%s', 'woocommerce' ), Shopkeeper_Opt::getOption( 'sale_label', 'Sale!' ));
        echo Shopkeeper_Opt::getOption( 'sale_badge_percentage', false ) ? ' ' . $percentage : '';
        echo '</span>';
	else:
        echo Shopkeeper_Opt::getOption( 'sale_badge_percentage', false ) ? '<span class="onsale">' . $percentage . '</span>' : '';
	endif;

    return;
}
if( !SHOPKEEPER_WOOCOMMERCE_SALE_FLASH_PRO_IS_ACTIVE ) {
	add_filter( 'woocommerce_sale_flash', 'woocommerce_custom_sale_tag_sale_flash', 10, 3 );
}

/**
 * Show Woocommerce Cart Widget Everywhere
 */
function shopkeeper_woocommerce_widget_cart_everywhere() {
    return false;
};
add_filter( 'woocommerce_widget_cart_is_hidden', 'shopkeeper_woocommerce_widget_cart_everywhere', 10, 1 );

/**
 * Deactivate out of stock variations in select
 */
function shopkeeper_variation_is_active( $active, $variation ) {

	if( Shopkeeper_Opt::getOption( 'disabled_outofstock_variations', true ) ) {
		if( ! $variation->is_in_stock() ) {
			return false;
		}
	}

	return $active;
}
add_filter( 'woocommerce_variation_is_active', 'shopkeeper_variation_is_active', 10, 2 );

/**
 * Hide empty categories
 */
add_filter( 'woocommerce_product_subcategories_hide_empty', 'sk_hide_empty_categories', 10, 1 );
function sk_hide_empty_categories( $hide_empty ) {
    if( !Shopkeeper_Opt::getOption( 'hide_empty_categories', true ) ) {
        return false;
    }

    return $hide_empty;
}
