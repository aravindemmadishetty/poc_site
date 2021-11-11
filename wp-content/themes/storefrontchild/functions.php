<?php

/*
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['order']['order_comments']['placeholder'] = 'My new placeholder';

     $fields['billing']['billing_new'] = array(
        'label' => __('new field', 'woocommerce'),
        'placeholder' => _x('new field', 'woocommerce'),
        'required' => true,
        'class' => array('form-row-wide'),
        'clear' => true
      );
     return $fields;
}


 * Update the order meta with field value

add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

function my_custom_checkout_field_update_order_meta( $order_id ) {
    $file = fopen("D:\xampp\htdocs\dev\pocsite\wordpress\logs.txt", "a");
    fwrite($file, "<---------------------order-------------->");
    foreach($_POST as $x => $y){   
        fwrite($file, "$x => $y");
    }
    fclose($file);
    if ( ! empty( $_POST['billing_new'] ) ) {
        update_post_meta( $order_id, '_billing_new', sanitize_text_field( $_POST['billing_new'] ) );
    }
}

 */

$event_ticket_type_attributes = [
    'provider_enrollment' => 'Provider Enrollment',
    'support_staff' => 'Support Staff',
    'graduate_rate' => 'Graduate Rate',
    'intern' => 'Intern/Resident/Military',
];

$custom_text_fields_attendee_data = array(
    array(
        'id' => 'attendee_first_name',
        'label' => 'First Name',
        'class' => 'cfwc_attendee_first_name',
        'desc_tip' => true,
    ),
    array(
        'id' => 'attendee_last_name',
        'label' => 'Last Name',
        'class' => 'cfwc_attendee_last_name',
        'desc_tip' => true,
    ),
    array(
        'id' => 'attendee_email_address',
        'label' => 'Email',
        'class' => 'cfwc_attendee_email_address',
        'desc_tip' => true,
    ),
    /*
    array(
        'id' => 'address_line_1',
        'label' => 'Address Line 1',
        'class' => 'cfwc_attendee_address_line_1',
        'desc_tip' => true,
    ),
    */
    array(
        'id' => 'address_line_2',
        'label' => 'Address Line 2',
        'class' => 'cfwc_attendee_address_line_2',
        'desc_tip' => true,
    ),
    array(
        'id' => 'attendee_city',
        'label' => 'City',
        'class' => 'cfwc_attendee_city',
        'desc_tip' => true,
    ),
    array(
        'id' => 'attendee_country',
        'label' => 'Country',
        'class' => 'cfwc_attendee_country',
        'desc_tip' => true,
        'type' => 'dropdown'
    ),
    array(
        'id' => 'attendee_state_province',
        'label' => 'State/Province',
        'class' => 'cfwc_attendee_state_province',
        'desc_tip' => true,
    ),
    array(
        'id' => 'attendee_zip_code',
        'label' => 'ZIP Code',
        'class' => 'cfwc_attendee_zip_code',
        'desc_tip' => true,
    ),
);

/**
 * Display the custom text field
 * @since 1.0.0
 */

function cfwc_create_custom_field() {
    global $event_ticket_type_attributes;
    global $custom_text_fields_attendee_data;
    /** example for a custom field
    * $args = array(
    *   'id' => 'custom_text_field_title',
    *   'label' => __( 'Custom Text Field Title', 'cfwc' ),
    *   'class' => 'cfwc-custom-field',
    *   'desc_tip' => true,
    *   'description' => __( 'Enter the title of your custom text field.', 'ctwc' ),
    *   );
    * woocommerce_wp_text_input( $args );  
    */

   foreach($event_ticket_type_attributes as $ticket_type => $ticket_text){
        woocommerce_wp_text_input(
            array(
                'id' => $ticket_type,
                'label' => __( $ticket_text ),
                'class' => 'ticket_type_dropdown',
                'desc_tip' => true,
            )
        );
   };

   woocommerce_wp_text_input(
       array(
           'id' => 'membership_field',
           'label' => 'Memberships to grant for this product',
           'class' => 'membership_field',
           'desc_tip'=> true,
       )
       );

   /*
   foreach($custom_text_fields_attendee_data as $attendee_data){
        woocommerce_wp_text_input($attendee_data);
   }
   */

}

