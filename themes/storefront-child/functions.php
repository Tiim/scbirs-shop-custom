<?php
// Woo Commerce
// remove some fields from billing form
// ref - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
function scbirs_custom_billing_fields( $fields = array() ) {
	unset($fields['billing_company']);
	unset($fields['billing_address_1']);
	unset($fields['billing_address_2']);
	unset($fields['billing_state']);
	unset($fields['billing_city']);
	unset($fields['billing_phone']);
	unset($fields['billing_postcode']);
	unset($fields['billing_country']);	
	return $fields;
}
add_filter('woocommerce_billing_fields','scbirs_custom_billing_fields');

// Woo Commerce
// Add fields for swimmer name to form
function scbirs_field_swimmer_name( $fields ) {
     $fields['swimmer_first_name'] = array(
        'label'     => 'Schwimmer Vorname',
		'placeholder'   => '',
		'required'  => true,
		'class'     => array('form-row-wide'),
		'clear'     => true
     );
	 $fields['swimmer_last_name'] = array(
        'label'     => 'Schwimmer Nachname',
		'placeholder'   => '',
		'required'  => true,
		'class'     => array('form-row-wide'),
		'clear'     => true
     );
     return $fields;
}
add_filter( 'woocommerce_billing_fields' , 'scbirs_field_swimmer_name' );

// Save swimmer names as 'shipping name'
function scbirs_save_fields_on_checkout( $order, $data ) {
    if ( ! isset( $_POST['swimmer_first_name']) || ! isset( $_POST['swimmer_last_name'] ) ) {
        return;
    }

    $order->set_shipping_first_name( wc_clean( $_POST['swimmer_first_name'] ) );
	$order->set_shipping_last_name( wc_clean( $_POST['swimmer_last_name'] ) );
	
    $order->save();
}
add_action( 'woocommerce_checkout_create_order', 'scbirs_save_fields_on_checkout', 11, 2 );


// Woo Commerce
// Change order comment placeholder Text
function scbirs_woocommerce_order_comments_placeholder( $fields ) {
     $fields['order']['order_comments']['placeholder'] = 'Anmerkungen zu deiner Bestellung.';

     return $fields;
}
add_filter('woocommerce_checkout_fields', 'scbirs_woocommerce_order_comments_placeholder');


// Woo Commerce
// add checkout checkbox
// ref - https://businessbloomer.com/woocommerce-additional-acceptance-checkbox-checkout/
function scbirs_add_checkout_checkbox() {
	woocommerce_form_field( 'scbirs_checkbox', array(
		'type'          => 'checkbox',
		'class'         => array('form-row privacy'),
		'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
		'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
		'required'      => true,
		'label'         => 'Ich bin Mitglied des ScBirs und habe die <a href="/so-funktionierts/">Bedingungen</a> gelesen und verstanden.',
	)); 
}
add_action( 'woocommerce_review_order_before_submit', 'scbirs_add_checkout_checkbox', 20);

// Show notice if customer does not tick
function scbirs_not_approved_checkbox() {
    if ( ! (int) isset( $_POST['scbirs_checkbox'] ) ) {
        wc_add_notice( __( 'Bitte akzeptieren sie die Bedingungen.' ), 'error' );
    }
}
add_action( 'woocommerce_checkout_process', 'scbirs_not_approved_checkbox' );