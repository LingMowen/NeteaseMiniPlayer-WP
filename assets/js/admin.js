jQuery(document).ready(function($) {
    'use strict';
    
    // 标签页切换功能
    function initTabs() {
        $('.nmp-wp-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // 移除所有活动状态
            $('.nav-tab').removeClass('nav-tab-active');
            $('.nmp-wp-tab-content').removeClass('active');
            
            // 添加当前活动状态
            $(this).addClass('nav-tab-active');
            var target = $(this).attr('href');
            $(target).addClass('active');
            
            // 更新URL哈希
            if (history.pushState) {
                history.pushState(null, null, target);
            }
            
            return false;
        });
        
        // 检查URL哈希并激活对应标签页
        var hash = window.location.hash;
        if (hash && $(hash).length) {
            $('.nav-tab[href="' + hash + '"]').click();
        }
    }
    
    // 音量滑块实时显示
    function initVolumeSlider() {
        var $volumeSlider = $('input[name="nmp_wp_default_volume"]');
        var $volumeValue = $('.volume-value');
        
        $volumeSlider.on('input', function() {
            $volumeValue.text($(this).val() + '%');
            updatePreview();
        });
    }
    
    // ID 提取工具
    function initExtractTool() {
        $('#nmp_wp_extract_id').on('click', function() {
            var $input = $('#nmp_wp_url_input');
            var $result = $('#nmp_wp_extract_result');
            var url = $input.val().trim();
            
            if (!url) {
                $result.html('<div class="nmp-wp-extract-result error">请输入网易云音乐链接</div>');
                return;
            }
            
            // 显示加载状态
            $result.html('<div class="nmp-wp-extract-result"><span class="spinner is-active"></span> 正在提取ID...</div>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nmp_wp_extract_id',
                    url: url,
                    nonce: nmp_wp_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $result.html('<div class="nmp-wp-extract-result success">' + response.data + '</div>');
                    } else {
                        $result.html('<div class="nmp-wp-extract-result error">提取失败: ' + response.data + '</div>');
                    }
                },
                error: function() {
                    $result.html('<div class="nmp-wp-extract-result error">请求失败，请重试</div>');
                }
            });
        });
    }
    
    // 实时预览功能
    function initPreview() {
        updatePreview();
        
        // 监听设置变化，实时更新预览
        $('input, select').on('change input', function() {
            updatePreview();
        });
    }
    
    function updatePreview() {
        var $preview = $('#nmp-wp-preview-container');
        var settings = {
            id: '14273792576', // 默认演示歌单
            type: $('select[name="nmp_wp_default_type"]').val() || 'playlist',
            theme: $('select[name="nmp_wp_default_theme"]').val() || 'auto',
            lyric: $('input[name="nmp_wp_default_lyric"]').is(':checked') ? 'true' : 'false',
            autoplay: $('input[name="nmp_wp_default_autoplay"]').is(':checked') ? 'true' : 'false',
            volume: ($('input[name="nmp_wp_default_volume"]').val() || 70) / 100,
            position: $('select[name="nmp_wp_default_position"]').val() || 'static',
            embed: 'false',
            width: '100%',
            height: '120px'
        };
        
        var playerHtml = '<div class="netease-mini-player" ' +
            'data-' + settings.type + '-id="' + settings.id + '" ' +
            'data-theme="' + settings.theme + '" ' +
            'data-lyric="' + settings.lyric + '" ' +
            'data-autoplay="' + settings.autoplay + '" ' +
            'data-volume="' + settings.volume + '" ' +
            'data-position="' + settings.position + '" ' +
            'data-embed="' + settings.embed + '" ' +
            'style="width: ' + settings.width + '; height: ' + settings.height + ';">' +
            '</div>';
        
        $preview.html(playerHtml);
        
        // 重新初始化播放器
        if (typeof NeteaseMiniPlayer !== 'undefined') {
            NeteaseMiniPlayer.init();
        }
    }
    
    // API 测试功能
    function initApiTest() {
        $('button[name="nmp_wp_test_api"]').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var originalText = $button.text();
            
            $button.text('测试中...').prop('disabled', true);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nmp_wp_test_api',
                    nonce: nmp_wp_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotice('API 连接测试成功！', 'success');
                    } else {
                        showNotice('API 连接测试失败: ' + response.data, 'error');
                    }
                },
                error: function() {
                    showNotice('请求失败，请重试', 'error');
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        });
    }
    
    // 显示通知消息
    function showNotice(message, type) {
        var noticeClass = 'notice-' + (type || 'info');
        var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.nmp-wp-settings h1').after($notice);
        
        // 自动消失
        setTimeout(function() {
            $notice.fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
        
        // 点击关闭
        $notice.on('click', '.notice-dismiss', function() {
            $notice.remove();
        });
    }
    
    // 清除缓存功能
    function initClearCache() {
        $('button[name="nmp_wp_clear_cache"]').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var originalText = $button.text();
            
            $button.text('清除中...').prop('disabled', true);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nmp_wp_clear_cache',
                    nonce: nmp_wp_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotice('缓存清除成功！', 'success');
                    } else {
                        showNotice('缓存清除失败: ' + response.data, 'error');
                    }
                },
                error: function() {
                    showNotice('请求失败，请重试', 'error');
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        });
    }
    
    // 初始化所有功能
    function initAll() {
        initTabs();
        initVolumeSlider();
        initExtractTool();
        initPreview();
        initApiTest();
        initClearCache();
        
        // 添加键盘快捷键
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S 保存设置
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('input[name="submit"]').click();
            }
        });
    }
    
    // 初始化
    initAll();
});
