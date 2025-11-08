<?php
class NMP_WP_I18n {
    
    public static function init() {
        add_action('plugins_loaded', array(__CLASS__, 'load_textdomain'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'localize_admin_scripts'));
    }
    
    public static function load_textdomain() {
        load_plugin_textdomain(
            'netease-mini-player-wp',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }
    
    public static function localize_admin_scripts() {
        wp_localize_script('nmp-wp-admin-js', 'nmp_wp_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nmp_wp_admin_nonce'),
            'i18n' => array(
                'testing' => __('测试中...', 'netease-mini-player-wp'),
                'success' => __('成功', 'netease-mini-player-wp'),
                'error' => __('错误', 'netease-mini-player-wp'),
                'confirm_clear_cache' => __('确定要清除所有缓存吗？', 'netease-mini-player-wp')
            )
        ));
    }
    
    public static function get_translated_strings() {
        return array(
            'settings' => __('设置', 'netease-mini-player-wp'),
            'appearance' => __('外观', 'netease-mini-player-wp'),
            'advanced' => __('高级', 'netease-mini-player-wp'),
            'tools' => __('工具', 'netease-mini-player-wp'),
            'api_endpoint' => __('API 端点', 'netease-mini-player-wp'),
            'default_theme' => __('默认主题', 'netease-mini-player-wp'),
            'show_lyrics' => __('显示歌词', 'netease-mini-player-wp'),
            'autoplay' => __('自动播放', 'netease-mini-player-wp'),
            'volume' => __('音量', 'netease-mini-player-wp'),
            'position' => __('位置', 'netease-mini-player-wp'),
            'enable_shortcode' => __('启用短代码', 'netease-mini-player-wp'),
            'enable_widget' => __('启用小部件', 'netease-mini-player-wp'),
            'preview' => __('预览', 'netease-mini-player-wp'),
            'extract_id' => __('提取 ID', 'netease-mini-player-wp'),
            'test_api' => __('测试 API', 'netease-mini-player-wp'),
            'clear_cache' => __('清除缓存', 'netease-mini-player-wp')
        );
    }
}
?>
