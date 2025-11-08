<?php
/**
 * General Settings Template
 */
?>
<div class="nmp-wp-settings-section">
    <h3><?php esc_html_e('API Configuration', 'netease-mini-player-wp'); ?></h3>
    
    <div class="nmp-wp-form-group">
        <label class="nmp-wp-label"><?php esc_html_e('API Endpoint', 'netease-mini-player-wp'); ?></label>
        <input type="url" class="regular-text" 
               name="nmp_wp_api_endpoint" 
               value="<?php echo esc_attr(get_option('nmp_wp_api_endpoint')); ?>">
        <p class="description"><?php esc_html_e('The API endpoint for Netease Cloud Music service', 'netease-mini-player-wp'); ?></p>
    </div>

    <div class="nmp-wp-form-group">
        <label class="nmp-wp-label"><?php esc_html_e('Default Player Type', 'netease-mini-player-wp'); ?></label>
        <select name="nmp_wp_default_type" class="nmp-wp-select">
            <option value="playlist" <?php selected('playlist', get_option('nmp_wp_default_type')); ?>>
                <?php esc_html_e('Playlist', 'netease-mini-player-wp'); ?>
            </option>
            <option value="song" <?php selected('song', get_option('nmp_wp_default_type')); ?>>
                <?php esc_html_e('Single Song', 'netease-mini-player-wp'); ?>
            </option>
        </select>
    </div>

    <div class="nmp-wp-switch-group">
        <label class="nmp-wp-switch">
            <input type="checkbox" name="nmp_wp_enable_shortcode" value="1" 
                <?php checked('1', get_option('nmp_wp_enable_shortcode')); ?>>
            <span class="nmp-wp-switch-slider"></span>
        </label>
        <div class="nmp-wp-switch-label">
            <strong><?php esc_html_e('Enable Shortcode', 'netease-mini-player-wp'); ?></strong>
            <p><?php esc_html_e('Allow using [netease_player] shortcode in posts/pages', 'netease-mini-player-wp'); ?></p>
        </div>
    </div>

    <div class="nmp-wp-switch-group">
        <label class="nmp-wp-switch">
            <input type="checkbox" name="nmp_wp_enable_widget" value="1" 
                <?php checked('1', get_option('nmp_wp_enable_widget')); ?>>
            <span class="nmp-wp-switch-slider"></span>
        </label>
        <div class="nmp-wp-switch-label">
            <strong><?php esc_html_e('Enable Widget', 'netease-mini-player-wp'); ?></strong>
            <p><?php esc_html_e('Show player widget in sidebar areas', 'netease-mini-player-wp'); ?></p>
        </div>
    </div>
</div>
