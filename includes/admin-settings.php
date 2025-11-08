，<?php
class NeteaseMiniPlayer_Admin {

    private $options;
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = get_option('netease_mini_player_settings');
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function add_admin_menu() {
        add_menu_page(
            '网易云音乐播放器',
            '网易云音乐',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_settings_page'),
            'dashicons-format-audio',
            30
        );
    }

    public function register_settings() {
        register_setting(
            'netease_mini_player_settings_group',
            'netease_mini_player_settings',
            array($this, 'sanitize_settings')
        );

        // 浮动播放器设置
        add_settings_section(
            'nmp_floating_section',
            '浮动播放器设置',
            array($this, 'floating_section_callback'),
            $this->plugin_name
        );

        add_settings_field(
            'enable_floating_player',
            '启用浮动播放器',
            array($this, 'enable_floating_player_callback'),
            $this->plugin_name,
            'nmp_floating_section'
        );

        add_settings_field(
            'floating_position',
            '浮动位置',
            array($this, 'floating_position_callback'),
            $this->plugin_name,
            'nmp_floating_section'
        );

        add_settings_field(
            'default_playlist_id',
            '默认歌单ID',
            array($this, 'default_playlist_id_callback'),
            $this->plugin_name,
            'nmp_floating_section'
        );

        // 播放器外观
        add_settings_section(
            'nmp_appearance_section',
            '播放器外观',
            array($this, 'appearance_section_callback'),
            $this->plugin_name
        );

        add_settings_field(
            'player_theme',
            '主题风格',
            array($this, 'player_theme_callback'),
            $this->plugin_name,
            'nmp_appearance_section'
        );

        add_settings_field(
            'show_lyrics',
            '歌词显示',
            array($this, 'show_lyrics_callback'),
            $this->plugin_name,
            'nmp_appearance_section'
        );

        // 高级设置
        add_settings_section(
            'nmp_advanced_section',
            '高级设置',
            array($this, 'advanced_section_callback'),
            $this->plugin_name
        );

        add_settings_field(
            'api_endpoint',
            'API端点',
            array($this, 'api_endpoint_callback'),
            $this->plugin_name,
            'nmp_advanced_section'
        );
    }

    public function display_settings_page() {
        ?>
        <div class="wrap nmp-settings">
            <div class="nmp-header">
                <div class="nmp-header-content">
                    <h1>
                        <span class="dashicons dashicons-format-audio"></span>
                        网易云音乐播放器设置
                        <span class="nmp-version">v<?php echo $this->version; ?></span>
                    </h1>
                </div>
                
                <nav class="nmp-tabs-wrapper">
                    <a href="#nmp-floating-settings" class="nmp-tab nav-tab-active">浮动播放器</a>
                    <a href="#nmp-appearance-settings" class="nmp-tab">外观设置</a>
                    <a href="#nmp-advanced-settings" class="nmp-tab">高级设置</a>
                </nav>
            </div>

            <form method="post" action="options.php">
                <?php
                settings_fields('netease_mini_player_settings_group');
                do_settings_sections($this->plugin_name);
                submit_button('保存设置', 'primary', 'submit', false);
                ?>
            </form>

            <div class="nmp-preview-section">
                <h3><span class="dashicons dashicons-visibility"></span> 播放器预览</h3>
                <div class="nmp-preview-container">
                    <div class="nmp-player-preview">
                        <div class="nmp-preview-placeholder">
                            <p>保存设置后预览将自动更新</p>
                            <div class="nmp-spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function floating_section_callback() {
        echo '<p>配置全局浮动播放器的基本设置</p>';
    }

    public function enable_floating_player_callback() {
        $enabled = isset($this->options['enable_floating_player']) ? $this->options['enable_floating_player'] : '1';
        ?>
        <label class="nmp-switch">
            <input type="checkbox" name="netease_mini_player_settings[enable_floating_player]" value="1" <?php checked('1', $enabled); ?>>
            <span class="nmp-switch-slider"></span>
        </label>
        <span class="description">启用后将在所有页面显示浮动播放器</span>
        <?php
    }

