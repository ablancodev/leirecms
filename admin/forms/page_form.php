<fieldset>
    <div class="form-group">
        <label for="title">Title *</label>
          <input type="text" name="title" value="<?php echo htmlspecialchars($edit ? $page['title'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="First Name" class="form-control" required="required" id = "title">
    </div> 

    <div class="form-group">
        <label for="slug">Slug *</label>
        <input type="text" name="slug" value="<?php echo htmlspecialchars($edit ? $page['slug'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Last Name" class="form-control" required="required" id="slug">
    </div>

    <div class="form-group">
        <label for="content">Content</label>
          <textarea name="content" placeholder="Content" class="form-control" id="content"><?php echo htmlspecialchars(($edit) ? $page['content'] : '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >Save <i class="glyphicon glyphicon-send"></i></button>
    </div>
</fieldset>
