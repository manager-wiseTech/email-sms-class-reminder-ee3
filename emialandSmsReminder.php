<?php
/*
 *Plugin Name: Email/SMS Reminder
 * Plugin URI:        http://www.finaldatasolutions.com/
 * Description:       This plugin allow you to send Reminder Message to your clients/students [of Event Espresso 3] through Email and SMS. It comes with the twilio api integeration. You just need to provide your twillio account credentials to work. You don't need to provide any credential to send message as Email.
 * Version:           1.0.0
 * Author:            Ibrar Ayoub
 * Author URI:        http://www.finaldatasolutions.com/
*/

require 'plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/manager-wiseTech/email-sms-class-reminder-ee3/',
  __FILE__,
  'fds-email-sms-class-reminder-ee3'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('your-token-here');



session_start();
add_action("admin_menu","reminder_plugin_menu");
function reminder_plugin_menu(){
  add_menu_page( "Reminder Message", "Reminder Message", 'manage_options', 'reminder-plugin', 'reminder_plugin_cb_fn', 'dashicons-email-alt', 10 );
  add_submenu_page('reminder-plugin', "Reminder Message", "Reminder Message", 'manage_options', 'reminder-plugin', 'reminder_plugin_cb_fn', 'dashicons-email-alt');
  add_submenu_page('reminder-plugin',"Settings Message","Settings",'manage_options','reminder-plugin-settings','reminderplugin_settings_cb_fn');
}
function reminderplugin_settings_cb_fn(){
  ?>
  <div class="wrap">
    <h2>Reminder Plugin Settings</h2>
    <?php settings_errors(); ?>
    <?php
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'api_options';
      if( isset( $_GET[ 'tab' ] ) ) {
          $active_tab = $_GET[ 'tab' ];
      } 
      ?>
         <h2 class="nav-tab-wrapper">
            <a href="?page=reminder-plugin-settings&tab=api_options" class="nav-tab <?php echo $active_tab == 'api_options' ? 'nav-tab-active' : ''; ?>">Plugin Options Settings</a>
            <a href="?page=reminder-plugin-settings&tab=email_options" class="nav-tab <?php echo $active_tab == 'email_options' ? 'nav-tab-active' : ''; ?>">Email Options</a>
            <a href="?page=reminder-plugin-settings&tab=sms_options" class="nav-tab <?php echo $active_tab == 'sms_options' ? 'nav-tab-active' : ''; ?>">SMS Options</a>
        </h2>
        <form method="post" action="options.php">
            <?php
                if ($active_tab == 'api_options') {
                  settings_fields( 'reminder-plugin-settings' );
                  do_settings_sections( 'reminder-plugin-settings' );
                }
                elseif ($active_tab == 'email_options')
                {
                  settings_fields( 'reminder-plugin-email-settings' );
                  do_settings_sections( 'reminder-plugin-email-settings' );
                }
                elseif ($active_tab == 'sms_options') {
                    
                    settings_fields( 'reminder-plugin-sms-settings' );
                    do_settings_sections( 'reminder-plugin-sms-settings' );  
                 } 
                
                 submit_button(); ?>
  </div>
  <?php

 
}

function reminder_plugin_settings_init(){
  include_once plugin_dir_path(__FILE__).'settings/api-settings/api-settings.php'; 
  include_once plugin_dir_path(__FILE__).'settings/email-settings/email-settings.php';
  include_once plugin_dir_path(__FILE__).'settings/sms-settings/sms-settings.php';

}
add_action('admin_init', 'reminder_plugin_settings_init');


