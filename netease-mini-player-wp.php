<?php
/**
 * Plugin Name: Netease Mini Player WP
 * Plugin URI: https://github.com/numakkiyu/NeteaseMiniPlayer
 * Description: 网易云音乐浮动播放器插件，支持在所有页面显示浮动播放器
 * Version: 2.1.0
 * Author: 凌墨问
 * License: Apache 2.0
 * Text Domain: netease-mini-player-wp
 */

defined('ABSPATH') || exit;

// 定义插件常量
define('NMP_WP_VERSION', '2.1.0');
define('NMP_WP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NMP_WP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NMP_WP_PLUGIN_BASENAME', plugin_basename(__FILE__));

class NeteaseMiniPlayer_WP {
    
    private static $instance = null;
    private $options;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        $this->options = get_option('netease_mini_player_settings');
        
        // 初始化设置
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // 前端资源
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // 核心功能：浮动播放器
        add_action('wp_footer', array($this, 'output_floating_player'));
        
        // 附加功能：短代码
        add_shortcode('netease_player', array($this, 'shortcode_handler'));
        
        // 小部件功能
        add_action('widgets_init', array($this, 'register_widget'));
    }
    
    public function register_settings() {
        register_setting('netease_mini_player_settings', 'netease_mini_player_settings', array(
            'sanitize_callback' => array($this, 'sanitize_settings')
        ));
        
        // 浮动播放器设置
        add_settings_section(
            'nmp_floating_section',
            '浮动播放器设置',
            array($this, 'floating_section_callback'),
            'netease-mini-player-settings'
        );
        
        add_settings_field(
            'enable_floating_player',
            '启用浮动播放器',
            array($this, 'enable_floating_player_callback'),
            'netease-mini-player-settings',
            'nmp_floating_section'
        );
        
        add_settings_field(
            'floating_position',
            '浮动位置',
            array($this, 'floating_position_callback'),
            'netease-mini-player-settings',
            'nmp_floating_section'
        );
        
        add_settings_field(
            'default_playlist_id',
            '默认歌单ID',
            array($this, 'default_playlist_id_callback'),
            'netease-mini-player-settings',
            'nmp_floating_section'
        );
        
        // 基本设置
        add_settings_section(
            'nmp_general_section',
            '基本设置',
            array($this, 'general_section_callback'),
            'netease-mini-player-settings'
        );
        
        add_settings_field(
            'api_endpoint',
            'API端点',
            array($this, 'api_endpoint_callback'),
            'netease-mini-player-settings',
            'nmp_general_section'
        );
        
        add_settings_field(
            'enable_shortcode',
            '启用短代码功能',
            array($this, 'enable_shortcode_callback'),
            'netease-mini-player-settings',
            'nmp_general_section'
        );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            '网易云播放器设置',
            '网易云播放器',
            'manage_options',
            'netease-mini-player-settings',
            array($this, 'settings_page'),
            'dashicons-format-audio',
            30
        );
    }
    
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>网易云音乐浮动播放器设置</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('netease_mini_player_settings');
                do_settings_sections('netease-mini-player-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function floating_section_callback() {
        echo '<p>配置全局浮动播放器设置，播放器将显示在所有页面的指定位置</p>';
    }
    
    public function enable_floating_player_callback() {
        $enabled = isset($this->options['enable_floating_player']) ? $this->options['enable_floating_player'] : '1';
        ?>
        <label>
            <input type="checkbox" name="netease_mini_player_settings[enable_floating_player]" value="1" <?php checked('1', $enabled); ?>>
            在所有页面显示浮动播放器
        </label>
        <p class="description">启用后，播放器将固定显示在页面角落</p>
        <?php
    }
    
    public function floating_position_callback() {
        $position = isset($this->options['floating_position']) ? $this->options['floating_position'] : 'bottom-right';
        ?>
        <select name="netease_mini_player_settings[floating_position]">
            <option value="bottom-right" <?php selected('bottom-right', $position); ?>>右下角</option>
            <option value="bottom-left" <?php selected('bottom-left', $position); ?>>左下角</option>
            <option value="top-right" <?php selected('top-right', $position); ?>>右上角</option>
            <option value="top-left" <?php selected('top-left', $position); ?>>左上角</option>
        </select>
        <p class="description">选择浮动播放器的显示位置</p>
        <?php
    }
    
    public function default_playlist_id_callback() {
        $playlist_id = isset($this->options['default_playlist_id']) ? $this->options['default_playlist_id'] : '14273792576';
        ?>
        <input type="text" name="netease_mini_player_settings[default_playlist_id]" 
               value="<?php echo esc_attr($playlist_id); ?>" class="regular-text">
        <p class="description">默认播放的歌单ID，支持数字ID或网易云链接</p>
        <?php
    }
    
    public function general_section_callback() {
        echo '<p>基本功能设置，短代码功能为文章嵌入提供支持</p>';
    }
    
    public function api_endpoint_callback() {
        $endpoint = isset($this->options['api_endpoint']) ? $this->options['api_endpoint'] : 'https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php';
        ?>
        <input type="url" name="netease_mini_player_settings[api_endpoint]" 
               value="<?php echo esc_attr($endpoint); ?>" class="regular-text">
        <p class="description">网易云音乐API代理地址</p>
        <?php
    }
    
    public function enable_shortcode_callback() {
        $enabled = isset($this->options['enable_shortcode']) ? $this->options['enable_shortcode'] : '1';
        ?>
        <label>
            <input type="checkbox" name="netease_mini_player_settings[enable_shortcode]" value="1" <?php checked('1', $enabled); ?>>
            启用短代码功能
        </label>
        <p class="description">启用后可以在文章中使用 [netease_player] 短代码</p>
        <?php
    }
    
    public function sanitize_settings($input) {
        $sanitized = array();
        
        if (isset($input['enable_floating_player'])) {
            $sanitized['enable_floating_player'] = '1';
        } else {
            $sanitized['enable_floating_player'] = '0';
        }
        
        if (isset($input['floating_position'])) {
            $sanitized['floating_position'] = sanitize_text_field($input['floating_position']);
        }
        
        if (isset($input['default_playlist_id'])) {
            $sanitized['default_playlist_id'] = sanitize_text_field($input['default_playlist_id']);
        }
        
        if (isset($input['api_endpoint'])) {
            $sanitized['api_endpoint'] = esc_url_raw($input['api_endpoint']);
        }
        
        if (isset($input['enable_shortcode'])) {
            $sanitized['enable_shortcode'] = '1';
        } else {
            $sanitized['enable_shortcode'] = '0';
        }
        
        return $sanitized;
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'netease-mini-player-css',
            NMP_WP_PLUGIN_URL . 'assets/css/netease-mini-player-v2.css',
            array(),
            NMP_WP_VERSION
        );
        
        wp_enqueue_script(
            'netease-mini-player-js',
            NMP_WP_PLUGIN_URL . 'assets/js/netease-mini-player-v2.js',
            array('jquery'),
            NMP_WP_VERSION,
            true
        );
        
        wp_localize_script('netease-mini-player-js', 'nmpSettings', array(
            'apiUrl' => isset($this->options['api_endpoint']) ? $this->options['api_endpoint'] : 'https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php',
            'enableFloating' => isset($this->options['enable_floating_player']) ? $this->options['enable_floating_player'] : '1'
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_netease-mini-player-settings' === $hook) {
            wp_enqueue_style(
                'nmp-admin-css',
                NMP_WP_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                NMP_WP_VERSION
            );
        }
    }
    
    // 核心功能：输出浮动播放器
    public function output_floating_player() {
        if (!isset($this->options['enable_floating_player']) || $this->options['enable_floating_player'] !== '1') {
            return;
        }
        
        $playlist_id = isset($this->options['default_playlist_id']) ? $this->options['default_playlist_id'] : '14273792576';
        $position = isset($this->options['floating_position']) ? $this->options['floating_position'] : 'bottom-right';
        
        $id = $this->extract_id_from_input($playlist_id, 'playlist');
        
        if (empty($id)) {
            return;
        }
        
        $player_html = $this->render_player($id, array(
            'type' => 'playlist',
            'position' => $position,
            'theme' => 'auto',
            'lyric' => 'true',
            'autoplay' => 'false',
            'embed' => 'false'
        ));
        
        echo $player_html;
    }
    
    // 附加功能：短代码处理
    public function shortcode_handler($atts) {
        if (!isset($this->options['enable_shortcode']) || $this->options['enable_shortcode'] !== '1') {
            return '<div class="nmp-notice">短代码功能已禁用</div>';
        }
        
        $atts = shortcode_atts(array(
            'id' => '',
            'type' => 'playlist',
            'theme' => 'auto',
            'lyric' => 'true',
            'autoplay' => 'false',
            'position' => 'static',
            'embed' => 'false'
        ), $atts, 'netease_player');
        
        $id = $this->extract_id_from_input($atts['id'], $atts['type']);
        
        if (empty($id)) {
            return '<div class="nmp-error">无效的网易云音乐ID或链接</div>';
        }
        
        return $this->render_player($id, $atts);
    }
    
    private function render_player($id, $options) {
        $data_attrs = array(
            'data-' . $options['type'] . '-id' => esc_attr($id),
            'data-theme' => esc_attr($options['theme']),
            'data-lyric' => esc_attr($options['lyric']),
            'data-autoplay' => esc_attr($options['autoplay']),
            'data-position' => esc_attr($options['position']),
            'data-embed' => esc_attr($options['embed']),
            'class' => 'netease-mini-player'
        );
        
        $attrs_string = '';
        foreach ($data_attrs as $key => $value) {
            $attrs_string .= ' ' . $key . '="' . $value . '"';
        }
        
        return '<div' . $attrs_string . '></div>';
    }
    
    private function extract_id_from_input($input, $type) {
        if (empty($input)) {
            return '';
        }
        
        if (is_numeric($input)) {
            return $input;
        }
        
        $patterns = array(
            'playlist' => '/music\.163\.com.*[?&]id=(\d+)/',
            'song' => '/music\.163\.com.*song.*[?&]id=(\d+)/'
        );
        
        if (isset($patterns[$type]) && preg_match($patterns[$type], $input, $matches)) {
            return $matches[1];
        }
        
        if (preg_match('/[?&]id=(\d+)/', $input, $matches)) {
            return $matches[1];
        }
        
        return '';
    }
    
    public function register_widget() {
        require_once NMP_WP_PLUGIN_PATH . 'includes/class-nmp-wp-widget.php';
        register_widget('Netease_Mini_Player_Widget');
    }
    
    public function activate() {
        $default_options = array(
            'enable_floating_player' => '1',
            'floating_position' => 'bottom-right',
            'default_playlist_id' => '14273792576',
            'api_endpoint' => 'https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php',
            'enable_shortcode' => '1'
        );
        
        if (get_option('netease_mini_player_settings') === false) {
            add_option('netease_mini_player_settings', $default_options);
        }
    }
    
    public function deactivate() {
        // 清理工作
    }
}

// 初始化插件
NeteaseMiniPlayer_WP::get_instance();
