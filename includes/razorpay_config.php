<?php
// Copy this file to includes/razorpay_config.php and fill your Razorpay credentials
// Example: define('RAZOR_KEY_ID', 'rzp_test_xxx');
//          define('RAZOR_KEY_SECRET', 'your_secret');
//          define('RAZOR_CURRENCY', 'USD');

if(!defined('RAZOR_KEY_ID')) define('RAZOR_KEY_ID', 'rzp_live_SHuOApRNfXt0ha');
if(!defined('RAZOR_KEY_SECRET')) define('RAZOR_KEY_SECRET', 'H6nt0FSvdeb7jsL46RkY7o2h');
// Razorpay expects ISO currency codes (e.g. 'INR', 'USD'). Use 'INR' for Indian Rupees.
if(!defined('RAZOR_CURRENCY')) define('RAZOR_CURRENCY', 'INR');

?>
