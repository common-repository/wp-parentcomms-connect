<div class="misc-pub-section misc-pub-section-last" style="padding-left:0px">
<h3>Send to ParentComms mobile app</h3>
</div>
<div class="misc-pub-section misc-pub-section-last ui-opencheck">
    <input type="checkbox" id="push_staff" name="push_staff" <?php  if( @get_post_meta($post->ID, 'push_staff', true)) echo 'checked' ?> />    
    <label for="push_staff">Push to staff</label>    
</div>
<div class="misc-pub-section misc-pub-section-last ui-opencheck">
    <input type="checkbox" id="push_opencheck" name="push_opencheck" <?php if( @get_post_meta($post->ID, 'push_opencheck', true)) echo 'checked' ?> />
    <label for="push_opencheck">Push to OpenCheck subscribers</label>    
</div>