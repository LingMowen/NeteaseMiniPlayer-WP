<?php
/**
 * Appearance Settings Template
 */
?>
<div class="nmp-wp-settings-section">
    <h3><?php esc_html_e('Player Appearance', 'netease-mini-player-wp'); ?></h3>
    
    <div class="nmp-wp-form-group">
        <label class="nmp-wp-label"><?php esc_html_e('Default Theme', 'netease-mini-player-wp'); ?></label>
        <select name="nmp_wp_default_theme" class="nmp-wp-select">
            <option value="auto" <?php selected('auto', get_option('nmp_wp_default_theme')); ?>>
                <?php esc_html_e('Auto (Follow System)', 'netease-mini-player-wp'); ?>
            </option>
            <option value="light" <?php selected('light', get_option('nmp_wp_default_theme')); ?>>
                <?php esc_html_e('Light Theme', 'netease-mini-player-wp'); ?>
            </option>
            <option value="dark" <?php selected('dark', get_option('nmp_wp_default_theme')); ?>>
                <?php esc_html_e('Dark Theme', 'netease-mini-player-wp'); ?>
            </option>
        </select>
    </div>

    <div class="nmp-wp-form-group">
        <label class="nmp-wp-label"><?php esc_html_e('Default Position', 'netease-mini-player-wp'); ?></label>
        <select name="nmp_wp_default_position" class="nmp-wp-select">
            <option value="static" <?php selected('static', get_option('nmp_wp_default_position')); ?>>
                <?php esc_html_e('Static (Follow Content)', 'netease-mini-player-wp'); ?>
            </option>
            <option value="top-left" <?php selected('top-left', get_option('nmp_wp_default_position')); ?>>
                <?php esc_html_e('Top Left Corner', 'netease-mini-player-wp'); ?>
            </option>
            <option value="top-right" <?php selected('top-right', get_option('nmp_wp_default_position')); ?>>
                <?php esc_html_e('Top Right Corner', 'netease-mini-player-wp'); ?>
            </option>
            <option value="bottom-left" <?php selected('bottom-left', get_option('nmp_wp_default_position')); ?>>
                <?php esc_html_e('Bottom Left Corner', 'netease-mini-player-wp'); ?>
            </option>
            <option value="bottom-right" <?php selected('bottom-right', get_option('nmp_wp_default_position')); ?>>
                <?php esc_html_e('Bottom Right Corner', 'netease-mini-player-wp'); ?>
            </option>
        </select>
    </div>

    <div class="nmp-wp-form-group">
        <label class="nmp-wp-label"><?php esc_html_e('Default Volume', 'netease-mini-player-wp'); ?></label>
        <div class="nmp-wp-volume-control">
            <input type="range" name="nmp_wp_default_volume" min="0" max="100" 
                   value="<?php echo esc_attr(get_option('nmp_wp_default_volume', '70')); ?>">
            <span class="nmp-wp-volume-value"><?php echo esc_html(get_option('nmp_wp_default_volume', '70')); ?>%</span>
        </div>
    </div>

    <div class="nmp-wp-switch-group">
        <label class="nmp-wp-switch">
            <input type="checkbox" name="nmp_wp_default_lyric" value="1" 
                <?php checked('1', get_option('nmp_wp_default_lyric')); ?>>
            <span class="nmp-wp-switch-slider"></span>
        </label>
        <div class="nmp-wp-switch-label">
            <strong><?php esc_html_e('Show Lyrics', 'netease-mini-player-wp'); ?></strong>
            <p><?php esc_html_e('Display lyrics when available', 'netease-mini-player-wp'); ?></p>
        </div>
    </div>

    <div class="nmp-wp-switch-group">
        <label class="nmp-wp-switch">
            <input type="checkbox" name="nmp_wp_default_autoplay" value="1" 
                <?php checked('1', get_option('nmp_wp_default_autoplay')); ?>>
            <span class="nmp-wp-switch-slider"></span>
        </label>
        <div class="nmp-wp-switch-label">
            <strong><?php esc_html_e('Autoplay', 'netease-mini-player-wp'); ?></strong>
            <p><?php esc_html_e('Note: Browsers may block autoplay', 'netease-mini-player-wp'); ?></p>
        </div>
    </div>
</div>
