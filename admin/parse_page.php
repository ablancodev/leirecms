<?php 
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$page_id = filter_input(INPUT_POST, 'page_id');
$page_id = intval($_GET['page_id']);
$db = getDbInstance();


// Delete a user using user_id
if ($page_id) {
    
    $db->where('id', $page_id);
    $page = $db->getOne('pages');

    if ( !$page ) {
        header('location: pages.php');
        exit;
    }

     // Parser
     try {
        $template = file_get_contents( './templates/page.html' );
    
        if ($template === false) {
            // Handle the error
            echo "error";
            exit;
        }

        $template = str_replace( '{{title}}', $page['title'], $template );
        $template = str_replace( '{{content}}', $page['content'], $template) ;
        
        file_put_contents( './outputs/' . $page['slug'] . '.html', $template );

    } catch (Exception $e) {
        // Handle exception
        echo "vaya errror";
    }


} else {
    echo "Necesita indicar el id de página.";
}