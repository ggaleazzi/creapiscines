<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://trackthe.click
 * @since      0.0.7
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/admin/partials
 */

$date_format = get_option ( 'date_format ', 'm/d/Y' );
if ($date_format == '') {$date_format = 'm/d/Y';}

?>
<div class="ttc-head">
    <div class="ttc-head-wrap">
        <a href="https://trackthe.click/" target="_blank">Trackthe.click</a>
    </div>
</div>
<div class="ttc-container">
    
    <div class="ttc-wrap ttc-wrap-filters">
        <div class="ttc-filters-row">
            <div class="ttc-filter">
                <label for="ttc-from">From</label>
                 <input type="text" id="ttc-from" name="ttc-from" value="<?php $d = new DateTime();echo $d->sub( new DateInterval( 'P7D' ) )->format( $date_format ); ?>">
            </div>
            <div class="ttc-filter ttc-filter-bar">
              <label for="ttc-to">to</label>
              <input type="text" id="ttc-to" name="ttc-to" value="<?php $d = new DateTime();echo $d->sub( new DateInterval( 'P0D' ) )->format( $date_format ); ?>">
             </div>
            <div class="ttc-filter">
                <label for="ttc-group">Group by</label>
                <select name="ttc-group" id="ttc-group">
                    <option value="link" selected="selected">link</option>
                    <option value="domain">domain</option>
                    <option value="page">page</option>
                </select>
             </div>
            <div class="ttc-filter">
                <label for="ttc-group-time">and</label>
                <select name="ttc-group-time" id="ttc-group-time">
                    <option value="day" selected="selected">day</option>
                    <option value="hour">hour</option>
                </select>
                 </div>
        </div>
    </div>
    <div class="ttc-wrap">
        <div class="ttc-chart-container">
            <canvas id="ttc-chart" style="height: 200px;"></canvas>
        </div>
    </div>
    
     <div class="ttc-wrap">
        <script>if ( typeof(getCSV) === "function" ) { document.write('<div class="ttc-filters-row" style="margin-bottom: 20px;"><a onclick="getCSV()">Download as CSV</a></div>') }</script>
        <div class="ttc-wrap-table">
            <div id="ttc-results-table" style="overflow-x: auto;">
            </div>
        </div>
    </div>

    <div id="ttc-results-text">
	</div>
</div>
