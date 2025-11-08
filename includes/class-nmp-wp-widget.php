<?php
class NMP_WP_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'netease_mini_player_widget',
            '网易云音乐播放器',
            array(
                'description' => '在侧边栏显示网易云音乐播放器',
                'classname' => 'netease-mini-player-widget'
            )
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $player_args = array(
            'id' => $instance['playlist_id'],
            'type' => $instance['player_type'],
            'theme' => $instance['theme'],
            'lyric' => $instance['show_lyric'] ? 'true' : 'false',
            'autoplay' => $instance['autoplay'] ? 'true' : 'false',
            'volume' => $instance['volume'] / 100,
            'position' => 'static',
            'embed' => 'false',
            'width' => '100%',
            'height' => '140px',
            'class' => 'nmp-wp-widget-player'
        );
        
        echo NMP_WP_Utils::generate_player_html($player_args);
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $defaults = array(
            'title' => '网易云音乐',
            'playlist_id' => '',
            'player_type' => 'playlist',
            'theme' => 'auto',
            'show_lyric' => true,
            'autoplay' => false,
            'volume' => 70
        );
        
        $instance = wp_parse_args($instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
            <input type="text" class="widefat" 
                   id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" 
                   value="<?php echo esc_attr($instance['title']); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('playlist_id'); ?>">歌单/歌曲 ID：</label>
            <input type="text" class="widefat" 
                   id="<?php echo $this->get_field_id('playlist_id'); ?>" 
                   name="<?php echo $this->get_field_name('playlist_id'); ?>" 
                   value="<?php echo esc_attr($instance['playlist_id']); ?>"
                   placeholder="例如：14273792576">
            <small>支持歌单ID、歌曲ID或网易云链接</small>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('player_type'); ?>">播放器类型：</label>
            <select class="widefat" 
                    id="<?php echo $this->get_field_id('player_type'); ?>" 
                    name="<?php echo $this->get_field_name('player_type'); ?>">
                <option value="playlist" <?php selected($instance['player_type'], 'playlist'); ?>>歌单播放器</option>
                <option value="song" <?php selected($instance['player_type'], 'song'); ?>>单曲播放器</option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('theme'); ?>">主题：</label>
            <select class="widefat" 
                    id="<?php echo $this->get_field_id('theme'); ?>" 
                    name="<?php echo $this->get_field_name('theme'); ?>">
                <option value="auto" <?php selected($instance['theme'], 'auto'); ?>>自动</option>
                <option value="light" <?php selected($instance['theme'], 'light'); ?>>浅色</option>
                <option value="dark" <?php selected($instance['theme'], 'dark'); ?>>深色</option>
            </select>
        </p>
        
        <p>
            <label>
                <input type="checkbox" 
                       id="<?php echo $this->get_field_id('show_lyric'); ?>" 
                       name="<?php echo $this->get_field_name('show_lyric'); ?>" 
                       value="1" <?php checked($instance['show_lyric'], true); ?>>
                显示歌词
            </label>
        </p>
        
        <p>
            <label>
                <input type="checkbox" 
                       id="<?php echo $this->get_field_id('autoplay'); ?>" 
                       name="<?php echo $this->get_field_name('autoplay'); ?>" 
                       value="1" <?php checked($instance['autoplay'], true); ?>>
                自动播放
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('volume'); ?>">默认音量：<?php echo esc_html($instance['volume']); ?>%</label>
            <input type="range" class="widefat" 
                   id="<?php echo $this->get_field_id('volume'); ?>" 
                   name="<?php echo $this->get_field_name('volume'); ?>" 
                   min="0" max="100" value="<?php echo esc_attr($instance['volume']); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['playlist_id'] = !empty($new_instance['playlist_id']) ? NMP_WP_Utils::sanitize_input($new_instance['playlist_id'], 'id') : '';
        $instance['player_type'] = !empty($new_instance['player_type']) ? sanitize_text_field($new_instance['player_type']) : 'playlist';
        $instance['theme'] = !empty($new_instance['theme']) ? sanitize_text_field($new_instance['theme']) : 'auto';
        $instance['show_lyric'] = !empty($new_instance['show_lyric']);
        $instance['autoplay'] = !empty($new_instance['autoplay']);
        $instance['volume'] = !empty($new_instance['volume']) ? intval($new_instance['volume']) : 70;
        
        return $instance;
    }
}
?>
