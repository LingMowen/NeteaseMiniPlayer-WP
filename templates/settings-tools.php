<?php
/**
 * Tools Settings Template
 */
?>
<div class="nmp-wp-settings-section">
    <h3><?php esc_html_e('ID Extraction Tool', 'netease-mini-player-wp'); ?></h3>
    <p><?php esc_html_e('Paste a Netease Music link to extract playlist/song ID', 'netease-mini-player-wp'); ?></p>
    
    <div class="nmp-wp-form-group">
        <input type="text" id="nmp_wp_url_input" class="regular-text" 
               placeholder="<?php esc_attr_e('https://music.163.com/#/playlist?id=123456789', 'netease-mini-player-wp'); ?>">
        <button type="button" id="nmp_wp_extract_id" class="button button-primary">
            <?php esc_html_e('Extract ID', 'netease-mini-player-wp'); ?>
        </button>
    </div>
    
    <div id="nmp_wp_extract_result" class="nmp-wp-tool-result"></div>

    <div class="nmp-wp-settings-section">
        <h3><?php esc_html_e('Cache Management', 'netease-mini-player-wp'); ?></h3>
        <p><?php esc_html_e('Clear cached music data and API responses', 'netease-mini-player-wp'); ?></p>
        <button type="submit" name="nmp_wp_clear_cache" class="button">
            <?php esc_html_e('Clear Cache', 'netease-mini-player-wp'); ?>
        </button>
    </div>

    <div class="nmp-wp-settings-section">
        <h3><?php esc_html_e('API Connection Test', 'netease-mini-player-wp'); ?></h3>
        <p><?php esc_html_e('Test connection to Netease Music API', 'netease-mini-player-wp'); ?></p>
        <button type="submit" name="nmp_wp_test_api" class="button">
            <?php esc_html_e('Test API Connection', 'netease-mini-player-wp'); ?>
        </button>
    </div>
</div>
