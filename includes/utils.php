<?php
class NMP_WP_Utils {
    
    /**
     * 获取支持的参数配置
     */
    public static function get_supported_parameters() {
        return array(
            'id' => array(
                'type' => 'string',
                'description' => '歌单或歌曲 ID',
                'required' => true
            ),
            'type' => array(
                'type' => 'string',
                'description' => '类型：playlist（歌单）或 song（单曲）',
                'default' => 'playlist',
                'options' => array('playlist', 'song')
            ),
            'theme' => array(
                'type' => 'string',
                'description' => '主题：auto（自动）、light（浅色）、dark（深色）',
                'default' => 'auto',
                'options' => array('auto', 'light', 'dark')
            ),
            'lyric' => array(
                'type' => 'boolean',
                'description' => '是否显示歌词',
                'default' => 'true'
            ),
            'autoplay' => array(
                'type' => 'boolean',
                'description' => '是否自动播放',
                'default' => 'false'
            ),
            'volume' => array(
                'type' => 'number',
                'description' => '音量（0-100）',
                'default' => '70'
            ),
            'position' => array(
                'type' => 'string',
                'description' => '播放器位置',
                'default' => 'static',
                'options' => array('static', 'top-left', 'top-right', 'bottom-left', 'bottom-right')
            ),
            'embed' => array(
                'type' => 'boolean',
                'description' => '嵌入模式',
                'default' => 'false'
            ),
            'width' => array(
                'type' => 'string',
                'description' => '播放器宽度',
                'default' => '400px'
            ),
            'height' => array(
                'type' => 'string',
                'description' => '播放器高度',
                'default' => '120px'
            )
        );
    }
    
    /**
     * 生成短代码示例
     */
    public static function generate_shortcode_examples() {
        return array(
            array(
                'title' => '基本歌单播放器',
                'shortcode' => '[netease_player id="14273792576"]',
                'description' => '显示指定歌单的播放器'
            ),
            array(
                'title' => '单曲播放器',
                'shortcode' => '[netease_player id="1901371647" type="song"]',
                'description' => '播放单首歌曲'
            ),
            array(
                'title' => '深色主题播放器',
                'shortcode' => '[netease_player id="14273792576" theme="dark"]',
                'description' => '使用深色主题'
            ),
            array(
                'title' => '自动播放',
                'shortcode' => '[netease_player id="14273792576" autoplay="true"]',
                'description' => '页面加载后自动播放'
            ),
            array(
                'title' => '嵌入模式',
                'shortcode' => '[netease_player id="14273792576" embed="true"]',
                'description' => '嵌入模式，隐藏控制按钮'
            ),
            array(
                'title' => '浮动播放器',
                'shortcode' => '[netease_player id="14273792576" position="bottom-right"]',
                'description' => '固定在右下角的浮动播放器'
            ),
            array(
                'title' => '自定义尺寸',
                'shortcode' => '[netease_player id="14273792576" width="500px" height="150px"]',
                'description' => '自定义播放器尺寸'
            )
        );
    }
    
    /**
     * 从网易云链接中提取 ID
     */
    public static function extract_id_from_url($url, $type = 'playlist') {
        if (empty($url)) {
            return '';
        }
        
        // 如果输入的是纯数字，直接返回
        if (is_numeric($url)) {
            return $url;
        }
        
        $patterns = array(
            'playlist' => array(
                '/music\.163\.com.*playlist\?id=(\d+)/i',
                '/music\.163\.com.*playlist\/(\d+)/i',
                '/y\.music\.163\.com.*playlist\/(\d+)/i'
            ),
            'song' => array(
                '/music\.163\.com.*song\?id=(\d+)/i',
                '/music\.163\.com.*song\/(\d+)/i',
                '/y\.music\.163\.com.*song\/(\d+)/i'
            ),
            'album' => array(
                '/music\.163\.com.*album\?id=(\d+)/i',
                '/music\.163\.com.*album\/(\d+)/i'
            ),
            'artist' => array(
                '/music\.163\.com.*artist\?id=(\d+)/i',
                '/music\.163\.com.*artist\/(\d+)/i'
            )
        );
        
        foreach ($patterns as $pattern_type => $type_patterns) {
            foreach ($type_patterns as $pattern) {
                if (preg_match($pattern, $url, $matches)) {
                    return array(
                        'id' => $matches[1],
                        'type' => $pattern_type
                    );
                }
            }
        }
        
        return false;
    }
    
