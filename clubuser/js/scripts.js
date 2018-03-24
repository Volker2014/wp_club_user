function wpMediaEditor() {
    wp.media.editor.open();
    wp.media.editor.send.attachment = function(props, attachment) {
        jQuery('input.clubuser_avatar').val(attachment.id);
        jQuery('div.clubuser_avatar img').remove();
        jQuery('div.clubuser_avatar').append(
            jQuery('<img>').attr({
                'src': attachment.sizes.thumbnail.url,
                'alt': attachment.title
            })
        );
        jQuery('button.clubuser-avatar-remove').fadeIn(250);
    };
}

function clubUserAvatar() {
    var buttonAdd = jQuery('button.clubuser-avatar-add');
    var buttonRemove = jQuery('button.clubuser-avatar-remove');

    buttonAdd.on('click', function(event) {
        event.preventDefault();
        wpMediaEditor();
    });

    buttonRemove.on('click', function(event) {
        event.preventDefault();
        jQuery('input.clubuser_avatar').val(0);
        jQuery('div.clubuser_avatar img').remove();
        jQuery(this).fadeOut(250);
    });

    jQuery(document).on('click', 'div.clubuser_avatar img', function() {
        wpMediaEditor();
    });

    if(
        jQuery('input.clubuser_avatar').val() === 0
        || !jQuery('div.clubuser_avatar img').length
    ) buttonRemove.css( 'display', 'none' );
}

jQuery(document).ready(function() {
    clubUserAvatar();
});