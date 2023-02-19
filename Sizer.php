<?php
/*
Plugin Name: Sizer
Description: Sizer
Version:     1.00
Author:      Patryk Organiściak
Author URI:  https://keyweb.pl
*/




function fiu_upload_file(){
    try {
        if (isset($_FILES['file2'])) {
            $filename = $_FILES["file2"]["name"];
            $url = wp_upload_bits($_FILES["file2"]["name"], null, file_get_contents($_FILES["file2"]["tmp_name"]));

            $post_id = wp_insert_post(array(
                'post_title' => $filename,
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => $filename,
            ));
            $product = wc_get_product($post_id);
            $product->set_sku($filename);
            $product->set_regular_price(30);
//            wc_rest_set_uploaded_image_as_attachment([$url['url'],$url['url']], $post_id);
            $product->save();
            $desc    = $filename;
            $image = media_sideload_image( $url['url'], $post_id, $desc,'id' );

            set_post_thumbnail( $post_id, $image );

            global $woocommerce;
            $woocommerce->cart->add_to_cart($post_id, 1);
            echo wc_get_cart_url();

        } else {
            echo "try again :>";
        }
    }
    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    exit();
}

add_action('wp_ajax_fiu_upload_file', 'fiu_upload_file');
add_action('wp_ajax_nopriv_fiu_upload_file', 'fiu_upload_file');