    /**
     * 验证网易云 ID
     */
    public static function validate_netease_id($id, $type = 'playlist') {
        if (!is_numeric($id)) {
            return false;
        }
        
        // 基本长度验证（网易云 ID 通常是数字）
        $id_length = strlen((string)$id);
        
        switch ($type) {
            case 'song':
                return $id_length >= 6 && $id_length <= 10;
            case 'playlist':
                return $id_length >= 8 && $id_length <= 12;
            case 'album':
                return $id_length >= 6 && $id_length <= 10;
            case 'artist':
                return $id_length >= 4 && $id_length <= 8;
            default:
                return $id_length >= 4 && $id_length <= 12;
        }
    }
    
    /**
     * 获取缓存键名
     */
    public static function get_cache_key($type, $id, $params = array()) {
        $key = 'nmp_wp_cache_' . $type . '_' . $id;
        
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        
        return $key;
    }
    
    /**
     * 设置缓存
     */
    public static function set_cache($key, $data, $expiration = 1800) {
        return set_transient($key, $data, $expiration);
    }
    
    /**
     * 获取缓存
     */
    public static function get_cache($key) {
        return get_transient($key);
    }
    
    /**
     * 删除缓存
     */
    public static function delete_cache($key) {
        return delete_transient($key);
    }
    
