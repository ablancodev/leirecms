<?php 
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$post_id = filter_input(INPUT_POST, 'post_id');
$post_id = intval($_GET['post_id']);
$db = getDbInstance();


// Delete a user using user_id
if ($post_id) {
    
    $db->where('id', $post_id);
    $post = $db->getOne('posts');

    if ( !$post ) {
        header('location: posts.php');
        exit;
    }

     // Parser
     try {
        $template = file_get_contents( './templates/post.html' );
    
        if ($template === false) {
            // Handle the error
            echo "No se ha podido cargar la plantilla.";
            exit;
        }

        $template = str_replace( '{{title}}', $post['title'], $template );
        $template = str_replace( '{{content}}', $post['content'], $template) ;
        
        file_put_contents( './outputs/' . $post['slug'] . '.html', $template );

        echo '<p>';

    } catch (Exception $e) {
        // Handle exception
        echo "Algo no fué bien.";
    }


} else {
    echo "Necesita indicar el id de página.";
}