<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
 /**
  * 
  */
 class ReminderSMSRecordClass extends WP_List_Table
 {
 	/**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    function extra_tablenav( $which ) {
   if ( $which == "top" ){
      //The code that goes before the table is here
    echo '<a onClick="return confirmDelete()" href="?page='.$_GET['page'].'&action=bulk_delete" class="button-primary">Delete Last 50 Records</a>';
   }
   if ( $which == "bottom" ){
      //The code that goes after the table is there
      echo '<a href="?page='.$_GET['page'].'&action=bulk_delete" class="button-primary">Delete Last 50 Records</a>';
   }
}
 	public function prepare_items()
    {
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
    	//$this->items = $this->$data;
         $data = $this->table_data();
         usort( $data, array( &$this, 'sort_data' ) );
         $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
        
    }


      /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'record_id' => 'ID',
        	'msg_id'=>'Message ID',
            'attendee_name' => 'Attendee Name',
            'attendee_email' => 'Email',
            'event_name'=>'Event Name',
            'msg_date_time'=>'Date Time',
            'email_status' => 'Email Status',
            'sms_status' => 'SMS Status',
            'error_msg' => 'Error Message',
            'error_code' => 'Error Code',
            'sms_msg' => 'SMS Content',
            'actions' => 'Action'
            );
        return $columns;
    }
    public function column_cb($item){
        return sprintf('<input type="checkbox" name=msg[] value="%s"/>',$item['record_id']);
    }
    public function column_default($item,$column_name)
    {
    	switch( $column_name ) {
            case 'record_id':
            case 'msg_id':
            case 'attendee_name':
            case 'attendee_email':
            case 'event_name':
            case 'msg_date_time':
            case 'sms_status':
            case 'email_status':
            case 'error_msg':
            case 'error_code': 
            case 'sms_msg':
                return $item[ $column_name ];
            case 'actions':
            	return '<a onClick="return confirmDelete1()" href="?page='.$_GET['page'].'&action=delete&record_id='.$item['record_id'].'">Delete</a>'; 
            default:
                return print_r( $item, true ) ;
        }
    }
    
	      /**
	     * Define which columns are hidden
	     *
	     * @return Array
	     */
	    public function get_hidden_columns()
	    {
	        return array('sms_msg');
	    }


	    /**
	     * Define the sortable columns
	     *
	     * @return Array
	     */
	    public function get_sortable_columns()
	    {
	        return array('msg_date_time' => array('msg_date_time', false));
	    }
        /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = '';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    } 

     private function table_data()
    {
    	global $wpdb;
    	 
    	$table_name = $wpdb->prefix."email_sms_records_tb";
		$posts = $wpdb->get_results("SELECT * FROM $table_name ORDER BY record_id ASC");
		

		$posts_array = array();

		 foreach($posts as $post){ 

		$posts_array[] = array(
      "record_id" =>$post->record_id,
			"msg_id" => $post->msg_id,
			"attendee_name" => $post->attendee_name,
			"attendee_email" => $post->attendee_email,
			"event_name"=>$post->event_name,
			"msg_date_time"=>$post->msg_date_time,
      "sms_status"=>$post->sms_status,
			"email_status"=>$post->email_status,
			'error_msg' => $post->error_msg,
      'error_code' => $post->error_code,
			"sms_msg"=>$post->sms_msg
		);

		} 
        return $posts_array;
    }


 }
    	function list_table_layout()
		 
		 {
		 	$reminder_record = new ReminderSMSRecordClass();

		 	echo "<h1 align = center> Reminder Email & SMS Log </h1>";
		 	$reminder_record->prepare_items();

		 	$reminder_record->display();

		 }
		 ?>
		 <div style="padding-right: 10px;">
         <?php
		 list_table_layout();
		 ?>	      
		 </div>
<script type="text/javascript">
 function confirmDelete(){
    var r = confirm("Are you Sure you want to Delete Last 50 Records ?");
  if (r == true) return true;
   else return false;
 }
  function confirmDelete1(){
    var r = confirm("Are you Sure you want to Delete this Record ?");
  if (r == true) return true;
   else return false;
 }
</script>