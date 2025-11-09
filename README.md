
<img src="https://raw.githubusercontent.com/LingMowen/NeteaseMiniPlayer-WP/refs/heads/main/NMPWP.png" alt="Version">
<p align="center">
  <img src="https://img.shields.io/badge/Version-2.0.9-4158ff?style=flat-square" alt="Version">
  <img src="https://img.shields.io/badge/WordPress-Plugin-ff5722?style=flat-square&logo=wordpress" alt="WordPress">
  <img src="https://img.shields.io/badge/License-Apache--2.0-4caf50?style=flat-square" alt="License">
</p>

<h1 align="center">NeteaseMiniPlayer-WP</h1>
<p align="center">
  基于 <a href="https://github.com/numakkiyu/NeteaseMiniPlayer">NeteaseMiniPlayer v2</a> 修改的 WordPress 插件<br>
  新拟态设计 · 短代码 / 浮动 / 小工具 · 自动深色模式
</p>

<p align="center">
  <a href="https://nmp.hypcvgm.top/" target="_blank">
    <img src="https://img.shields.io/badge/_NMP v2_项目演示网站-00c853?style=flat-square&logo=netease-cloud-music&logoColor=white" alt="Demo">
  </a>
</p>


---

## 功能特性
- 支持在所有页面显示“浮动播放器”，可固定在四个角落，支持缩小模式与播放列表展开。
- 提供短代码 `[netease_player]` 与别名 `[nmp_player]`，支持在文章/页面插入歌单或单曲播放器。
- 提供侧边栏小部件（Widget），可在外观 → 小工具中添加播放器。
- 自动适配主题：`auto` 跟随系统/站点深浅色；也可强制 `light` 或 `dark`。
- 播放控制：播放/暂停、上一首/下一首、进度条、音量调节、歌词显示/隐藏、循环模式、播放列表面板。
- 智能 ID 提取：短代码支持直接写网易云链接，自动解析出 ID。
- 管理工具：ID 提取工具；（可扩展）缓存清理与 API 测试按钮。
- 数据缓存：基于 WordPress Transients，减少 API 请求次数，提升响应速度。
- 国际化：提供 `languages/netease-mini-player-wp.pot`，可扩展翻译。

## 环境要求
- WordPress：5.0 或更高版本（建议 5.8+）。
- PHP：7.2 或更高版本。
- 支持 HTTPS 外部请求（用于访问网易云音乐 API 代理）。

## 安装与升级
1. 下载本仓库后，将整个目录 `NeteaseMiniPlayer-WP-main` 放置到站点的 `wp-content/plugins/` 下，并重命名为 `netease-mini-player-wp`（建议）。
2. 登录 WordPress 后台 → 插件，启用“Netease Mini Player WP”。
3. 后台菜单将出现“网易云播放器”，进入可进行设置。
4. 升级：用新版本覆盖旧版本目录即可（如有重大变更，建议备份设置）。

## 快速上手
- 在文章中插入基本歌单播放器：
  ```markdown
  [netease_player id="14273792576"]
  ```
- 插入单曲播放器：
  ```markdown
  [netease_player id="1901371647" type="song"]
  ```
- 固定在页面右下角的浮动播放器（文章内嵌入方式）：
  ```markdown
  [netease_player id="14273792576" position="bottom-right"]
  ```
- 选择深色主题：
  ```markdown
  [netease_player id="14273792576" theme="dark"]
  ```
- 嵌入模式（更简洁、隐藏部分控制按钮）：
  ```markdown
  [netease_player id="14273792576" embed="true"]
  ```

> 提示：`id` 支持数字 ID 或完整的网易云音乐链接，插件会自动解析。

## 短代码使用
- 支持短代码标签：
  - 主标签：`[netease_player ...]`
  - 别名：`[nmp_player ...]`
- 可用于文章、页面或主题模板：
  - 在模板中：`echo do_shortcode('[netease_player id="14273792576"]');`

### 示例组合
- 基本歌单播放器：
  ```markdown
  [netease_player id="14273792576"]
  ```
- 单曲播放器：
  ```markdown
  [netease_player id="1901371647" type="song"]
  ```
- 浮动定位：
  ```markdown
  [netease_player id="14273792576" position="top-left"]
  ```
- 主题切换：
  ```markdown
  [netease_player id="14273792576" theme="auto"]
  [netease_player id="14273792576" theme="light"]
  [netease_player id="14273792576" theme="dark"]
  ```
- 自动播放与音量（0–100）：
  ```markdown
  [netease_player id="14273792576" autoplay="true" volume="60"]
  ```
- 自定义尺寸：
  ```markdown
  [netease_player id="14273792576" width="500px" height="150px"]
  ```

## 参数说明
短代码支持以下参数（来自 `includes/utils.php::get_supported_parameters` 与前端实现）：

- `id`：歌单/歌曲 ID 或链接（必填）。
- `type`：`playlist` 或 `song`（默认 `playlist`）。
- `theme`：`auto` / `light` / `dark`（默认 `auto`）。
- `lyric`：`true` / `false` 是否显示歌词（默认 `true`）。
- `autoplay`：`true` / `false` 是否自动播放（默认 `false`）。
- `volume`：音量 0–100（默认 `70`）。
- `position`：播放器位置：`static`、`top-left`、`top-right`、`bottom-left`、`bottom-right`（默认 `static`）。
- `embed`：`true` / `false` 嵌入模式（默认 `false`）。
- `width`：播放器宽度（默认 `400px`）。
- `height`：播放器高度（默认 `120px`）。

