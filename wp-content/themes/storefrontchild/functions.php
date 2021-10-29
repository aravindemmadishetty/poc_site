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
        'id' => 'attendee_email_adress',
        'label' => 'Email',
        'class' => 'cfwc_attendee_email_adress',
        'desc_tip' => true,
    ),
    array(
        'id' => 'address_line_1',
        'label' => 'Address Line 1',
        'class' => 'cfwc_attendee_address_line_1',
        'desc_tip' => true,
    ),
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
        'id' => 'attendee_email_adress',
        'label' => 'Email',
        'class' => 'cfwc_attendee_email_adress',
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
        wp_enqueue_script('credentials_dropdown_script', get_stylesheet_directory_uri().'/assets/js/credentials-dropdown.js');
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
    if( empty( $_POST['cfwc-title-field'] ) && empty( $_POST['cfwc-select-field'] ) ) {
    // Fails validation
    $passed = false;
    wc_add_notice( __( 'Please enter a value into the text field', 'cfwc' ), 'error' );
    }
    return $passed;
   }
//add_filter( 'woocommerce_add_to_cart_validation', 'cfwc_validate_custom_field', 10, 3 );

/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array $cart_item_data Cart item meta data.
 * @param Integer $product_id Product ID.
 * @param Integer $variation_id Variation ID.
 * @param Boolean $quantity Quantity
 */
function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
    if( ! empty( $_POST['ticket-type-credentials'] ) ) {
    // Add the item data
    $cart_item_data['ticket-type-credential'] = $_POST['ticket-type-credentials'];
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
    foreach( $item as $cart_item_key=>$values ) {
    if( isset( $values['title_field'] ) ) {
    $item->add_meta_data( __( 'Custom Field', 'cfwc' ), $values['title_field'], true );
    }
    }
   }
add_action( 'woocommerce_checkout_create_order_line_item', 'cfwc_add_custom_data_to_order', 10, 4 );