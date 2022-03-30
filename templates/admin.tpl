<style type="text/css">
    .mcga-events {
        border-collapse: collapse;
    }
    .mcga-events th {
        text-align: left;
        padding: 2px 10px;
        border-bottom: 1px solid;
    }
    .mcga-events td {
        padding: 5px 10px;
        border-bottom: 1px solid #ccc;
    }
    .mcga-events td.other input[type="text"] {
        width: 300px;
    }
    .mcga-events td em {
        color: #999;
    }
</style>

<div class="wrap">
    <h2>Google Analytics</h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'mc_google_analytics' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Tracking ID:</th>
                <td>
                    <input type="text" name="mc_ga_tracking_id" value="<?php echo get_option('mc_ga_tracking_id'); ?>" placeholder="UA-000000-0" /><br/>
                    <em>You can find this in the analytics admin Property Settings area.</em>
                </td>
            </tr>
        </table>

        <h3>Link Event Tracking</h3>
        <p>Use the following to track links via <a href="https://support.google.com/analytics/answer/1033068?hl=en">Google Analytics Event</a> tracking. You can customize the category but the Action will always be the link destination and the label will be the page the link was clicked on.</p>
        <?php
        $mcGAEvents = array_replace_recursive(
            array(
                'email' => array(
                    'status'   => 0,
                    'category' => ''
                ),
                'download' => array(
                    'status'     => 0,
                    'category'   => '',
                    'extensions' => ''
                ),
                'external' => array(
                    'status'   => 0,
                    'category' => ''
                ),
            ),
            get_option( 'mc_ga_events' ) ?: array()
        );
        ?>
        <table class="mcga-events">
            <tr>
                <th>Type</th>
                <th>Category</th>
                <th></th>
            </tr>
            <tr valign="top">
                <td>
                    <input type="checkbox" id="mcga-email-link" name="mc_ga_events[email][status]" value="1" <?php echo ($mcGAEvents['email']['status'] ? 'checked="checked"' : null);?> />
                    <label for="mcga-email-link">Email</label>
                </td>
                <td><input type="text" placeholder="MailTo" name="mc_ga_events[email][category]" value="<?php echo $mcGAEvents['email']['category'];?>" /></td>
                <td></td>
            </tr>
            <tr valign="top">
                <td>
                    <input type="checkbox" id="mcga-doc-link" name="mc_ga_events[download][status]" value="1" <?php echo ($mcGAEvents['download']['status'] ? 'checked="checked"' : null);?> />
                    <label for="mcga-doc-link">File</label>
                </td>
                <td>
                    <input type="text" placeholder="Downloads-{EXT}" name="mc_ga_events[download][category]" value="<?php echo $mcGAEvents['download']['category'];?>" /><br/>
                    <em>use the token "{EXT}" for<br/> the file extension.</em>
                </td>
                <td class="other">
                    <input type="text" placeholder="doc|docx|xls|xlsx|ppt|pptx|jpg|png|gif|pdf|zip|txt|mov" name="mc_ga_events[download][extensions]" value="<?php echo $mcGAEvents['download']['extensions'];?>" /><br/>
                    <em>File extensions to track (pipe or comma delimited).<br/>
                    <strong>Default:</strong> doc|docx|xls|xlsx|ppt|pptx|jpg|png|gif|pdf|zip|txt|mov</em>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <input type="checkbox" id="mcga-external-link" name="mc_ga_events[external][status]" value="1" <?php echo ($mcGAEvents['external']['status'] ? 'checked="checked"' : null);?> />
                    <label for="mcga-external-link">External</label>
                </td>
                <td><input type="text" placeholder="External" name="mc_ga_events[external][category]" value="<?php echo $mcGAEvents['external']['category'];?>" /></td>
                <td></td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
