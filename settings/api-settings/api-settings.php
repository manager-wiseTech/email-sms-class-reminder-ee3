<?php
 register_setting('reminder-plugin-settings','reminder_plugin_email_checkbox');
    register_setting('reminder-plugin-settings','reminder_plugin_sms_checkbox');
    register_setting('reminder-plugin-settings','reminder_plugin_reminder_message_date');
    register_setting('reminder-plugin-settings','reminder_plugin_country_name');
    register_setting('reminder-plugin-settings','reminder_plugin_twilio_api_sid');
    register_setting('reminder-plugin-settings','reminder_plugin_twilio_api_auth_token');
    register_setting('reminder-plugin-settings','reminder_plugin_twilio_api_phone_number');
    add_option('reminder_plugin_reminder_message_date', '1');
     add_settings_section(
        'reminder_plugin_api_options_section',
        'Plugin Settings',
        'reminder_plugin_api_options_section_cb',
        'reminder-plugin-settings'
    );
     add_settings_field(
        'reminder_plugin_api_email_settings_field',
        'Send Email as Reminder',
        'reminder_plugin_api_email_settings_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_section'
    );
     add_settings_field(
        'reminder_plugin_api_sms_settings_field',
        'Send SMS as Reminder',
        'reminder_plugin_api_sms_settings_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_section'
    );
    add_settings_field(
        'reminder_plugin_reminder_message_date_field',
        'Before how many days you want to send reminder message',
        'reminder_plugin_reminder_message_date_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_section'
    );
     add_settings_field(
        'reminder_plugin_country_name_field',
        'Enter Your Country Name',
        'reminder_plugin_country_name_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_section'
    );
     add_settings_section(
        'reminder_plugin_api_options_settings_section',
        'Twilio Api Credentials',
        'reminder_plugin_api_options_settings_section_cb',
        'reminder-plugin-settings'
     );
     add_settings_field(
        'reminder_plugin_api_accountsid_field',
        'Account SID',
        'reminder_plugin_api_accountsid_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_settings_section'
    );
     add_settings_field(
        'reminder_plugin_api_auth_token_field',
        'AUTH Token',
        'reminder_plugin_api_auth_token_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_settings_section'
    );
       add_settings_field(
        'reminder_plugin_api_twilio_phone_number_field',
        'Twilio Phone Number',
        'reminder_plugin_api_twilio_phone_number_field_cb',
        'reminder-plugin-settings',
        'reminder_plugin_api_options_settings_section'
    );

function reminder_plugin_api_options_section_cb(){
  echo "Check the options you want to use";
}
function reminder_plugin_api_accountsid_field_cb(){
  $acc_sid = get_option('reminder_plugin_twilio_api_sid');
  ?>
  <input type="text" name="reminder_plugin_twilio_api_sid" class="regular-text" value="<?php echo $acc_sid ? $acc_sid : "" ?>"><span class="description"> Enter your ACCOUNT SID provided by Twilio. You can find it form <a href="https://www.twilio.com/console">here</a>.</span>
<?php
}
function reminder_plugin_api_auth_token_field_cb(){
  $auth_token = get_option("reminder_plugin_twilio_api_auth_token");
  ?>
  <input type="text" name="reminder_plugin_twilio_api_auth_token" class="regular-text" value="<?php echo $auth_token ? $auth_token : "" ?>"><span class="description"> Enter your account AUTH Token provided by Twilio. You can find it form <a href="https://www.twilio.com/console">here</a>.</span>
  <?php
}
function reminder_plugin_api_twilio_phone_number_field_cb(){
  $twilio_number = get_option('reminder_plugin_twilio_api_phone_number');
  ?>
  <input type="text" name="reminder_plugin_twilio_api_phone_number" value="<?php echo $twilio_number ? $twilio_number : "" ?>"><span class="description"> Enter Phone number assigned by Twilio. You can find it form <a href="https://www.twilio.com/console">here</a>.</span>
  <?php
}
function reminder_plugin_api_email_settings_field_cb(){
  
  $email_checkbox = get_option('reminder_plugin_email_checkbox');
  ?>
  <input type="checkbox"  name="reminder_plugin_email_checkbox" value="true" <?php echo  $email_checkbox ? "Checked" : ''; ?> /><span class="description">Check this if want to  send an Email as Reminder</span>
  <?php
 
}
function reminder_plugin_api_sms_settings_field_cb(){
  $sms_checkbox = get_option('reminder_plugin_sms_checkbox');
  ?>
  <input type="checkbox"  name="reminder_plugin_sms_checkbox" value="true" <?php echo $sms_checkbox ? "Checked" : ''; ?> /><span class="description">Check this if want to  send sms as Reminder</span>
  <?php
}
function reminder_plugin_reminder_message_date_field_cb(){
  $reminder_day = get_option('reminder_plugin_reminder_message_date');
  ?>
  <input type="Number" placeholder="1" name="reminder_plugin_reminder_message_date" class="small-text" value="<?php echo $reminder_day ? $reminder_day : "" ?>">
  <span class="description">Please Enter number of days in digits like 2</span>
  <?php
}
function reminder_plugin_country_name_field_cb(){
  $country_name = get_option('reminder_plugin_country_name');
  ?>
  <input type="text" placeholder="Country Name" name="reminder_plugin_country_name" class="regular-text" value="<?php echo $country_name ? $country_name : "" ?>">
  <span class="description">Please Enter Country Name like "United States of America"</span>
  <?php
}
function reminder_plugin_api_options_settings_section_cb(){
  echo "<h4>Please Provide Your Twilio account API Credentials</h4>";
}

?>