add_action( 'woocommerce_product_options_inventory_product_data', 'cfwc_create_custom_field' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_custom_field( $post_id ) {
    global $event_ticket_type_attributes;
    global $custom_text_fields_attendee_data;
    $product = wc_get_product( $post_id );
    $title = isset( $_POST['custom_text_field_title'] ) ? $_POST['custom_text_field_title'] : '';
    $dropdown = isset( $_POST['my_select']) ? $_POST['my_select'] : '';
    $product->update_meta_data( 'custom_text_field_title', sanitize_text_field( $title ) );
    $product->update_meta_data('my_select', sanitize_text_field($dropdown));
    
    foreach($event_ticket_type_attributes as $ticket_type => $ticket_text){
        $credentials = isset( $_POST[$ticket_type] ) ? $_POST[$ticket_type] : '';
        if( !empty($credentials) ){
            $product->update_meta_data( $ticket_type , sanitize_text_field( $credentials ) );
        }
    }

    $memberships = isset( $_POST['membership_field']) ? $_POST['membership_field'] : '';
    $product->update_meta_data('membership_field', sanitize_text_field( $memberships ));

    /*
    foreach($custom_text_fields_attendee_data as $attendee_data){
        $data = isset( $_POST[ $attendee_data['id'] ] ) ? $_POST[ $attendee_data['id'] ] : '';
        if( !empty($data) ){
            $product->update_meta_data( $attendee_data['id'], sanitize_text_field( $data ));
        }
    }
    */

    $product->save();
   }
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );

