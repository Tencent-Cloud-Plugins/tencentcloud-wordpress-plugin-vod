<?php
/*
 * Copyright (C) 2020 Tencent Cloud.
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

namespace TencentCloudVod;

if (!is_file(TENCENT_WORDPRESS_VOD_DIR . 'vendor/autoload.php')) {
    wp_die('缺少依赖文件，请先执行composer install', '缺少依赖文件', array('back_link' => true));
}
require_once 'vendor/autoload.php';
require_once TENCENT_WORDPRESS_PLUGINS_COMMON_DIR . 'TencentWordpressPluginsSettingActions.php';

use Exception;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\VodClient;
use Vod\VodUploadClient;
use Vod\Model\VodUploadRequest;
use TencentCloud\Vod\V20180717\Models\ApplyUploadRequest;
use TencentWordpressPluginsSettingActions;
use TencentCloud\Vod\V20180717\Models\DescribeAllClassRequest;
use \WP_Error;
use \WP_User;

class TencentCloudVodActions
{
    const TENCENT_WORDPRESS_VOD_OPTIONS = 'tencent_wordpress_vod_options';
    const TENCENT_WORDPRESS_VOD_APP_ID = 'vod_app_id';
    const TENCENT_WORDPRESS_VOD_REGISTER_APP_ID = 'vod_register_app_id';
    const TENCENT_WORDPRESS_VOD_SECRET_ID = 'secret_id';
    const TENCENT_WORDPRESS_VOD_SECRET_KEY = 'secret_key';
    const TENCENT_WORDPRESS_VOD_APP_KEY = 'vod_app_key';
    const TENCENT_WORDPRESS_VOD_REGISTER_APP_KEY = 'vod_register_app_key';
    const TENCENT_WORDPRESS_VOD_SECRET_CUSTOM = 'secret_custom';
    const TENCENT_WORDPRESS_VOD_TRANSCODE = 'transcode';
    const TENCENT_WORDPRESS_VOD_PLUGIN_TYPE = 'vod';

    private $mediaUrl;

    public static $videos = [];

    private $videoTypes = array(
        'asf' => 'video/x-ms-asf',
        'wmv' => 'video/x-ms-wmv',
        'wmx' => 'video/x-ms-wmx',
        'wm' => 'video/x-ms-wm',
        'avi' => 'video/avi',
        'divx' => 'video/divx',
        'flv' => 'video/x-flv',
        'mov' => 'video/quicktime',
        'mpeg' => 'video/mpeg',
        'mp4' => 'video/mp4',
        'ogv' => 'video/ogg',
        'webm' => 'video/webm',
        'mkv' => 'video/x-matroska',
        '3gp' => 'video/3gpp',  // Can also be audio.
        '3g2' => 'video/3gpp2'
    );

    public function uploadVideo($file)
    {
        $tecentVodOptions = self::getVodOptionsObject();

        if (!$tecentVodOptions->getActivation())
            return $file;

        if (in_array($file['type'], $this->videoTypes)) {
            $client = new VodUploadClient($tecentVodOptions->getSecretID(), $tecentVodOptions->getSecretKey());
            $req = new VodUploadRequest();
            $req->MediaFilePath = $file['tmp_name'];
            $req->MediaType = array_search($file['type'], $this->videoTypes);
            $req->Procedure = 'LongVideoPreset';
            $req->SubAppId = $tecentVodOptions->getSubAppID();
            try {
                $rsp = $client->upload("ap-guangzhou", $req);
                // echo "FileId -> ". $rsp->FileId . "\n";
                // echo "MediaUrl -> ". $rsp->MediaUrl . "\n";
                // apply_filters( 'wp_handle_upload', array(
                //     'file' => $file,
                //     'url' => $rsp->MediaUrl,
                //     'type' => 'mp4'
                // ), 'upload' );
                // return $rsp->MediaUrl;
                $this->mediaUrl = $rsp->MediaUrl;
            } catch (Exception $e) {
                // 处理上传异常
                echo $e;
            }
        }
        return $file;
    }

    public function handleUpload($upload, $context)
    {
        $tecentVodOptions = self::getVodOptionsObject();
        if (!$tecentVodOptions->getActivation())
            return $upload;
        if (in_array($upload['type'], $this->videoTypes)) {
            $client = new VodUploadClient($tecentVodOptions->getSecretID(), $tecentVodOptions->getSecretKey());
            $req = new VodUploadRequest();
            $req->MediaFilePath = $upload['file'];
            $req->MediaType = array_search($upload['type'], $this->videoTypes);
            if ($tecentVodOptions->getTranscode()) {
                $req->Procedure = 'LongVideoPreset';
            }
            $req->SubAppId = $tecentVodOptions->getSubAppID();
            try {
                $rsp = $client->upload("ap-guangzhou", $req);
                $this->mediaUrl = $rsp->MediaUrl;
                self::$videos[$upload['url']] = $rsp->MediaUrl;
                $_SESSION['uploadVideos'][$upload['url']] = $rsp->MediaUrl;
            } catch (Exception $e) {
                // 处理上传异常
                echo $e;
            }
        }
        return $upload;
    }

    public function modifyVideo($response, $post, $request)
    {
        $response->data['guid']['rendered'] = $this->mediaUrl;
        $response->data['guid']['raw'] = $this->mediaUrl;
        return $response;
    }

    public function postVideos($post, $query)
    {
        $post->guid = $this->mediaUrl;
    }

    public function insertPostData($data, $postattr, $unsanitized_postarr)
    {
        foreach ($_SESSION['uploadVideos'] as $key => $val) {
            $data['post_content'] = str_replace($key, $val, $data['post_content']);
        }
        if (!strpos($data['post_content'], '<video controls')) {
            $data['post_content'] = str_replace('<video', '<video controls', $data['post_content']);
        }
        return $data;
    }

    /**
     * 插件菜单设置
     */
    public function pluginSettingPage()
    {
        require_once 'TencentCloudVodSettingPage.php';
        TencentWordpressPluginsSettingActions::AddTencentWordpressCommonSettingPage();

        $pagehook = add_submenu_page(
            'TencentWordpressPluginsCommonSettingPage',
            '云点播',
            '云点播',
            'manage_options',
            'TencentCloudVodSettingPage',
            'TencentCloudVodSettingPage');

        add_action('admin_print_styles-' . $pagehook,
            array(new TencentCloudVodActions(), 'loadCssForPage'));
    }

    /**
     * 插件配置信息操作页面
     */
    public static function settingPage()
    {
        include TENCENT_WORDPRESS_VOD_DIR . 'TencentCloudVodSettingPage.php';
    }

    /**
     * 添加设置按钮
     * @param $links
     * @param $file
     * @return mixed
     */
    public function pluginSettingPageLinkButton($links, $file)
    {
        if ($file == plugin_basename(TENCENT_WORDPRESS_VOD_DIR . 'tencentcloud-vod.php')) {
            $links[] = '<a href="admin.php?page=TencentCloudVodSettingPage">设置</a>';
        }

        return $links;
    }

    public function loadCssForPage()
    {
        wp_enqueue_style('codeVerify_admin_css', TENCENT_WORDPRESS_PLUGINS_COMMON_CSS_URL . 'bootstrap.min.css');
    }

    /**
     * 加载js脚本
     */
    public function loadMyScriptEnqueue()
    {
        wp_register_script('wp_vod_back_admin_script',
            TENCENT_WORDPRESS_VOD_JS_DIR . 'tencent_cloud_vod_admin.js',
            array('jquery'), '2.1', true);
        wp_enqueue_script('wp_vod_back_admin_script');
    }


    /**
     * @param $secretID string 腾讯云密钥ID
     * @param $secretKey string 腾讯云密钥Key
     * @param $SDKAppId string 腾讯云点播 SDKAppId
     * @param $transcode string 云点播转自适应码流
     * @param $secretCustom string 自定义密钥
     * @return bool|string
     */
    public static function checkMustParams($secretID, $secretKey, $SubAppId, $transcode, $secretCustom)
    {
        if ($secretCustom == '1') {
            if (empty($secretID)) {
                return 'Secret Id未填写.';
            }
            if (empty($secretKey)) {
                return 'Secret key未填写.';
            }
        }
        if (empty($SubAppId)) {
            return 'SDKAppId 未填写.';
        }
        if (empty($transcode)) {
            return '是否转码未填写.';
        }
        return true;
    }

    public function checkVodSettings() {
        $vodOptionsSettings = new TencentCloudVodOptions();
        $vodOptionsSettings->setSecretID(sanitize_text_field($_POST['secretId']));
        $vodOptionsSettings->setSecretKey(sanitize_text_field($_POST['secretKey']));
        $vodOptionsSettings->setSubAppID(sanitize_text_field($_POST['SubAppId']));
        $vodOptionsSettings->setCustomKey(sanitize_text_field($_POST['secretCustom']));
        $vodOptionsSettings->setTranscode(sanitize_text_field($_POST['transcode']));

        if ($vodOptionsSettings->getCustomKey() == '0') {
            $vodOptionsSettings->setSecretID('');
            $vodOptionsSettings->setSecretKey('');
        }

        $checkResult = self::checkMustParams(
            $vodOptionsSettings->getSecretID(),
            $vodOptionsSettings->getSecretKey(),
            $vodOptionsSettings->getSubAppID(),
            $vodOptionsSettings->getTransCode(),
            $vodOptionsSettings->getCustomKey());

        if ($checkResult !== true) {
            wp_send_json_error(array('msg' => $checkResult));
        }

        try {
            $cred = new Credential($vodOptionsSettings->getSecretID(), $vodOptionsSettings->getSecretKey());
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, "", $clientProfile);
            $req = new DescribeAllClassRequest();
            $params = array(
                "SubAppId" => $vodOptionsSettings->getSubAppID()
            );
            $req->fromJsonString(json_encode($params));
            $resp = $client->DescribeAllClass($req);
            wp_send_json_success(array('msg' => '配置测试成功', 'info' => $resp->toJsonString()));
        } catch (TencentCloudSDKException $e) {
            wp_send_json_error(array('msg' => '配置测试失败', 'info' => $e));
        }
    }

    /**
     * 保存插件配置
     */
    public function updateVodSettings()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('msg' => '当前用户无权限.'));
        }
        $vodOptionsSettings = new TencentCloudVodOptions();
        $vodOptionsSettings->setSecretID(sanitize_text_field($_POST['secretId']));
        $vodOptionsSettings->setSecretKey(sanitize_text_field($_POST['secretKey']));
        $vodOptionsSettings->setSubAppID(sanitize_text_field($_POST['SubAppId']));
        $vodOptionsSettings->setCustomKey(sanitize_text_field($_POST['secretCustom']));
        $vodOptionsSettings->setTranscode(sanitize_text_field($_POST['transcode']));

        if ($vodOptionsSettings->getCustomKey() == '0') {
            $vodOptionsSettings->setSecretID('');
            $vodOptionsSettings->setSecretKey('');
        }

        $checkResult = self::checkMustParams(
            $vodOptionsSettings->getSecretID(),
            $vodOptionsSettings->getSecretKey(),
            $vodOptionsSettings->getSubAppID(),
            $vodOptionsSettings->getTransCode(),
            $vodOptionsSettings->getCustomKey());
        if ($checkResult !== true) {
            wp_send_json_error(array('msg' => $checkResult));
        }
        update_option(self::TENCENT_WORDPRESS_VOD_OPTIONS, $vodOptionsSettings, true);
        //发送用户体验数据
        $staticData = self::getTencentCloudWordPressStaticData('save_config');
        TencentWordpressPluginsSettingActions::sendUserExperienceInfo($staticData);
        wp_send_json_success(array('msg' => '保存成功'));

    }


    /**
     * 获取SecrtId
     * @return mixed
     */
    private static function getSecretID()
    {
        $tecentVodOptions = self::getVodOptionsObject();
        return $tecentVodOptions->getSecretID();
    }

    /**
     * 获取SecrtKey
     * @return mixed
     */
    private static function getSecretKey()
    {
        $tecentVodOptions = self::getVodOptionsObject();
        return $tecentVodOptions->getSecretKey();

    }

    /**
     * 开启插件
     */
    public static function activatePlugin()
    {
        $tencentWordpressVodOptions = self::getVodOptionsObject();

        $tencentWordpressVodOptions->setActivation(true);

        delete_option(self::TENCENT_WORDPRESS_VOD_OPTIONS);

        add_option(self::TENCENT_WORDPRESS_VOD_OPTIONS, $tencentWordpressVodOptions);

        $plugin = array(
            'plugin_name' => TENCENT_WORDPRESS_VOD_SHOW_NAME,
            'nick_name' => '腾讯云云点播（VOD）插件',
            'plugin_dir' => 'tencentcloud-vod/tencentcloud-vod.php',
            'href' => 'admin.php?page=TencentCloudVodSettingPage',
            'activation' => 'true',
            'status' => 'true',
            'download_url' => ''
        );

        TencentWordpressPluginsSettingActions::prepareTencentWordressPluginsDB($plugin);

        // 第一次开启插件则生成一个全站唯一的站点id，保存在公共的option中
        TencentWordpressPluginsSettingActions::setWordPressSiteID();

        //发送用户体验数据
        $staticData = self::getTencentCloudWordPressStaticData('activate');
        TencentWordpressPluginsSettingActions::sendUserExperienceInfo($staticData);
    }

    /**
     * 禁止插件
     */
    public static function deactivePlugin()
    {
        $tencentWordpressVodOptions = self::getVodOptionsObject();
        if ($tencentWordpressVodOptions->getActivation()) {
            $tencentWordpressVodOptions->setActivation(false);
            update_option(self::TENCENT_WORDPRESS_VOD_OPTIONS, $tencentWordpressVodOptions);
        }
        TencentWordpressPluginsSettingActions::disableTencentWordpressPlugin(TENCENT_WORDPRESS_VOD_SHOW_NAME);
        $staticData = self::getTencentCloudWordPressStaticData('deactivate');
        TencentWordpressPluginsSettingActions::sendUserExperienceInfo($staticData);
    }

    /**
     * 删除插件
     */
    public static function uninstallPlugin()
    {
        delete_option(self::TENCENT_WORDPRESS_VOD_OPTIONS);
        TencentWordpressPluginsSettingActions::disableTencentWordpressPlugin(TENCENT_WORDPRESS_VOD_SHOW_NAME);
        $staticData = self::getTencentCloudWordPressStaticData('uninstall');
        TencentWordpressPluginsSettingActions::sendUserExperienceInfo($staticData);
    }

    /**
     * 初始化插件中心设置页面
     */
    public function initCommonSettingPage()
    {
        self::requirePluginCenterClass();
        if (class_exists('TencentWordpressPluginsSettingActions')) {
            TencentWordpressPluginsSettingActions::init();
        }
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * 引入插件中心类
     */
    public static function requirePluginCenterClass()
    {
        require_once TENCENT_WORDPRESS_PLUGINS_COMMON_DIR . 'TencentWordpressPluginsSettingActions.php';
    }

    public static function getTencentCloudWordPressStaticData($action)
    {
        $siteId = TencentWordpressPluginsSettingActions::getWordPressSiteID();
        $siteUrl = TencentWordpressPluginsSettingActions::getWordPressSiteUrl();
        $siteApp = TencentWordpressPluginsSettingActions::getWordPressSiteApp();
        $staticData['action'] = $action;
        $staticData['plugin_type'] = 'vod';
        $staticData['data'] = array(
            'site_id' => $siteId,
            'site_url' => $siteUrl,
            'site_app' => $siteApp
        );
        $tencentWordpressVodOptions = self::getVodOptionsObject();

        $staticData['data']['uin'] = TencentWordpressPluginsSettingActions::getUserUinBySecret(
            $tencentWordpressVodOptions->getSecretID(),
            $tencentWordpressVodOptions->getSecretKey());

        $staticData['data']['cust_sec_on'] = $tencentWordpressVodOptions->getCustomKey() == '2' ? 1 : 2;
        $others = array(
            'vod_appid' => $tencentWordpressVodOptions->getSubAppId(),
        );
        $staticData['data']['others'] = json_encode($others);
        return $staticData;
    }

    /**
     * 获取配置对象
     * @return TencentCloudVodOptions
     */
    public static function getVodOptionsObject()
    {
        $vodOptions = get_option(self::TENCENT_WORDPRESS_VOD_OPTIONS);
        if ($vodOptions instanceof TencentCloudVodOptions) {
            return $vodOptions;
        }
        return new TencentCloudVodOptions();
    }
}




