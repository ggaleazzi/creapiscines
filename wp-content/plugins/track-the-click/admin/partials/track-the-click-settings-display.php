<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/admin/partials
 */
?>

<?php
if ( class_exists( 'Track_The_Click_Pro' ) ) {
	$pro_available = true;
	$plugin_pro = new Track_The_Click_Pro( $this->get_plugin_name(), $this->get_version(), $this->basefile );
} else {
	$pro_available = false;
}
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form action="options.php" method="post">
		<div class="ttc-bar ttc-light-grey ttc-border">
			<button type="button" class="ttc-bar-item ttc-button ttc-settings-tabbutton ttc-dark-grey" onclick="openTab(event, 'ttc-general')">General</button>
			<button type="button" class="ttc-bar-item ttc-button ttc-settings-tabbutton" onclick="openTab(event, 'ttc-other')">Other</button>
			<?php if ( !$pro_available ) { ?>
			<button type="button" class="ttc-bar-item ttc-button ttc-settings-tabbutton" onclick="openTab(event, 'ttc-pro')">Pro</button>
			<?php } else {
				$plugin_pro->settings_buttons();
			} ?>
			<button type="button" class="ttc-bar-item ttc-button ttc-settings-tabbutton" onclick="openTab(event, 'ttc-help')">Help</button>
		</div>
		<div id="ttc-general" class="ttc-settings-tab">
	    <?php
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
			?>
		</div>
		<div id="ttc-other" class="ttc-settings-tab" style="display:none">
			<?php
				do_settings_sections( $this->plugin_name . '-other' );
			?>
		</div>
		<div id="ttc-pro" class="ttc-settings-tab" style="display:none">
		<?php if ( !$pro_available ) { ?>
			<p>Functionality on this page is only available by upgrading to <a href="https://trackthe.click/track-the-click-pro/" target="_blank">Track The Click Pro</a>.
			<?php }
				do_settings_sections( $this->plugin_name . '-pro' );
			?>
		</div>
		<div id="ttc-help" class="ttc-settings-tab" style="display:none">
			<h2>Click data - the basics</h2>
			<p>Once the plugin is active, every time a website visitor clicks an external link, Track The Click attempts to record that click.  The plugin records the time, date, page and destination URL.  You can then use the various click views to interpret this click data.</p>
			<p>As standard we try to exclude your own domain name from the click views.  We do this by adding your Site Address to the domain exclusion list.  Your current setting for Site Address under Settings -> General -> Site Address is <b><?php echo get_home_url() ?></b>.  Sometimes you might have internal links that don’t match this Site Address.  For example if your Site Address is<br>
			https://domain.com<br>
			you might also have links in your site of the form<br>
			https://www.domain.com<br>
			In these instances you will need to add the extra variations of your site to the exclusion list under the Other tab, above.  The clicks are still recorded, but they will be hidden in the various click views to help you interpret your data better.</p>
		</div>

		<?php
      submit_button();
	  ?>
	</form>
</div>
