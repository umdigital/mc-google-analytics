<div class="wrap">
    <h2>Google Analytics</h2>
    <form method="post" action="options.php">
        <? settings_fields( 'mc_google_analytics' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Tracking ID:</th>
                <td><input type="text" name="mc_ga_tracking_id" value="<?php echo get_option('mc_ga_tracking_id'); ?>" placeholder="UA-000000-0" /></td>
            </tr>
        </table>

        <? submit_button(); ?>
    </form>
</div>
