<?php
$sample_email_msg = "Hi {attendee_name} !
Thank You for Registering for {event_name}.
As a REMINDER the class details are as follows:
Course Name: {event_name}
Class Date: {event_date}
Class Start Time: {event_start_time}
Class End Time: {event_end_time}
Address: {event_address} {event_city}
Please contact us on 1-888-870-7002 if you have any question prior to the class. Thank you again. We look forward to seeing you tomorrow.";

register_setting('reminder-plugin-email-settings','reminder_plugin_email_message_format_setting');
add_option('reminder_plugin_email_message_format_setting',$sample_email_msg);
 add_settings_section(
        'reminder_plugin_email_settings_section',
        'Email Settings',
        'reminder_plugin_email_settings_section_cb',
        'reminder-plugin-email-settings'
    );
  add_settings_field(
        'reminder_plugin_email_messgae_format_settings_field',
        'Format Your message as You Want',
        'reminder_plugin_email_messgae_format_settings_field_cb',
        'reminder-plugin-email-settings',
        'reminder_plugin_email_settings_section',
        ['class' => 'mail_scope']
    );
function reminder_plugin_email_settings_section_cb(){
	echo "<h4>Here you can edit your email Message which is to be sent as a reminder message</h4>";
}
function reminder_plugin_email_messgae_format_settings_field_cb(){
	$mail_msg = get_option('reminder_plugin_email_message_format_setting');
    ?>
    <div class="wrap">

    <div id="icon-options-general" class="icon32"></div>
    <h1><?php esc_attr_e( 'Message', 'WpAdminStyle' ); ?></h1>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">

                        <h2><span><?php esc_attr_e( 'Format Your Message', 'WpAdminStyle' ); ?></span></h2>

                        <div class="inside">
                            <textarea  name="reminder_plugin_email_message_format_setting" cols="80" rows="25" class="large-text"><?php echo $mail_msg;  ?></textarea>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables .ui-sortable -->

            </div>
            <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">

                    <div class="postbox">

                        <h2 align="center"><span><?php esc_attr_e(
                                    'Use these place holder instead of using their values in your Message', 'WpAdminStyle'
                                ); ?></span></h2>
                                <p align="center"><span style="color:red;">*</span>These placeholders will be replaced by its original value in the message</p>

                        <div class="inside">
        <table border="1" align="center">
        <tr>
            <th style="text-align: center">Placeholders</th>
            <th style="text-align: center">Used for</th>
        </tr>
        <tr>
            <td >{attendee_name}</td>
            <td>Student/client name</td>
        </tr>
        <tr>
            <td >{event_name}</td>
            <td>Event/Class name</td>
        </tr>
        <tr>
            <td >{event_date}</td>
            <td>Event/Class Date</td>
        </tr>
        <tr>
            <td >{event_start_time}</td>
            <td>Event start time</td>
        </tr>
        <tr>
            <td >{event_end_time}</td>
            <td>Event end time</td>
        </tr>
        <tr>
            <td >{event_city}</td>
            <td>Event City name</td>
        </tr>
        <tr>
            <td >{event_address}</td>
            <td>Event Venue</td>
        </tr>
    </table>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables -->

            </div>
            <!-- #postbox-container-1 .postbox-container -->

        </div>
        <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->
<script type="text/javascript">
     jQuery(document).ready(function(){
    jQuery('.mail_scope').find('[scope=row]').hide();  
    });
</script>

    <?php
}
?>