    /**
     * 清理所有插件缓存
     */
    public static function clear_all_cache() {
        global $wpdb;
        
        // 清理 transient 缓存
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                '_transient_nmp_wp_cache_%',
                '_transient_timeout_nmp_wp_cache_%'
            )
        );
        
        // 清理选项缓存
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                'nmp_wp_cache_%'
            )
        );
        
        return true;
    }
    
    /**
     * API 请求封装
     */
    public static function api_request($endpoint, $params = array()) {
        $api_base = get_option('nmp_wp_api_endpoint', 'https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php');
        $url = rtrim($api_base, '/') . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $cache_key = self::get_cache_key('api', md5($url));
        $cached_data = self::get_cache($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'user-agent' => 'NeteaseMiniPlayer-WP/2.0.9',
            'headers' => array(
                'Referer' => home_url(),
                'Origin' => home_url()
            )
        ));
        
        if (is_wp_error($response)) {
            return new WP_Error('api_request_failed', $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('json_decode_error', 'JSON 解析失败');
        }
        
        // 缓存成功的结果（5分钟）
        if (!is_wp_error($data) && isset($data['code']) && $data['code'] === 200) {
            self::set_cache($cache_key, $data, 300);
        }
        
        return $data;
    }
    
    /**
     * 测试 API 连接
     */
    public static function test_api_connection() {
        $test_data = self::api_request('/song/url/v1', array('id' => 33894312));
        
        if (is_wp_error($test_data)) {
            return array(
                'success' => false,
                'message' => 'API 连接失败: ' . $test_data->get_error_message()
            );
        }
        
        if (isset($test_data['code']) && $test_data['code'] === 200) {
            return array(
                'success' => true,
                'message' => 'API 连接正常',
                'data' => $test_data
            );
        } else {
            return array(
                'success' => false,
                'message' => 'API 返回错误: ' . ($test_data['code'] ?? '未知错误')
            );
        }
    }
    
    /**
     * 获取歌单信息
     */
    public static function get_playlist_info($playlist_id) {
        $cache_key = self::get_cache_key('playlist_info', $playlist_id);
        $cached_data = self::get_cache($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $data = self::api_request('/playlist/detail', array('id' => $playlist_id));
        
        if (is_wp_error($data)) {
            return $data;
        }
        
        if (isset($data['playlist'])) {
            $playlist_info = array(
                'id' => $data['playlist']['id'],
                'name' => $data['playlist']['name'],
                'cover' => $data['playlist']['coverImgUrl'],
                'track_count' => $data['playlist']['trackCount'],
                'play_count' => $data['playlist']['playCount'],
                'creator' => $data['playlist']['creator']['nickname']
            );
            
            // 缓存1小时
            self::set_cache($cache_key, $playlist_info, 3600);
            
            return $playlist_info;
        }
        
        return new WP_Error('playlist_not_found', '歌单不存在或无法访问');
    }
    
    /**
     * 获取歌曲信息
     */
    public static function get_song_info($song_id) {
        $cache_key = self::get_cache_key('song_info', $song_id);
        $cached_data = self::get_cache($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $data = self::api_request('/song/detail', array('ids' => $song_id));
        
        if (is_wp_error($data)) {
            return $data;
        }
        
        if (isset($data['songs']) && !empty($data['songs'])) {
            $song = $data['songs'][0];
            $song_info = array(
                'id' => $song['id'],
                'name' => $song['name'],
                'artists' => implode(', ', array_column($song['ar'], 'name')),
                'album' => $song['al']['name'],
                'cover' => $song['al']['picUrl'],
                'duration' => $song['dt']
            );
            
            // 缓存30分钟
            self::set_cache($cache_key, $song_info, 1800);
            
            return $song_info;
        }
        
        return new WP_Error('song_not_found', '歌曲不存在或无法访问');
    }
    
    /**
     * 生成播放器 HTML
     */
    public static function generate_player_html($args = array()) {
        $defaults = array(
            'id' => '',
            'type' => 'playlist',
            'theme' => 'auto',
            'lyric' => 'true',
            'autoplay' => 'false',
            'volume' => '0.7',
            'position' => 'static',
            'embed' => 'false',
            'width' => '400px',
            'height' => '120px',
            'class' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $data_attrs = array(
            'data-' . $args['type'] . '-id' => esc_attr($args['id']),
            'data-theme' => esc_attr($args['theme']),
            'data-lyric' => esc_attr($args['lyric']),
            'data-autoplay' => esc_attr($args['autoplay']),
            'data-volume' => esc_attr($args['volume']),
            'data-position' => esc_attr($args['position']),
            'data-embed' => esc_attr($args['embed']),
            'style' => sprintf('width: %s; height: %s;', 
                esc_attr($args['width']), 
                esc_attr($args['height']))
        );
        
        if (!empty($args['class'])) {
            $data_attrs['class'] = 'netease-mini-player ' . esc_attr($args['class']);
        } else {
            $data_attrs['class'] = 'netease-mini-player';
        }
        
        $attrs_string = '';
        foreach ($data_attrs as $key => $value) {
            $attrs_string .= sprintf(' %s="%s"', $key, $value);
        }
        
        return sprintf('<div%s></div>', $attrs_string);
    }
    
    /**
     * 记录日志
     */
    public static function log($message, $level = 'info') {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }
        
        $log_levels = array('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug');
        $level_index = array_search($level, $log_levels);
        
        if ($level_index === false || $level_index > array_search('info', $log_levels)) {
            return; // 只记录 info 及以上级别的日志
        }
        
        $timestamp = current_time('mysql');
        $log_message = sprintf('[%s] %s: %s', $timestamp, strtoupper($level), $message);
        
        error_log('NMP_WP: ' . $log_message);
    }
    
    /**
     * 安全过滤输入
     */
    public static function sanitize_input($input, $type = 'text') {
        switch ($type) {
            case 'id':
                return preg_replace('/[^0-9]/', '', $input);
            case 'url':
                return esc_url_raw($input);
            case 'html':
                return wp_kses_post($input);
            case 'css':
                return wp_strip_all_tags($input);
            case 'boolean':
                return filter_var($input, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return floatval($input);
            default:
                return sanitize_text_field($input);
        }
    }
}
?>
