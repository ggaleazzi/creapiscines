<!DOCTYPE html>

<!--[if IE 9]>
<html class="ie ie9" <?php language_attributes(); ?>>
<![endif]-->

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php shopkeeper_preload_default_fonts( array('Radnika', 'NeueEinstellung') ); ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php if ( !function_exists( 'elementor_theme_do_location' ) || !elementor_theme_do_location( 'header' ) ) { ?>

        <?php do_action( 'wp_header_components' ); ?>

    	<?php if( Shopkeeper_Opt::getOption( 'smooth_transition_between_pages', false ) ) { ?>
    		<div id="header-loader">
    		    <div id="header-loader-under-bar"></div>
    		</div>
    	<?php }	?>

    	<div id="st-container" class="st-container">

            <div class="st-content">

                <?php $transparency = shopkeeper_get_transparency_options(); ?>
                <div id="page_wrapper" class="<?php echo esc_attr( $transparency['transparency_class'] ); ?> <?php echo esc_attr( $transparency['transparency_scheme'] ); ?>">

                    <?php do_action( 'before' ); ?>

                    <div class="top-headers-wrapper <?php echo Shopkeeper_Opt::getOption( 'sticky_header', true ) ? 'site-header-sticky' : ''; ?>">

                        <?php

    					if( shopkeeper_is_topbar_enabled() ) {
    						include( get_parent_theme_file_path('header-topbar.php') );
    					} else {
    					?>
    						<div class="top-clear"></div>
    					<?php
    					}

                        $header_layout = Shopkeeper_Opt::getOption( 'main_header_layout', '1' );
                        if ( $header_layout == "1" || $header_layout == "11" ) :
                            include( get_parent_theme_file_path('header-default.php') );
                        elseif ( $header_layout == "2" || $header_layout == "22" ) :
                            include( get_parent_theme_file_path('header-centered-2menus.php') );
                        elseif ( $header_layout == "3" ) :
                            include( get_parent_theme_file_path('header-centered-menu-under.php') );
                        endif;

    					?>

                    </div>
    <?php } ?>