/*
 * Send the dropdown data using ajax


 function cfwc_send_custom_data(){
     global $post;
     global $event_ticket_type_attributes;
     
     $file = fopen("./logs.txt", 'a');
     fwrite($file, $post->ID);
     fclose($file);

     $product = wc_get_product( $post->ID );
     $data = array();
     foreach($event_ticket_type_attributes as $ticket_type => $ticket_text){
        $value = $product->get_meta($ticket_type);
        if($value){
            $data[$ticket_type] = array(
                'ticket_type' => $ticket_text,
                'value' => $value,
            );
        }
     }

     return wp_json_encode($data);
 }

 add_action('wp_ajax_cfcw_custom_dropdown_data', 'cfwc_send_custom_data');
 */

 function cfwc_enque_custom_data_script(){
    if(get_the_terms($wc_pro_id, 'product_cat')[0]->name == "woo_event"){
        wp_enqueue_script('credentials_dropdown_script', get_stylesheet_directory_uri().'/assets/js/custom-fields.js');
        wp_localize_script( 'credentials_dropdown_script', 'my_ajax_obj',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

add_action('wp_enqueue_scripts', 'cfwc_enque_custom_data_script');


/**
 * Display custom field on the front end
 * @since 1.0.0
 */
function cfwc_display_custom_field() {
    global $event_ticket_type_attributes;
    global $custom_text_fields_attendee_data;
    global $post;
    // Check for the custom field value
    $product = wc_get_product( $post->ID );

    /*
    $title = $product->get_meta( 'custom_text_field_title' );
    if( $title ) {
    // Only display our field if we've got a value for the field title
    printf(
    '<div class="cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label><input type="text" id="cfwc-title-field" name="cfwc-title-field" value=""></div>',
    esc_html( $title )
    );
    }

    */

    $data = array();
    /**
     * Credentials Dropdown
     */
     foreach($event_ticket_type_attributes as $ticket_type => $ticket_text){
        $value = $product->get_meta($ticket_type);
        if($value){
            $data[$ticket_text] = array(
                'ticket_type' => $ticket_type,
                'credential' => $value,
            );
        }
     }

     $data["attendee_data"] = $custom_text_fields_attendee_data;

     $memberships = $product->get_meta('membership_field');
     if( $memberships ){
        printf(
            '<input type="hidden" id="membership_field" name="membership_field" value="%s">',
            esc_html( $memberships )
        );
     }
     /*
     foreach($custom_text_fields_attendee_data as $attendee_data){
         $value = $product->get_meta( $attendee_data['id']);
         
         $file = fopen('./logs.txt', 'a');
         fwrite($file, $value);
         fclose($file);

         if($value){
             $data["attendee_data"][$attendee_data['id']] = $attendee_data['label'];
         }
     }

     */



    $json_data = wp_json_encode($data) ?> 
        <script>
            var cfwc_ticket_type_dropdown_data = JSON.parse('<?php echo $json_data ?>');
        </script>
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'cfwc_display_custom_field' );

/**
 * Validate the text field
 * @since 1.0.0
 * @param Array $passed Validation status.
 * @param Integer $product_id Product ID.
 * @param Boolean $quantity Quantity
 */
function cfwc_validate_custom_field( $passed, $product_id, $quantity ) {
    global $wpdb;
    $email = $_POST['attendee_email_address'];
    if( ! empty($email)){
        $user = get_user_by('email', $email);
        if(empty($user)){
            $passed = false;
            wc_add_notice( __("An account doesn't exist for this attendee email. Please create an account and try again") , 'error');
            return $passed;
        }else{
            $is_registered = $wpdb->get_results("SELECT order_item_id FROM `wp_woocommerce_order_itemmeta` WHERE meta_key = 'attendee_email_address' and meta_value = '$email' and order_item_id IN (SELECT order_item_id FROM `wp_woocommerce_order_itemmeta` WHERE meta_key = '_product_id' and meta_value = $product_id );");
            
            if( ! empty($is_registered) ){
                $order_entries = array();
                $refund_entries = array();
                foreach($is_registered as $entry){
                    $order_entries[] = $entry->order_item_id;
                }
                $is_refunded = $wpdb->get_results("SELECT meta_value from `wp_woocommerce_order_itemmeta` where meta_key = '_refunded_item_id' and meta_value IN(".implode(',',$order_entries).")");
                foreach($is_refunded as $entry){
                    $refund_entries[] = $entry->meta_value;
                }

                sort($order_entries);
                sort($refund_entries);

                if( $order_entries != $refund_entries){
                    $passed = false;
                    wc_add_notice( __("The user with the given attendee email is already registered"), 'error');
                    return $passed;
                }
                
            }
            
            $data = WC()->cart->get_cart();
            if(!empty($data)){
                foreach($data as $cart_item){
                    if( $cart_item ['attendee_email_address'] == $email){
                        $passed = false;
                        wc_add_notice ( __("This product with the given attendee email is already present in the cart"), 'error');
                        return $passed;
                    }
                }
            }

        }
    }

    

    
    
    /*
    if( empty( $_POST['cfwc-title-field'] ) && empty( $_POST['cfwc-select-field'] ) ) {
    // Fails validation
    $passed = false;
    wc_add_notice( __( 'Please enter a value into the text field', 'cfwc' ), 'error' );
    }
    */
    return $passed;
   }
add_filter( 'woocommerce_add_to_cart_validation', 'cfwc_validate_custom_field', 10, 3 );

/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array $cart_item_data Cart item meta data.
 * @param Integer $product_id Product ID.
 * @param Integer $variation_id Variation ID.
 * @param Boolean $quantity Quantity
 */
function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
    global $custom_text_fields_attendee_data;
    if( ! empty( $_POST['ticket-type-credentials'] ) ) {
        // Add the item data
        $cart_item_data['ticket-type-credential'] = $_POST['ticket-type-credentials'];
    }

    foreach($custom_text_fields_attendee_data as $field){
        if(! empty( $_POST[$field['id']])){
            $cart_item_data[$field['id']] = $_POST[$field['id']];
        }
    }

    if(! empty( $_POST['membership_field'] ) ){
        $cart_item_data[ 'membership_field' ] = $_POST[ 'membership_field' ];
    }

    return $cart_item_data;
   }
add_filter( 'woocommerce_add_cart_item_data', 'cfwc_add_custom_field_item_data', 10, 4 );

/**
 * Display the custom field value in the cart
 * @since 1.0.0
 */
function cfwc_cart_item_name( $name, $cart_item, $cart_item_key ) {
    if( isset( $cart_item['ticket-type-credential'] ) ) {
        $name .= sprintf(
            '<p>%s</p>',
            esc_html( $cart_item['ticket-type-credential'] )
        );
    }
    return $name;
   }
add_filter( 'woocommerce_cart_item_name', 'cfwc_cart_item_name', 10, 3 );


/**
 * Add custom field to order object
 */
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
    global $custom_text_fields_attendee_data;
    $transactions = array();
    foreach( $item as $cart_item_key=>$values ) {
        if( isset( $values['ticket-type-credential'] ) ) {
            $item->add_meta_data( __( 'ticket-type-credential', 'cfwc' ), $values['ticket-type-credential'], true );
        }
        foreach($custom_text_fields_attendee_data as $field){
            if(isset( $values[$field['id']] ) ){
                $item->add_meta_data( __($field['id'], 'cfwc'), $values[ $field['id'] ], true );
                if( isset( $values['membership_field'] ) && $field['id'] == 'attendee_email_address' ){
                    $memberships = explode( "|", $values['membership_field']);
                    foreach( $memberships as $membership )
                    if(class_exists('MeprTransaction')){
                        $product_id = (int) trim( $membership );
                        $user = get_user_by('email', $values[$field['id']]);
                        if ( empty( $product_id ) && empty($user)) {
                            return;
                        }
                
                        $txn = new MeprTransaction();
                        $txn->user_id = $user->ID;
                        $txn->product_id = $product_id; 
                        $txn->trans_num  = uniqid();
                        $txn->status     = MeprTransaction::$complete_str;
                        $txn->gateway    = MeprTransaction::$free_gateway_str;
                        $txn->expires_at = 0; 
                        $transactions[] = $txn->store();
                    }
                }
            }
        }    
    }
    $item->add_meta_data(__('mepr_transactions', 'cfwc'), implode(',', $transactions));

   }
add_action( 'woocommerce_checkout_create_order_line_item', 'cfwc_add_custom_data_to_order', 10, 4 );

function custom_woo_event_refund( $order_id, $refund_id ){
    $order = wc_get_order($refund_id);

    $file = fopen('./logs.txt', 'a');
    
    foreach($order->get_items() as $refund_item_id => $refund_item){
        $order_item_id = wc_get_order_item_meta( $refund_item_id, '_refunded_item_id', true);
        //$order_product_id = wc_get_order_item_meta( $order_item_id, '_product_id', true);
        $transactions = explode( ',' , wc_get_order_item_meta( $order_item_id, 'mepr_transactions', true));

        foreach($transactions as $transaction_id){
            $id = sanitize_key($transaction_id);
            $value = sanitize_key('refunded');


            $tdata = MeprTransaction::get_one($id, ARRAY_A);

            if(!empty($tdata)) {
                $txn = new MeprTransaction();
                $txn->load_data($tdata);
                $txn->status = esc_sql($value); //escape the input this way since $wpdb->escape() is depracated
                $txn->store();
            }

        }
    }
    //$data = wc_get_order_item_meta( $item_id, 'attendee_email_address', true );

    //fwrite($file, print_r($order->get_refunds() , true));
    //fwrite($file, print_r($data , true));
    /*
    foreach($order->get_items() as $item_id => $item){
        $data = wc_get_order_item_meta( $item_id, 'attendee_email_address', true );
        fwrite($file, print_r($data , true));
    }
    foreach($order->get_refunds() as $refund){
        //fwrite($file, print_r($refund, true));
    }

    */
    //fclose($file);
}

add_action('woocommerce_order_refunded', 'custom_woo_event_refund', 10, 2);

/**
 * Ajax
 */

/*
function cfwc_check_user_email(){
    global $wpdb;
    $email = $_POST['email'];
    if( ! empty($email) ){
        $result = $wpdb->get_results("SELECT * FROM `wp_users` where user_email = '".$email."'");
    }
    wp_send_json($result);
    exit();
}

add_action('wp_ajax_check_user_email', 'cfwc_check_user_email');

function cfwc_check_attendee_user_email_script(){
    if(get_the_terms($wc_pro_id, 'product_cat')[0]->name == "woo_event"){
        wp_enqueue_script('attendee_email_js', get_stylesheet_directory_uri().'/assets/js/check-user.js');
        wp_localize_script( 'attendee_email_js', 'my_ajax_obj',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

add_action('wp_enqueue_scripts', 'cfwc_check_attendee_user_email_script');