    public function floating_position_callback() {
        $position = isset($this->options['floating_position']) ? $this->options['floating_position'] : 'bottom-right';
        ?>
        <select name="netease_mini_player_settings[floating_position]" class="nmp-select">
            <option value="bottom-right" <?php selected('bottom-right', $position); ?>>右下角</option>
            <option value="bottom-left" <?php selected('bottom-left', $position); ?>>左下角</option>
            <option value="top-right" <?php selected('top-right', $position); ?>>右上角</option>
            <option value="top-left" <?php selected('top-left', $position); ?>>左上角</option>
        </select>
        <p class="description">选择播放器在页面上的固定位置</p>
        <?php
    }

    public function default_playlist_id_callback() {
        $playlist_id = isset($this->options['default_playlist_id']) ? $this->options['default_playlist_id'] : '';
        ?>
        <input type="text" name="netease_mini_player_settings[default_playlist_id]" 
               value="<?php echo esc_attr($playlist_id); ?>" class="regular-text">
        <p class="description">输入网易云歌单ID或完整链接</p>
        <div id="nmp-id-extractor" style="margin-top: 10px;">
            <button type="button" id="nmp-extract-id" class="button button-secondary">
                <span class="dashicons dashicons-admin-links"></span> 从链接提取ID
            </button>
            <div id="nmp-extract-result" class="notice notice-info inline" style="display: none; margin-top: 10px;"></div>
        </div>
        <?php
    }

    public function appearance_section_callback() {
        echo '<p>自定义播放器的外观和显示效果</p>';
    }

    public function player_theme_callback() {
        $theme = isset($this->options['player_theme']) ? $this->options['player_theme'] : 'auto';
        ?>
        <select name="netease_mini_player_settings[player_theme]" class="nmp-select">
            <option value="auto" <?php selected('auto', $theme); ?>>自动 (跟随系统)</option>
            <option value="light" <?php selected('light', $theme); ?>>浅色主题</option>
            <option value="dark" <?php selected('dark', $theme); ?>>深色主题</option>
        </select>
        <p class="description">选择播放器的配色方案</p>
        <?php
    }

    public function show_lyrics_callback() {
        $show = isset($this->options['show_lyrics']) ? $this->options['show_lyrics'] : '1';
        ?>
        <label class="nmp-switch">
            <input type="checkbox" name="netease_mini_player_settings[show_lyrics]" value="1" <?php checked('1', $show); ?>>
            <span class="nmp-switch-slider"></span>
        </label>
        <span class="description">启用后播放歌曲时将显示歌词</span>
        <?php
    }

    public function advanced_section_callback() {
        echo '<p>高级功能设置，一般情况无需修改</p>';
    }

    public function api_endpoint_callback() {
        $endpoint = isset($this->options['api_endpoint']) ? $this->options['api_endpoint'] : 'https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php';
        ?>
        <input type="url" name="netease_mini_player_settings[api_endpoint]" 
               value="<?php echo esc_attr($endpoint); ?>" class="regular-text">
        <p class="description">网易云音乐API代理地址，除非必要请不要修改</p>
        <?php
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        
        $sanitized['enable_floating_player'] = isset($input['enable_floating_player']) ? '1' : '0';
        $sanitized['floating_position'] = sanitize_text_field($input['floating_position']);
        $sanitized['default_playlist_id'] = sanitize_text_field($input['default_playlist_id']);
        $sanitized['player_theme'] = sanitize_text_field($input['player_theme']);
        $sanitized['show_lyrics'] = isset($input['show_lyrics']) ? '1' : '0';
        $sanitized['api_endpoint'] = esc_url_raw($input['api_endpoint']);
        
        return $sanitized;
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, $this->plugin_name) === false) {
            return;
        }
        
        wp_enqueue_style(
            $this->plugin_name . '-admin',
            plugin_dir_url(__FILE__) . 'assets/css/admin.css',
            array(),
            $this->version
        );
        
        wp_enqueue_script(
            $this->plugin_name . '-admin',
            plugin_dir_url(__FILE__) . 'assets/js/admin.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_localize_script($this->plugin_name . '-admin', 'nmpAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nmp_admin_nonce')
        ));
    }
}