function reminder_plugin_cb_fn(){
  global $wpdb;
 include_once plugin_dir_path(__FILE__).'views/sms-records.php';   
  //delete record code file
  $action = isset($_GET['action'])? trim($_GET['action']):"";
  if ($action == 'delete') {
    $record_id = isset($_GET['record_id'])?intval($_GET['record_id']):"";
      ob_start();
      include_once plugin_dir_path(__FILE__).'views/delete-record.php';
      $template = ob_get_contents();
      ob_end_clean();
      echo $template;
      ?>
      <div id="msgdiv">
       
      <?php
      if ( false === $deleted ) {
             ?>
          <div class="notice notice-error is-dismissible inline">
                <p>Error In Deleting Records. Try again.</p>
            </div>
          
        
        <?php
          } else {
              ?>
         <div class="notice notice-success is-dismissible inline">
                <p>Last 50 Records Deleted Successfully.</p>
            </div>
      <?php
          }
     ?>
     </div>
     <?php

  }
  if ($action == 'bulk_delete') {
    $table = $wpdb->prefix."email_sms_records_tb";
     ?>
     <div id="msgdiv">
    <?php
    if ($wpdb->query("DELETE FROM $table ORDER BY record_id ASC LIMIT 50")) {
        ?>
       
          <div class="notice notice-success is-dismissible inline">
                <p>Last 50 Records Deleted Successfully.</p>
            </div>
        
        <?php
    }
    else{
      ?>
         <div class="notice notice-error is-dismissible inline">
                <p>Error In Deleting Records. Try again.</p>
            </div>
      <?php
    }
    ?>
    </div>
    <?php
  }
}
register_activation_hook( __FILE__, 'reminderplugin_activation' );
function reminderplugin_activation(){

    $time = new DateTime( "2016-10-10 17:00:00", new DateTimeZone( 'UTC' ) );
    
        if( ! wp_next_scheduled( 'reminderplugin_email_hook' ) ) {
           wp_schedule_event( $time->getTimestamp(), 'daily', 'reminderplugin_email_hook');
          }
reminderplugin_generate_db_table();    
}
add_action( 'reminderplugin_email_hook', 'send_reminder_message' );
function reminderplugin_generate_db_table(){
  global $wpdb;  
    $table_name = $wpdb->prefix . "email_sms_records_tb";
    $sql = 'CREATE TABLE IF NOT EXISTS '.$table_name.' (
    `record_id` int(11) NOT NULL AUTO_INCREMENT,
    `msg_id` varchar(50) NULL, 
    `attendee_id` int(11) NOT NULL,
    `attendee_name` varchar(100)NOT NULL,
    `attendee_phone` varchar(15)NOT NULL,
    `attendee_email` varchar(50) NOT NULL,
    `event_name` varchar(50) NOT NULL,
    `msg_date_time` varchar(100) NULL,
    `email_status` varchar(10) NULL,
    `sms_status` varchar(10) NULL,
    `email_msg` varchar(1000) NULL,
    `sms_msg` varchar(1000) NULL,
    `error_msg` varchar(500) NULL,
    `error_code` varchar(10) NULL,
    PRIMARY KEY (`record_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
function send_reminder_message(){

    global $wpdb;
    $table_name = $wpdb->prefix."events_attendee"; 
    
    $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE payment_status = 'Completed'");
    $today = date('Y-m-d');
    $number_of_days = get_option('reminder_plugin_reminder_message_date');
    foreach($results as $row){
     $event_date = $row->start_date;
     $mail_date = date('Y-m-d', strtotime('-'.$number_of_days.' day', strtotime($event_date)));
      
      $stud_phone = $row->phone;
      $stud_email = $row->email;
      if( $mail_date == $today ){ 
        //echo "Date is matched<br>";

        $evnet_table_name = $wpdb->prefix."events_detail";
        $events = $wpdb->get_results( "SELECT * FROM $evnet_table_name WHERE id = $row->event_id");
        foreach ($events as $event) {
        $mail_message = get_option('reminder_plugin_email_message_format_setting'); 
        $sms_message = get_option('reminder_plugin_sms_message_format_setting'); 
        
        

        $placeholders = array("{attendee_name}","{event_name}","{event_date}","{event_start_time}","{event_end_time}","{event_address}","{event_city}");
        
        $original_values = array($row->fname." ".$row->lname,$event->event_name,$event->start_date,$row->event_time,$row->end_time,$event->address,$event->city);
        
        $mail_message = str_replace($placeholders,$original_values,$mail_message);
        $sms_message = str_replace($placeholders,$original_values,$sms_message);
         


          $_SESSION['attendee_id'] = $row->id;
          $_SESSION['attendee_name'] = $row->fname." ".$row->lname;
          $_SESSION['attendee_email'] = $stud_email;
          $_SESSION['attende_phone'] = $stud_phone;
          $_SESSION['event_name'] = $event->event_name;

        }
            
          

          
          if (get_option('reminder_plugin_email_checkbox') == "true") {
                send_email_for_reminder($stud_email,$mail_message);
              //  echo "mail sent ok";
                
            }   


            if (get_option('reminder_plugin_sms_checkbox') == "true"){
               send_sms_for_reminder($stud_phone,$sms_message);
              // echo "message sent ok";
                
            }
            if (get_option('reminder_plugin_email_checkbox') == "true" || get_option('reminder_plugin_sms_checkbox') == "true") {
              insert_data_db();
             
            }
            

      }
     

    }

}
function send_email_for_reminder($email,$msg){
     $headers = $_SERVER['SERVER_NAME'];
      $headers .= 'From: <no-reply@'.$headers.'>' . "\r\n";
        // Send
        if(mail($email,'Class Reminder', $msg,$headers))
            {
              $_SESSION["email_status"] = "sent";
              $_SESSION["email_msg"] = $msg;
            }
        else
            {
              $_SESSION["email_status"] = "Not sent";
            }   
}
function send_sms_for_reminder($phone,$msg){
      // Update the path below to your autoload.php,
     // see https://getcomposer.org/doc/01-basic-usage.md
    require_once dirname(__FILE__).'/vendor/autoload.php';
    //use Twilio\Rest\Client;
    // Find your Account Sid and Auth Token at twilio.com/console
    // DANGER! This is insecure. See http://twil.io/secure
    $sid    = get_option('reminder_plugin_twilio_api_sid');
    $token  = get_option('reminder_plugin_twilio_api_auth_token');
    $twilio = new Twilio\Rest\Client($sid, $token);
    include_once plugin_dir_path(__FILE__).'settings/countryToCode.php';
    echo $country_code = countryToCode(get_option('reminder_plugin_country_name'));
     $phone_number = $twilio->lookups->v1->phoneNumbers($phone)->fetch(array("countryCode" => $country_code));
     $phone = $phone_number->phoneNumber;
    $message = $twilio->messages
                      ->create($phone, // to+16048080668
                               array(
                                   "body" => $msg,
                                   "from" => get_option('reminder_plugin_twilio_api_phone_number')
                               )
                      );
    
    $msg_id = $message->sid;
    if ($msg_id) {

      $message = $twilio->messages($msg_id)
                  ->fetch();
        
        $_SESSION["msg_id"] = $msg_id;
        $_SESSION["sms_status"] = $message->status;
        $_SESSION["sms_msg"] = $message->body;
       // $_SESSION["date_sent"] = $message->dateSent->format('Y-m-d H:i:s');
        $_SESSION["error_msg"] = $message->errorMessage;
        $_SESSION['error_code'] = $message->errorCode; 
    }
    

}
function insert_data_db(){
  if (get_option('reminder_plugin_email_checkbox') == "true") {
    $mail_status = $_SESSION['email_status'];
  }
  else $mail_status = "false";
  if (get_option('reminder_plugin_sms_checkbox') == "true"){
    $sms_status = $_SESSION['sms_status'];
  }
  else $sms_status = "false";
  $msg_id = $_SESSION["msg_id"];
  $mail_status = $_SESSION['email_status'];
  $attendee_id = $_SESSION['attendee_id']; 
  $attendee_name = $_SESSION['attendee_name'];
  $attendee_email = $_SESSION['attendee_email']; 
  $event_name = $_SESSION['event_name']; 
  $attendee_phone = $_SESSION['attende_phone'];
  $email_msg = $_SESSION['email_msg'];
  $sms_msg = $_SESSION['sms_msg'];
  $msg_date_time = date('Y-m-d H:i:sa'); //$_SESSION["date_sent"];
  $msg_error = $_SESSION['error_msg'];
  $error_code = $_SESSION['error_code'];
       //print_r($_SESSION);
       global $wpdb;
       $table = $wpdb->prefix.'email_sms_records_tb';
       $data = array('msg_id' => $msg_id,'attendee_id'=>$attendee_id,'attendee_name'=>$attendee_name,'attendee_phone'=> $attendee_phone, 'attendee_email'=> $attendee_email, 'event_name'=>$event_name,'email_status'=> $mail_status,'msg_date_time'=>$msg_date_time,'sms_status'=>$sms_status,'email_msg'=>$email_msg,'sms_msg'=>$sms_msg,'error_msg'=>$msg_error,'error_code'=>$error_code);
      // echo"<br>Query Data to be Insertd in database";
       print_r($data);
        $format = array('%s','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        $wpdb->insert($table,$data,$format);
        $_SESSION["msg_id"] = $_SESSION['email_status'] = $_SESSION['attendee_id'] = $_SESSION['attendee_name'] = $_SESSION['attendee_email'] = $_SESSION['event_name'] = $_SESSION['attende_phone'] = $_SESSION['email_msg'] = $_SESSION['sms_msg'] = $_SESSION["date_sent"] = $_SESSION['error_msg'] = $_SESSION['error_code'] = NULL;
}
register_deactivation_hook( __FILE__, 'reminderplugin_deactivation' );
function reminderplugin_deactivation(){
    wp_clear_scheduled_hook('reminderplugin_email_hook');

}
