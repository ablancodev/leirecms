<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Sanitize if you want
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_STRING); 
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

// Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Get post id form query string parameter.
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_STRING);

    // Get input data for posts ddbb table
    $data_to_db_post = array();
    $data_to_db_post['title'] = filter_input(INPUT_POST, 'title');
    $data_to_db_post['slug'] = filter_input(INPUT_POST, 'slug');
    $data_to_db_post['content'] = filter_input(INPUT_POST, 'content');
    // Insert user and timestamp
    $data_to_db_post['author_id'] = $_SESSION['user_id'];
    $data_to_db_post['updated_at'] = date('Y-m-d H:i:s');

    $db = getDbInstance();
    $db->where('id', $post_id);
    $stat = $db->update('posts', $data_to_db_post);

    if ($stat)
    {
        // Custom field from form
        $data_acf = filter_input_array(INPUT_POST);
        unset($data_acf['title']);
        unset($data_acf['slug']);
        unset($data_acf['content']);

        foreach ( $data_acf as $key=>$acf ) {
            $db = getDbInstance();
            $db->where('name', $key);
            $db->where('post_id', $post_id);
            if ($db->has("posts_meta")) {
                $stat = $db->update('posts_meta', array('value' => $acf));
            } else {
                $stat = $db->insert('posts_meta', array('post_id' => $post_id, 'name' => $key, 'value' => $acf));
            }
        }

        // Ahora los $_FILES, que no son procesados por filter_input
        if ( $_FILES && is_array( $_FILES ) ) {
            $dir_subida = './uploads/';
            foreach ( $_FILES as $key => $file ) {
                $fichero_subido = $dir_subida . basename($file['name']);
                $stat = move_uploaded_file($file['tmp_name'], $fichero_subido);
                if ( $stat ) {
                    $db = getDbInstance();
                    $db->where('name', $key);
                    $db->where('post_id', $post_id);
                    if ($db->has("posts_meta")) {
                        $stat = $db->update('posts_meta', array('value' => $fichero_subido));
                    } else {
                        $stat = $db->insert('posts_meta', array('post_id' => $post_id, 'name' => $key, 'value' => $fichero_subido));
                    }
                }
            }
        }

        if ( $stat ) {
            $_SESSION['success'] = 'Post updated successfully!';
        } else {
            $_SESSION['failure'] = 'Something went wrong!';
        }
        // Redirect to the listing post
        header('Location: posts.php');
        // Important! Don't execute the rest put the exit/die.
        exit();
    }
}

// If edit variable is set, we are performing the update operation.
if ($edit)
{
    $db->where('id', $post_id);
    // Get data to pre-populate the form.
    $post = $db->getOne('posts');
}
?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Update Post</h2>
        </div>
    </div>
    <!-- Flash messages -->
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>
    <form class="form" action="" method="post" id="post_form" enctype="multipart/form-data">
        <?php include BASE_PATH.'/forms/post_form.php'; ?>
    </form>
</div>
<?php include BASE_PATH.'/includes/footer.php'; ?>
