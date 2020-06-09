<?php
class XZ_Favorites_Widget extends WP_Widget {
    public function __construct()
    {
        $args = [
             'name' => 'Избранные записи',
            'description' => 'Выводит блок избранных записей пользователя',
        ];
        parent::__construct('xz-favorites-widget', 'Избранные записи', $args);
    }

    public function form($instance)
    {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr( $title ); ?>" id="<?php echo $this->get_field_id('title'); ?>" class="widefat">
        </p>
        <?php
    }

    public function widget($args, $instance)
    {
        if (!is_user_logged_in()) return;

        echo $args['before_widget'];
            echo $args['before_title'];
                echo $instance['title'];
            echo $args['after_title'];
        xz_show_dashboard_front_widget();
        echo $args['after_widget'];

    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }


}