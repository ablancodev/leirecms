<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Sanitize if you want
$db = getDbInstance();

// Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Get input data
    $data_to_db = filter_input_array(INPUT_POST);

    if ( is_array( $data_to_db ) && ( sizeof( $data_to_db ) > 0 ) ) {
        foreach ( $data_to_db as $key => $data ) {
            $db->where('name', $key);
            if ($db->has("settings")) {
                $stat = $db->update( 'settings', array( 'value' => $data ) );
            } else {
                $stat = $db->insert ('settings', array( 'name' => $key, 'value' => $data ));
            }
        }
    }
    

    if ($stat)
    {
        $_SESSION['success'] = 'Settings updated successfully!';
        // Redirect to the listing page
        header('Location: settings.php');
        exit();
    }
}

?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Settings</h2>
        </div>
    </div>
    <!-- Flash messages -->
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>
    <form class="form" action="" method="post" id="settings_form" enctype="multipart/form-data">
        <?php include BASE_PATH.'/forms/settings_form.php'; ?>
    </form>
</div>
<?php include BASE_PATH.'/includes/footer.php'; ?>
