<?php
/**
 * Netease Mini Player WP - Settings Page Template
 * 
 * This template handles the display of the plugin settings page
 * with modern UI components and responsive design.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
$tabs = array(
    'general'   => __('General', 'netease-mini-player-wp'),
    'appearance' => __('Appearance', 'netease-mini-player-wp'),
    'tools'     => __('Tools', 'netease-mini-player-wp'),
    'usage'     => __('Usage', 'netease-mini-player-wp')
);
?>
<div class="wrap nmp-wp-settings">
    <!-- Header Section -->
    <div class="nmp-wp-header">
        <div class="nmp-wp-header-content">
            <div class="nmp-wp-header-icon">
                <span class="dashicons dashicons-format-audio"></span>
            </div>
            <div class="nmp-wp-header-text">
                <h1><?php esc_html_e('Netease Cloud Music Player', 'netease-mini-player-wp'); ?></h1>
                <p><?php esc_html_e('Professional music player for Netease Cloud Music', 'netease-mini-player-wp'); ?></p>
            </div>
            <div class="nmp-wp-version-badge">
                v<?php echo esc_html(NMP_WP_VERSION); ?>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <nav class="nmp-wp-tabs">
        <ul class="nav-tab-wrapper">
            <?php foreach ($tabs as $tab_key => $tab_label) : ?>
                <li>
                    <a href="?page=netease-mini-player-settings&tab=<?php echo esc_attr($tab_key); ?>" 
                       class="nav-tab <?php echo $current_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html($tab_label); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Settings Form -->
    <form method="post" action="options.php">
        <?php 
        settings_fields('nmp_wp_settings');
        do_settings_sections('netease-mini-player-settings-' . $current_tab);
        submit_button();
        ?>
    </form>

    <!-- Tab Content -->
    <div class="nmp-wp-tab-content">
        <?php
        switch ($current_tab) {
            case 'general':
                include_once NMP_WP_PLUGIN_PATH . 'templates/settings-general.php';
                break;
            case 'appearance':
                include_once NMP_WP_PLUGIN_PATH . 'templates/settings-appearance.php';
                break;
            case 'tools':
                include_once NMP_WP_PLUGIN_PATH . 'templates/settings-tools.php';
                break;
            case 'usage':
                include_once NMP_WP_PLUGIN_PATH . 'templates/settings-usage.php';
                break;
        }
        ?>
    </div>
</div>

<!-- Inline Scripts -->
<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nmp-wp-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        $('.nmp-wp-tab-content').hide();
        $($(this).attr('href')).show();
    });

    // Volume slider value display
    $('input[name="nmp_wp_default_volume"]').on('input', function() {
        $('.nmp-wp-volume-value').text($(this).val() + '%');
    });

    // ID extraction tool
    $('#nmp_wp_extract_id').on('click', function() {
        var url = $('#nmp_wp_url_input').val().trim();
        var $result = $('#nmp_wp_extract_result');
        
        if (!url) {
            $result.html('<div class="notice notice-error"><p><?php esc_html_e("Please enter a valid Netease Music URL", "netease-mini-player-wp"); ?></p></div>');
            return;
        }
        
        $result.html('<div class="notice notice-info"><p><?php esc_html_e("Extracting ID...", "netease-mini-player-wp"); ?></p></div>');
        
        $.post(ajaxurl, {
            action: 'nmp_wp_extract_id',
            url: url,
            nonce: '<?php echo wp_create_nonce("nmp_wp_extract_id"); ?>'
        }, function(response) {
            if (response.success) {
                $result.html(response.data);
            } else {
                $result.html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
            }
        }).fail(function() {
            $result.html('<div class="notice notice-error"><p><?php esc_html_e("Request failed, please try again", "netease-mini-player-wp"); ?></p></div>');
        });
    });
});
</script>