实现细节：
- 渲染为 `<div class="netease-mini-player" ...>`，并通过前端脚本 `assets/js/netease-mini-player-v2.js` 自动初始化。
- 数据属性示例：`data-playlist-id="14273792576"` 或 `data-song-id="1901371647"`、`data-theme="auto"`、`data-lyric="true"`、`data-autoplay="false"`、`data-volume="0.7"` 等。

## 浮动播放器
- 插件在后台提供“启用浮动播放器”与“浮动位置”的设置，启用后将在所有页面通过 `wp_footer` 输出一个全局播放器。
- 默认位置：右下角；可切换四角定位。
- 缩小/展开：支持缩小为圆形按钮，点击可展开播放器。
- 相关代码：`netease-mini-player-wp.php::output_floating_player()`。

## 侧边栏小部件
- 后台 → 外观 → 小工具，添加“网易云音乐播放器”。
- 小部件选项：
  - 标题、ID（支持链接自动解析）、类型（歌单/单曲）、主题、是否显示歌词、自动播放、默认音量等。
- 渲染通过 `includes/class-nmp-wp-widget.php` 与 `NMP_WP_Utils::generate_player_html()` 完成。

## 后台设置
- 插件菜单：`网易云播放器`（图标：`dashicons-format-audio`）。
- 设置项（经典设置页 `netease-mini-player-wp.php`）：
  - 启用浮动播放器、浮动位置（四角）、默认歌单 ID。
  - API 端点配置（默认：`https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php`）。
  - 启用短代码功能。
- 模板化设置页（`templates/settings-*.php` 与 `assets/js/admin.js`）：提供更美观的标签页、预览、工具面板（部分站点可能尚未启用该模板渲染）。

## API 与网络
- 默认 API 代理：`https://api.hypcvgm.top/NeteaseMiniPlayer/nmp.php`。
- 可在后台修改为自有代理地址，建议使用稳定、低延迟的服务。
- 前端脚本会通过 `fetch` 请求 API（在 NMP v2 JS 中默认常量也指向该地址）。
- 若站点防火墙或 CSP 限制外部请求，请为该域名放行。

## 缓存与性能
- 缓存实现：基于 WordPress Transients（`set_transient/get_transient`），键前缀 `nmp_wp_cache_*`。
- 缓存时长：API 响应 5 分钟；歌单信息 1 小时；歌曲信息 30 分钟。
- 清理缓存：
  - 代码层面：`NMP_WP_Utils::clear_all_cache()` 会批量清理相关 transient 与选项。
  - 后台工具页提供“清除缓存”按钮（如启用相应的 AJAX 处理）。
- 调试：启用 `WP_DEBUG` 时，前台右下角会显示资源加载与播放器数量信息（`includes/debug.php`）。

## i18n
- 文本域：`netease-mini-player-wp`。
- 词条模板：`languages/netease-mini-player-wp.pot`。
- 加载方式：`includes/class-nmp-wp-i18n.php::load_textdomain()`。

## 故障排查
- 无法播放或加载很慢：检查 API 代理可用性；在后台切换到稳定可用的代理地址。
- 短代码无效：确认后台已勾选“启用短代码功能”；检查输入的 `id` 是否正确或尝试直接使用数字 ID。
- 样式/脚本未加载：确保主题的 `wp_head()` 与 `wp_footer()` 钩子正常；检查插件是否已启用；打开 `WP_DEBUG` 查看右下角调试信息。
- 浮动播放器未显示：确认已启用并设置了有效的“默认歌单 ID”。

## 常见问题
- 支持哪些链接？
  - 支持标准的网易云歌单、单曲、专辑、歌手链接以及移动端链接与短链接，插件会尝试自动提取 ID。
- 可以强制深色/浅色吗？
  - 可以，使用 `theme="dark"` 或 `theme="light"`；`auto` 会跟随系统/站点。
- 支持自定义大小吗？
  - 支持，使用 `width` 与 `height` 参数（如 `500px`/`120px`）。


## 贡献指南
- Issue：欢迎在 GitHub 提交问题/建议（见插件头部 `Plugin URI`）。
- 开发：代码结构主要包含：
  - 插件入口与设置：`netease-mini-player-wp.php`
  - 管理 UI 模板与样式：`templates/`、`assets/css/admin.css`、`assets/js/admin.js`
  - 前端播放器组件：`assets/js/netease-mini-player-v2.js`、`assets/css/netease-mini-player-v2.css`
  - 短代码处理与工具：`includes/player-shortcode.php`、`includes/utils.php`
  - 小部件：`includes/class-nmp-wp-widget.php`
  - 调试信息：`includes/debug.php`
- 代码风格：遵循 WordPress 开发规范，注意转义与过滤（如 `esc_attr/sanitize_text_field`）。

## 许可证
- 本项目基于 Apache 2.0 开源许可证发布，详情见 [LICENSE](./LICENSE)。

---

### 附注：实现差异
- 当前代码同时存在两套后台设置实现：
  - 经典设置页（直接通过 `netease-mini-player-wp.php` 注册与渲染）。
  - 模板化设置页（`templates/settings-*.php` + `assets/js/admin.js`），提供更丰富的 UI 与工具，但在部分站点可能还未启用该模板渲染。
- 管理工具中的“清除缓存 / API 测试”按钮需要对应的 AJAX 处理器：
  - `nmp_wp_clear_cache` 与 `nmp_wp_test_api` 可基于 `NMP_WP_Utils::clear_all_cache()` 与 `NMP_WP_Utils::test_api_connection()` 实现。
- 前端与后端 API 端点存在两处来源（后端选项与前端常量），建议统一以后台设置为准，并在 JS 中通过 `wp_localize_script` 注入。
- 当前项目基于NeteaseMiniPlayer v2 实现，NeteaseMiniPlayer v2项目代码目前处于更新当中。
