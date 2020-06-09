//режим без конфликта, чтобы использовать $
jQuery(document).ready(function ($) {
    $('.xz-favorites-link a').click(function (e) {
        let action = $(this).data('type');
        $.ajax({
            type: "POST",
            // url: '/wp-admin/admin-ajax.php',
            url: xzFavorites.url,
            data: {
                security: xzFavorites.nonce,
                action: 'xz_'+action,
                postId: xzFavorites.postId,
            },
            beforeSend: function(){
               $('.xz-favorites-link a').fadeOut(300, function () {
                    $('.xz-favorites-link .xz-favorites-hidden').fadeIn();
               });
            },
            success: function (res) {
                $('.xz-favorites-link .xz-favorites-hidden').fadeOut(300, function () {
                    $('.xz-favorites-link').html(res);
                    if(action == 'del') {
                        $('.xz-ul-widget').find('li.xz-item-'+ xzFavorites.postId).remove();
                    }
                });
            },
            error: function () {
                alert('error');
            }
        });
        e.preventDefault();
    });

});