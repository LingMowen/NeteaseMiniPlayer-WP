<?php
class NMP_WP_Player_Shortcode {
    
    public static function init() {
        add_shortcode('netease_player', array(__CLASS__, 'handle_shortcode'));
        add_shortcode('nmp_player', array(__CLASS__, 'handle_shortcode')); // 添加别名
        add_action('wp_ajax_nmp_wp_extract_id', array(__CLASS__, 'ajax_extract_id'));
        add_action('wp_ajax_nopriv_nmp_wp_extract_id', array(__CLASS__, 'ajax_extract_id'));
        
        // 确保资源加载
        add_action('wp_enqueue_scripts', array(__CLASS__, 'ensure_assets_loaded'));
    }
    
    /**
     * 确保资源被加载
     */
    public static function ensure_assets_loaded() {
        // 如果检测到短代码，强制加载资源
        global $post;
        if ($post && (has_shortcode($post->post_content, 'netease_player') || 
                      has_shortcode($post->post_content, 'nmp_player'))) {
            self::load_assets();
        }
    }
    
    /**
     * 加载播放器资源
     */
    public static function load_assets() {
        wp_enqueue_style(
            'netease-mini-player-css',
            NMP_WP_PLUGIN_URL . 'assets/css/netease-mini-player-v2.css',
            array(),
            NMP_WP_VERSION
        );
        
        wp_enqueue_script(
            'netease-mini-player-js',
            NMP_WP_PLUGIN_URL . 'assets/js/netease-mini-player-v2.js',
            array(),
            NMP_WP_VERSION,
            true
        );
    }
    
    public static function handle_shortcode($atts) {
        // 确保资源加载
        self::load_assets();
        
        $atts = shortcode_atts(array(
            'id' => '',
            'type' => 'playlist',
            'theme' => get_option('nmp_wp_default_theme', 'auto'),
            'lyric' => get_option('nmp_wp_default_lyric', 'true'),
            'autoplay' => get_option('nmp_wp_default_autoplay', 'false'),
            'volume' => get_option('nmp_wp_default_volume', '70'),
            'position' => get_option('nmp_wp_default_position', 'static'),
            'embed' => 'false',
            'width' => '400px',
            'height' => '120px',
            'class' => ''
        ), $atts, 'netease_player');
        
        // 智能提取ID：支持链接或纯ID
        $id = self::extract_id_from_input($atts['id'], $atts['type']);
        
        if (empty($id)) {
            return '<div class="nmp-wp-error" style="padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                        <strong>错误：</strong>请输入有效的网易云音乐歌单ID或链接
                    </div>';
        }
        
        // 验证ID格式
        if (!self::validate_id($id, $atts['type'])) {
            return '<div class="nmp-wp-error" style="padding: 10px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; border-radius: 4px;">
                        <strong>警告：</strong>ID格式可能不正确，请检查输入
                    </div>';
        }
        
        // 构建数据属性
        $data_attrs = array(
            'data-' . $atts['type'] . '-id' => esc_attr($id),
            'data-theme' => esc_attr($atts['theme']),
            'data-lyric' => esc_attr($atts['lyric']),
            'data-autoplay' => esc_attr($atts['autoplay']),
            'data-volume' => esc_attr($atts['volume'] / 100),
            'data-position' => esc_attr($atts['position']),
            'data-embed' => esc_attr($atts['embed']),
            'style' => sprintf('width: %s; height: %s;', 
                esc_attr($atts['width']), 
                esc_attr($atts['height']))
        );
        
        $class = 'netease-mini-player';
        if (!empty($atts['class'])) {
            $class .= ' ' . esc_attr($atts['class']);
        }
        $data_attrs['class'] = $class;
        
        // 构建属性字符串
        $attrs_string = '';
        foreach ($data_attrs as $key => $value) {
            $attrs_string .= sprintf(' %s="%s"', $key, $value);
        }
        
        return sprintf('<div%s></div>', $attrs_string);
    }
    
    /**
     * 智能提取ID：支持链接和纯ID
     */
    public static function extract_id_from_input($input, $type = 'playlist') {
        if (empty($input)) {
            return '';
        }
        
        $input = trim($input);
        
        // 如果输入的是纯数字，直接返回
        if (is_numeric($input)) {
            return $input;
        }
        
        // 定义各种网易云链接模式
        $patterns = array(
            'playlist' => array(
                // 标准歌单链接
                '/music\.163\.com.*playlist\?id=(\d+)/i',
                '/music\.163\.com.*playlist\/(\d+)/i',
                '/y\.music\.163\.com.*playlist\/(\d+)/i',
                '/y\.music\.163\.com.*m\/playlist\?id=(\d+)/i',
                // 移动端链接
                '/music\.163\.com.*#\/playlist\?id=(\d+)/i',
                '/music\.163\.com.*#\/m\/playlist\?id=(\d+)/i',
                // 短链接
                '/163\.fm\/([a-zA-Z0-9]+)/i',
                '/c\.163\.com\/([a-zA-Z0-9]+)/i'
            ),
            'song' => array(
                // 标准歌曲链接
                '/music\.163\.com.*song\?id=(\d+)/i',
                '/music\.163\.com.*song\/(\d+)/i',
                '/y\.music\.163\.com.*song\/(\d+)/i',
                '/y\.music\.163\.com.*m\/song\?id=(\d+)/i',
                // 移动端链接
                '/music\.163\.com.*#\/song\?id=(\d+)/i',
                '/music\.163\.com.*#\/m\/song\?id=(\d+)/i'
            ),
            'album' => array(
                '/music\.163\.com.*album\?id=(\d+)/i',
                '/music\.163\.com.*album\/(\d+)/i',
                '/y\.music\.163\.com.*album\/(\d+)/i'
            ),
            'artist' => array(
                '/music\.163\.com.*artist\?id=(\d+)/i',
                '/music\.163\.com.*artist\/(\d+)/i',
                '/y\.music\.163\.com.*artist\/(\d+)/i'
            )
        );
        
        // 首先尝试匹配指定类型
        if (isset($patterns[$type])) {
            foreach ($patterns[$type] as $pattern) {
                if (preg_match($pattern, $input, $matches)) {
                    return $matches[1];
                }
            }
        }
        
        // 如果指定类型没匹配到，尝试所有类型
        foreach ($patterns as $pattern_type => $type_patterns) {
            foreach ($type_patterns as $pattern) {
                if (preg_match($pattern, $input, $matches)) {
                    return $matches[1];
                }
            }
        }
        
        // 如果还是没匹配到，尝试提取URL参数中的id
        if (strpos($input, '?') !== false) {
            $query_string = parse_url($input, PHP_URL_QUERY);
            if ($query_string) {
                parse_str($query_string, $params);
                if (isset($params['id']) && is_numeric($params['id'])) {
                    return $params['id'];
                }
            }
        }
        
        // 最后尝试直接提取数字
        if (preg_match('/(\d{6,})/', $input, $matches)) {
            return $matches[1];
        }
        
        return $input; // 如果无法提取，返回原输入
    }
    
