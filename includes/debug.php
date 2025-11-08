<?php
class NMP_WP_Debug {
    
    public static function init() {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('wp_footer', array(__CLASS__, 'output_debug_info'));
            add_action('admin_footer', array(__CLASS__, 'output_debug_info'));
        }
    }
    
    public static function output_debug_info() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '
        <div style="position: fixed; bottom: 10px; right: 10px; background: #f0f0f0; padding: 10px; border: 1px solid #ccc; z-index: 99999; font-size: 12px;">
            <strong>NMP Debug Info</strong><br>
            Plugin URL: ' . NMP_WP_PLUGIN_URL . '<br>
            CSS Loaded: ' . (wp_style_is('netease-mini-player-css') ? 'Yes' : 'No') . '<br>
            JS Loaded: ' . (wp_script_is('netease-mini-player-js') ? 'Yes' : 'No') . '<br>
            Players Found: <span id="nmp-debug-player-count">0</span>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var players = document.querySelectorAll(".netease-mini-player");
            document.getElementById("nmp-debug-player-count").textContent = players.length;
            console.log("NMP Debug: Found", players.length, "players");
        });
        </script>
        ';
    }
}

// 初始化调试
NMP_WP_Debug::init();
?>
