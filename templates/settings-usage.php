<?php
/**
 * Usage Instructions Template
 */
?>
<div class="nmp-wp-settings-section">
    <h3><?php esc_html_e('Shortcode Usage', 'netease-mini-player-wp'); ?></h3>
    
    <div class="nmp-wp-code-block">
        <code>[netease_player id="14273792576"]</code>
    </div>
    <p><?php esc_html_e('Basic playlist player with default settings', 'netease-mini-player-wp'); ?></p>

    <div class="nmp-wp-code-block">
        <code>[netease_player id="1901371647" type="song"]</code>
    </div>
    <p><?php esc_html_e('Single song player with custom ID', 'netease-mini-player-wp'); ?></p>

    <div class="nmp-wp-code-block">
        <code>[netease_player id="14273792576" theme="dark" position="bottom-right"]</code>
    </div>
    <p><?php esc_html_e('Dark theme player fixed at bottom right corner', 'netease-mini-player-wp'); ?></p>

    <h4><?php esc_html_e('Available Parameters:', 'netease-mini-player-wp'); ?></h4>
    <ul class="nmp-wp-params-list">
        <li><code>id</code> - <?php esc_html_e('Playlist/song ID or URL (required)', 'netease-mini-player-wp'); ?></li>
        <li><code>type</code> - <?php esc_html_e('"playlist" or "song" (default: playlist)', 'netease-mini-player-wp'); ?></li>
        <li><code>theme</code> - <?php esc_html_e('"auto", "light" or "dark" (default: auto)', 'netease-mini-player-wp'); ?></li>
        <li><code>lyric</code> - <?php esc_html_e('"true" or "false" to show/hide lyrics (default: true)', 'netease-mini-player-wp'); ?></li>
        <li><code>autoplay</code> - <?php esc_html_e('"true" or "false" (default: false)', 'netease-mini-player-wp'); ?></li>
        <li><code>position</code> - <?php esc_html_e('Position: "static", "top-left", "top-right", etc (default: static)', 'netease-mini-player-wp'); ?></li>
        <li><code>width</code> - <?php esc_html_e('Player width (e.g. "400px")', 'netease-mini-player-wp'); ?></li>
        <li><code>height</code> - <?php esc_html_e('Player height (e.g. "120px")', 'netease-mini-player-wp'); ?></li>
    </ul>

    <h3><?php esc_html_e('Widget Usage', 'netease-mini-player-wp'); ?></h3>
    <p><?php esc_html_e('Go to Appearance → Widgets and add "Netease Music Player" widget to your sidebar.', 'netease-mini-player-wp'); ?></p>
</div>
