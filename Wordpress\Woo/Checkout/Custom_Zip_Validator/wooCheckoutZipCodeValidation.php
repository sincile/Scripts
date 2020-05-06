<?php
/*
  Validator that checks billing & shipping states against the zipcode during checkout.
  Works with 5 or 9 digit US based zipcodes. Stops the checkout and prompts user
  with reasoning if it encounters

  This was created as a lot of 3rd party Woo validation plugins are becoming deprecated causing our
  backend to get invalid state/zip/county combinations.

  zippopotam.us is used for validation, but any 3rd party can be substituted. Or you can make your own ;)
*/

function zipValidate($data,$errors) {
  $billZip = $data['billing_postcode'];
  $billState = $data['billing_state'];

  //Split the zip if it's a 9 digit code
  if(strlen($billZip) >= 6){
    $billZip = explode("-",$billZip)[0];
  }
  $billZipURL = "http://api.zippopotam.us/us/".$billZip;

  $shipZip = $data['shipping_postcode'];

  //Split the zip if it's a 9 digit code
  $shipState = $data['shipping_state'];
  if(strlen($shipZip) >= 6){
    $shipZip = explode("-",$shipZip)[0];
  }
  $shipZipURL = "http://api.zippopotam.us/us/".$shipZip;

  try {
    if($billState == $shipState && $billZip==$shipZip){
      $billResponse = wp_remote_get( $billZipURL,
      array(
                  'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8',
              )
            ));
      $billJson = json_decode( $billResponse['body'],true);
      $billResState = $billJson['places'][0]['state abbreviation'];
      if (isset($billResState)) {
        if($billResState == $billState){
          //Everything's correct
          return true;
        }
        else {
          $errors->add('validation','Billing zip code does not match state');
        }
      }//end response state check
      else{
        $errors->add('validation','Invalid Billing zip code');
      }
    }//same zip/state
    else{
      //Different Zips or states check
      //Check billing first
      $billResponse = wp_remote_get( $billZipURL,
      array(
                  'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8',
              )
            ));
      $billJson = json_decode( $billResponse['body'],true);
      $billResState = $billJson['places'][0]['state abbreviation'];
      if (isset($billResState)) {
        if($billResState == $billState){
          //Everything's correct check shipping
        }
        else {
          $errors->add('validation','Billing zip code does not match state');
        }
      }//end response state check
      else{
        $errors->add('validation','Invalid billing zip code');
      }
      //Ship zip check
      $shipResponse = wp_remote_get( $shipZipURL,
      array(
                  'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8',
              )
            ));
      $shipJson = json_decode( $shipResponse['body'],true);
      $shipResState = $shipJson['places'][0]['state abbreviation'];
      if (isset($shipResState)) {
        if($shipResState == $shipState){
          //Everything's correct
          return true;
        }
        else {
          $errors->add('validation','Shipping zip code does not match state');
        }
      }//end response state check
      else{
        $errors->add('validation','Invalid shipping zip code');
      }
    }//end different zip/state check
  }//end try block
  catch( Exception $ex ){
    $errors->add( 'validation', $ex);
  }
  // Uncheck this to prevent the order from going through
  // $errors->add( 'validation', 'Blocking orders, please ignore');
}
add_action('woocommerce_after_checkout_validation', 'zipValidate',10,2);

?>
