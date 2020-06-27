<?php
// Posts template
$db->where('name', 'posts_template');
$posts_template = $db->getOne('settings');
?>

<fieldset>
    <div class="form-group">
        <label for="title">Posts template filename:</label>
        <label class="description">This file should exist on /templates folder.</label>
          <input type="text" name="posts_template" value="<?php echo htmlspecialchars($posts_template['value'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="post.html" class="form-control" id = "posts_template">
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >Save <i class="glyphicon glyphicon-send"></i></button>
    </div>
</fieldset>
