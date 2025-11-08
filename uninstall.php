<?php
/**
 * Netease Mini Player WP 插件卸载脚本
 */

// 确保通过 WordPress 卸载
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// 清理选项
$options_to_remove = array(
    'nmp_wp_api_endpoint',
    'nmp_wp_default_theme',
    'nmp_wp_default_lyric',
    'nmp_wp_default_autoplay',
    'nmp_wp_default_volume',
    'nmp_wp_default_position',
    'nmp_wp_enable_shortcode',
    'nmp_wp_enable_widget',
    'nmp_wp_cache_duration'
);

foreach ($options_to_remove as $option) {
    delete_option($option);
}

// 清理 transient 缓存
global $wpdb;
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
        '_transient_nmp_wp_cache_%',
        '_transient_timeout_nmp_wp_cache_%'
    )
);

// 清理自定义表（如果有的话）
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}nmp_wp_cache");

// 清理用户 meta（如果有的话）
// $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'nmp_wp_%'");

// 记录卸载日志
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Netease Mini Player WP 插件已卸载 - 清理完成');
}
?>
