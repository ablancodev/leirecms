<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Serve POST method, After successful insert, redirect to posts.php post.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    $data_to_db = array_filter($_POST);

    // Insert user and timestamp
    $data_to_db['author_id'] = $_SESSION['user_id'];
    $data_to_db['created_at'] = date('Y-m-d H:i:s');

    $db = getDbInstance();
    $last_id = $db->insert('posts', $data_to_db);

    if ($last_id)
    {
        $_SESSION['success'] = 'Post added successfully!';
        // Redirect to the listing post
        header('Location: posts.php');
        // Important! Don't execute the rest put the exit/die.
    	exit();
    }
    else
    {
        echo 'Insert failed: ' . $db->getLastError();
        exit();
    }
}

// We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;
?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Add Post</h2>
        </div>
    </div>
    <!-- Flash messages -->
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>
    <form class="form" action="" method="post" id="post_form" enctype="multipart/form-data">
        <?php include BASE_PATH.'/forms/post_form.php'; ?>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
   $('#post_form').validate({
       rules: {
            title: {
                required: true,
                minlength: 3
            },
            slug: {
                required: true,
                minlength: 3
            },   
        }
    });
});
</script>
<?php include BASE_PATH.'/includes/footer.php'; ?>
