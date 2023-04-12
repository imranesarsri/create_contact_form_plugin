<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<?php
/*
Plugin Name: Contact-Form
Description: This contact form adds to your WordPress website a contact form just via typing a Short Code by soufian.
Version: 1.0
Author: imrane sarsri 
Author URI: https://imrane.com
License: SSL
*/
function contact_form(){
    $content = '';
    $content .= '<h2> Countact us!</h2>';
    $content .= '<form method="post">'; 
    $content .= '<label for="first_name" class="mt-3 mb-3">First Name</label>';
    $content .= '<input type="text" name="first_name" class="w-100" placeholder="Enter your first name">';

    $content .= '<label for="last_name" class="mt-3 mb-3">Last Name</label>';
    $content .= '<input type="text" name="last_name" class="w-100" placeholder="Enter your last name">';

    $content .= '<label for="your_email" class="mt-3 mb-3">Email</label>';
    $content .= '<input type="email" name="Email" class="w-100" placeholder="Enter your email">';
    $content .= '<label for="your_sujet" class="mt-3 mb-3">Subject</label>';
    $content .= '<input type="text" name="Subject" class="w-100" placeholder="Enter your Subject">';
    $content .= '<label for="your_message" class="mt-3 mb-3">Message</label>';
    $content .= '<textarea name="Message" cols="30" rows="10" placeholder="Enter your Message"></textarea>';
    $content .= '<input type="submit" name="form_submit" value="Submit" class="w-100 mt-5">';
    $content .= '</form>';

    return $content;
}
    add_shortcode('contact-form' , 'contact_form');


// craete database


function Plugin_Activation_Hook() {

    $sql = "CREATE TABLE wp_contact_form (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `FirstName` varchar(30) NOT NULL,
        `LastName` varchar(30) NOT NULL,
        `Email` varchar(255) NOT NULL,
        `Subject` varchar(255) NOT NULL,
        `Message` text NOT NULL,
        `SentDate` timestamp NOT NULL DEFAULT current_timestamp()
    )";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
    register_activation_hook( __FILE__, 'Plugin_Activation_Hook' );

/*  
    ** END registering activation hook

    ** START registering Deactivation hook
    ** DELETE wp_contact_form WHEN THE PLUGIN IS DESACTIVATED
*/


    function Plugin_Deactivation_Hook() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_form';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

    register_deactivation_hook(__FILE__ , 'Plugin_Deactivation_Hook');







##############################################################################


if (isset( $_POST['form_submit'])){
    global $wpdb;

    $FirstName   = sanitize_text_field( $_POST["first_name"] );
    $LastName    = sanitize_text_field( $_POST["last_name"] );
    $Email   = sanitize_email( $_POST["Email"] );
    $Subject = sanitize_text_field( $_POST["Subject"] );
    $Message = esc_textarea( $_POST["Message"] );
    
    $sql = "INSERT INTO `wp_contact_form`( `FirstName`, `LastName`, `Email`, `Subject`, `Message`) 
    VALUES (
        '$FirstName', 
        '$LastName',
        '$Email', 
        '$Subject', 
        '$Message'
    )";
    


    if ($wpdb->query($sql) == true) {
        echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>message sent! </strong> Your message has been recieved thanks for contacting us .
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ';
    }else{
        echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>message failed! </strong> Your message has not recieved please try again .
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

}

##############################################################################


    function View_contact_form_submition(){
            $page_title = 'Contact form submition';
            $menu_title = 'Contact form submition';
            $capability = 'manage_options';
            $menu_slug = 'Contact_form_submition';
            $icon_url = 'https://cdn-icons-png.flaticon.com/24/9862/9862681.png';

            
            function Menu_Page_Callback(){
                    include('createTable.php');
    
            }
    
    add_menu_page(  $page_title ,  $menu_title,  $capability,  $menu_slug, 'Menu_Page_Callback' ,  $icon_url,  $position = 2 );
    
        }
    
        add_action( "admin_menu", 'View_contact_form_submition');







?>