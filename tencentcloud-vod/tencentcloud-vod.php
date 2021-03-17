<?php
/**
 * Plugin Name: tencentcloud-vod
 * Plugin URI:  https://wordpress.org/plugins/tencentcloud-vod
 * Description: 通过腾讯云云点播技术，为有音视频应用相关需求的客户提供包括音视频存储管理、音视频转码处理、音视频加速播放和音视频通信服务的一站式解决方案。
 * Version: 1.0.2
 * Author: 腾讯云
 * Author URI: https://www.tencent.com/
 * Copyright (C) 2021 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
define('TENCENT_WORDPRESS_VOD_VERSION', '1.0.2');
define('TENCENT_WORDPRESS_VOD_DIR', plugin_dir_path(__FILE__));
define('TENCNET_WORDPRESS_VOD_BASENAME', plugin_basename(__FILE__));
define('TENCENT_WORDPRESS_VOD_JS_DIR', plugins_url('tencentcloud-vod') . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR);
define('TENCENT_WORDPRESS_VOD_CSS_DIR', plugins_url('tencentcloud-vod') . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR);
define('TENCENT_WORDPRESS_VOD_NAME', 'tencentcloud-vod');
define('TENCENT_WORDPRESS_VOD_SHOW_NAME', 'tencentcloud-vod');
defined('TENCENT_WORDPRESS_VOD_URL') or define('TENCENT_WORDPRESS_VOD_URL', plugins_url(TENCENT_WORDPRESS_VOD_NAME) . DIRECTORY_SEPARATOR);
defined('TENCENT_WORDPRESS_PLUGINS_COMMON_URL') or define('TENCENT_WORDPRESS_PLUGINS_COMMON_URL', TENCENT_WORDPRESS_VOD_URL . 'common' . DIRECTORY_SEPARATOR);
defined('TENCENT_WORDPRESS_PLUGINS_COMMON_CSS_URL') or define('TENCENT_WORDPRESS_PLUGINS_COMMON_CSS_URL', TENCENT_WORDPRESS_PLUGINS_COMMON_URL . 'css' . DIRECTORY_SEPARATOR);
defined('TENCENT_WORDPRESS_PLUGINS_COMMON_DIR') or define('TENCENT_WORDPRESS_PLUGINS_COMMON_DIR', TENCENT_WORDPRESS_VOD_DIR . 'common' . DIRECTORY_SEPARATOR);

if (!is_file(TENCENT_WORDPRESS_VOD_DIR . 'vendor/autoload.php')) {
    wp_die('缺少依赖文件，请确保安装了腾讯云sdk', '缺少依赖文件', array('back_link' => true));
}
require_once 'vendor/autoload.php';

use TencentCloudVod\TencentCloudVodActions;

$tencentCloudVodActions = new TencentCloudVodActions();

register_activation_hook(__FILE__, array($tencentCloudVodActions, 'activatePlugin'));

register_deactivation_hook(__FILE__, array($tencentCloudVodActions, 'deactivePlugin'));
//卸载
register_uninstall_hook(__FILE__, array(TencentCloudVodActions::class, 'uninstallPlugin'));
//插件中心初始化
add_action('init', array($tencentCloudVodActions, 'initCommonSettingPage'));

// add_action('pre_get_posts', array($tencentCloudVodActions, 'echo'));

// add_filter('wp_handle_upload_prefilter', array($tencentCloudVodActions, 'uploadVideo'));

add_action('wp_handle_upload', array($tencentCloudVodActions, 'handleUpload'), 9, 2);

//add_action('rest_prepare_attachment', array($tencentCloudVodActions, 'modifyVideo'), 9, 3);

//add_action('the_post', array($tencentCloudVodActions, 'postVideos'), 10, 2);

add_action('wp_insert_post_data', array($tencentCloudVodActions, 'insertPostData'), 11, 3);

// add_action('add_attachment', array($tecentWordpressVodActions, 'attach'));
// 添加插件设置页面
add_action('admin_menu', array($tencentCloudVodActions, 'pluginSettingPage'));
// 插件列表加入设置按钮
add_filter('plugin_action_links', array($tencentCloudVodActions, 'pluginSettingPageLinkButton'), 10, 2);
// 添加腾讯云验证码配置保存
add_action('wp_ajax_update_vod_settings', array($tencentCloudVodActions, 'updateVodSettings'));
// 测试配置是否可用
add_action('wp_ajax_check_vod_settings', array($tencentCloudVodActions, 'checkVodSettings'));
// js脚本引入
add_action('admin_enqueue_scripts', array($tencentCloudVodActions, 'loadMyScriptEnqueue'));

add_action('wp_enqueue_scripts', array($tencentCloudVodActions, 'loadScriptForPage'));


