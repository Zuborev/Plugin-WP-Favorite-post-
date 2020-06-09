<?php

function xz_favorites_dashboard_widget() {
    wp_add_dashboard_widget('xz_favorites_dashboard', 'Ваш список избранного',
                            'xz_show_dashboard_widget');
}
function xz_show_dashboard_widget(){
    $user = wp_get_current_user();
    $favorites = get_user_meta($user->ID, 'xz_favorites');
    $favorites = array_reverse($favorites);
    if (!$favorites) {
        echo 'Список пуст';
        return;
    }
    $img_src = plugins_url('img/lazyload.gif', __FILE__);

    /*
    $str = implode(',', $favorites);
    $xz_posts = get_posts(['include' => $str]);
    */

    echo '<ul class="xz-ul-widget">';
    foreach ($favorites as $favorite) {
        echo '<li class="xz-item xz-item-'.$favorite.'">
                <a href="'. get_permalink($favorite) . '"  target="_blank">'
                .get_the_title($favorite).'</a>
                <span><a class="xz-favorites-del" href="#" data-post = "'.$favorite.'">&#10008;</a></span>
                <span class="xz-favorites-hidden"><img src="' . $img_src . '" 
                alt=""></span>
              </li>';
    }
    echo '</ul>';
    echo '<div class="xz-favorites-del-all"><button class="button" id="xz-favorites-del-all">Очистить список</button><span class="xz-favorites-hidden"><img src="' . $img_src . '"
                alt=""></span></div>';

}

function xz_show_dashboard_front_widget(){
    $user = wp_get_current_user();
    $favorites = get_user_meta($user->ID, 'xz_favorites');
    $favorites = array_reverse($favorites);
    if (!$favorites) {
        echo 'Список пуст';
        return;
    }

    echo '<ul class="xz-ul-widget">';
    foreach ($favorites as $favorite) {
        echo '<li class="xz-item xz-item-'.$favorite.'">
              <a href="'. get_permalink($favorite) . '"  target="_blank">' .get_the_title($favorite).'</a></li>';
    }
    echo '</ul>';

}

function xz_favorites_content($content)
{
    if (!is_single() || !is_user_logged_in()) {
        return $content;
    }
    $img_src = plugins_url('img/lazyload.gif', __FILE__);

    global $post;
    if(xz_is_favorites($post->ID)) {
        return '<p class="xz-favorites-link"><span class="xz-favorites-hidden"><img src="'.$img_src.'" 
                alt=""></span><a data-type="del" href="#">Удалить из избранного</a></p>' . $content;

    }
    return '<p class="xz-favorites-link"><span class="xz-favorites-hidden"><img src="' . $img_src . '" 
                alt=""></span><a data-type="add" href="#">Добавить в избранное</a></p>' . $content;
}

function xz_favorites_admin_scripts($hook) {
    if ($hook != 'index.php') return;
    wp_enqueue_script('xz-favorites-admin-scripts', plugins_url('/js/xz-favorites-admin-scripts.js', __FILE__),
        ['jquery'], null, true);
    wp_enqueue_style('xz-favorites-admin-style', plugins_url('/css/xz-favorites-admin-style.css', __FILE__));
    wp_localize_script('xz-favorites-admin-scripts', 'xzFavorites', ['nonce' => wp_create_nonce('xz-favorites')]);

}


function xz_favorites_scripts()
{
    if (!is_user_logged_in()) return;

    wp_enqueue_script('xz-favorites-scripts', plugins_url('/js/xz-favorites-scripts.js', __FILE__),
        ['jquery'], null, true);
    wp_enqueue_style('xz-favorites-style', plugins_url('/css/xz-favorites-style.css', __FILE__));
    global $post;
    wp_localize_script('xz-favorites-scripts', 'xzFavorites', ['url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('xz-favorites'), 'postId'=>$post->ID] );
}

function wp_ajax_xz_add()
{
    if ( !wp_verify_nonce($_POST['security'], 'xz-favorites')) {
        wp_die('Ошибка безопасности');
    }
    $post_id = (int)$_POST['postId'];
    $user = wp_get_current_user();

    if(xz_is_favorites($post_id)) wp_die();

    if (add_user_meta($user->ID, 'xz_favorites', $post_id)) {
        wp_die('Добавлено');
    }
    wp_die('Ошибка добавления');
}

function wp_ajax_xz_del_all()
{
    if ( !wp_verify_nonce($_POST['security'], 'xz-favorites')) {
        wp_die('Ошибка безопасности');
    }
    $user = wp_get_current_user();
    if (delete_metadata('user',$user->ID, 'xz_favorites')) {
        wp_die('Список очищен');
    } else {
        wp_die('Ошибка удаления');
    }
}


function wp_ajax_xz_del()
{
    if ( !wp_verify_nonce($_POST['security'], 'xz-favorites')) {
        wp_die('Ошибка безопасности');
    }
    $post_id = (int)$_POST['postId'];
    $user = wp_get_current_user();

    if( !xz_is_favorites($post_id) ) wp_die();

    if (delete_user_meta($user->ID, 'xz_favorites', $post_id)) {
        wp_die('Удалено');
    }
    wp_die('Ошибка удаления');
}

function xz_is_favorites($post_id) {
    $user = wp_get_current_user();
    $favorites = get_user_meta($user->ID, 'xz_favorites');

    foreach ($favorites as $favorite) {
        if ($favorite == $post_id) return true;
    }
    return false;
}