    /**
     * 验证ID格式
     */
    public static function validate_id($id, $type = 'playlist') {
        if (!is_numeric($id)) {
            return false;
        }
        
        $id_length = strlen((string)$id);
        
        // 根据类型验证ID长度范围
        $valid_ranges = array(
            'playlist' => array('min' => 8, 'max' => 12),
            'song' => array('min' => 6, 'max' => 10),
            'album' => array('min' => 6, 'max' => 10),
            'artist' => array('min' => 4, 'max' => 8)
        );
        
        if (isset($valid_ranges[$type])) {
            $range = $valid_ranges[$type];
            return $id_length >= $range['min'] && $id_length <= $range['max'];
        }
        
        // 默认验证：ID长度在4-12位之间
        return $id_length >= 4 && $id_length <= 12;
    }
    
    public static function ajax_extract_id() {
        check_ajax_referer('nmp_wp_admin_nonce', 'nonce');
        
        $url = isset($_POST['url']) ? sanitize_text_field($_POST['url']) : '';
        $result = array();
        
        if (empty($url)) {
            wp_send_json_error('请输入网易云音乐链接');
        }
        
        // 尝试提取各种类型的ID
        $types = array('playlist', 'song', 'album', 'artist');
        $extracted_id = null;
        $extracted_type = null;
        
        foreach ($types as $type) {
            $id = self::extract_id_from_input($url, $type);
            if ($id && self::validate_id($id, $type)) {
                $extracted_id = $id;
                $extracted_type = $type;
                break;
            }
        }
        
        if ($extracted_id) {
            $shortcode = '[netease_player id="' . $extracted_id . '" type="' . $extracted_type . '"]';
            $message = sprintf(
                '<div class="nmp-wp-extract-result success">' .
                '<strong>成功提取%s ID:</strong> <code>%s</code><br>' .
                '<strong>短代码:</strong> <code>%s</code><br>' .
                '<small>复制上面的短代码到文章或页面中即可使用</small>' .
                '</div>',
                ucfirst($extracted_type),
                $extracted_id,
                esc_html($shortcode)
            );
            
            wp_send_json_success($message);
        } else {
            wp_send_json_error('无法从链接中提取有效的ID，请检查链接格式是否正确。支持的格式：<br>' .
                '- 歌单链接: https://music.163.com/#/playlist?id=123456789<br>' .
                '- 歌曲链接: https://music.163.com/#/song?id=123456789<br>' .
                '- 直接输入ID: 123456789');
        }
    }
    
    /**
     * 获取短代码使用示例
     */
    public static function get_usage_examples() {
        return array(
            array(
                'title' => '基本歌单播放器',
                'description' => '使用歌单ID或链接',
                'examples' => array(
                    '[netease_player id="14273792576"]',
                    '[netease_player id="https://music.163.com/#/playlist?id=14273792576"]'
                )
            ),
            array(
                'title' => '单曲播放器',
                'description' => '使用歌曲ID或链接',
                'examples' => array(
                    '[netease_player id="1901371647" type="song"]',
                    '[netease_player id="https://music.163.com/#/song?id=1901371647" type="song"]'
                )
            ),
            array(
                'title' => '浮动播放器',
                'description' => '固定在页面角落',
                'examples' => array(
                    '[netease_player id="14273792576" position="bottom-right"]',
                    '[netease_player id="14273792576" position="top-left"]'
                )
            ),
            array(
                'title' => '自定义主题',
                'description' => '选择不同的主题样式',
                'examples' => array(
                    '[netease_player id="14273792576" theme="dark"]',
                    '[netease_player id="14273792576" theme="light"]',
                    '[netease_player id="14273792576" theme="auto"]'
                )
            ),
            array(
                'title' => '嵌入模式',
                'description' => '简洁的嵌入样式',
                'examples' => array(
                    '[netease_player id="14273792576" embed="true"]',
                    '[netease_player id="1901371647" type="song" embed="true"]'
                )
            )
        );
    }
}
?>
