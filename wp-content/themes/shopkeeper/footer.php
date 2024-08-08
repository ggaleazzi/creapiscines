<?php if ( !function_exists( 'elementor_theme_do_location' ) || !elementor_theme_do_location( 'footer' ) ) { ?>

			<?php global $page_id, $woocommerce; ?>

			<?php

			$page_footer_option = "on";

			if (get_post_meta( $page_id, 'footer_meta_box_check', true )) {
				$page_footer_option = get_post_meta( $page_id, 'footer_meta_box_check', true );
			}

			if (class_exists('WooCommerce')) {
				if (is_shop() && get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'footer_meta_box_check', true )) {
					$page_footer_option = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'footer_meta_box_check', true );
				}
			}

			?>

			<?php if ( $page_footer_option == "on" ) : ?>

				<footer id="site-footer">

					<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>

						<div class="trigger-footer-widget-area">
							<span class="trigger-footer-widget spk-icon-load-more"></span>
						</div>

						<div class="site-footer-widget-area">
							<div class="row">
								<?php dynamic_sidebar( 'footer-widget-area' ); ?>
							</div><!-- .row -->
						</div><!-- .site-footer-widget-area -->

					<?php endif; ?>

					<div class="site-footer-copyright-area">
						<div class="row">
							<div class="large-12 columns">

								<?php do_action( 'footer_socials'); ?>

								<nav class="footer-navigation-wrapper">
									<?php
									wp_nav_menu(array(
										'theme_location'  => 'footer-navigation',
										'fallback_cb'     => false,
										'container'       => false,
										'depth' 		  => 1,
										'items_wrap'      => '<ul class="%1$s">%3$s</ul>',
									));
									?>
								</nav><!-- #site-navigation -->

								<div class="copyright_text">
									<?php if ( !empty( Shopkeeper_Opt::getOption( 'footer_copyright_text' ) ) ) { ?>
										<?php printf( __( '%s', 'shopkeeper' ), Shopkeeper_Opt::getOption( 'footer_copyright_text' ) ); ?>
									<?php } ?>
								</div><!-- .copyright_text -->

							</div><!--.large-12-->
						</div><!-- .row -->
					</div><!-- .site-footer-copyright-area -->

				</footer>

			<?php endif; ?>

			</div><!-- #page_wrapper -->

		</div><!--</st-content -->

	</div><!-- .st-container -->

<?php } ?>

<?php do_action( 'wp_footer_components' ); ?>

<?php wp_footer(); ?>

</body>

</html>
