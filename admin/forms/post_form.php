<?php
$db->where('post_type', 'post');
$acfs = $db->get('acf');

?>
<fieldset>
    <div class="form-group">
        <label for="title">Title *</label>
          <input type="text" name="title" value="<?php echo htmlspecialchars($edit ? $post['title'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="First Name" class="form-control" required="required" id = "title">
    </div> 

    <div class="form-group">
        <label for="slug">Slug *</label>
        <input type="text" name="slug" value="<?php echo htmlspecialchars($edit ? $post['slug'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Last Name" class="form-control" required="required" id="slug">
    </div>

    <div class="form-group">
        <label for="content">Content</label>
          <textarea name="content" placeholder="Content" class="form-control" id="content"><?php echo htmlspecialchars(($edit) ? $post['content'] : '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

<?php
    if ( $acfs && is_array($acfs) ) {
        foreach ( $acfs as $acf ) {

            $db_post_meta = getDbInstance();
            $db_post_meta->where('post_id', $post_id);
            $db_post_meta->where('name', $acf['name']);
            
            $post_meta = $db_post_meta->getOne('posts_meta');

            echo '
            <div class="form-group">
                <label for="content">' . $acf['title'] . '</label>
            ';
            switch ($acf['type']) {
                case 'textarea':
                    echo '
                    <textarea name="' . $acf['name'] . '" class="form-control">' . $post_meta['value'] . '</textarea>
                    ';
                    break;
                case 'image':
                    echo '
                    <input type="file" name="' . $acf['name'] . '" src="' . $post_meta['value'] . '" > 
                    ';
                    break;
                case 'text':
                default:
                    echo '
                    <input type="text" name="'. $acf['name'] . '" value="' . $post_meta['value'] . '" placeholder="" class="form-control">
                    ';
                    break;
            }
            echo '</div>';

        }
    }
?>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >Save <i class="glyphicon glyphicon-send"></i></button>
    </div>
</fieldset>
