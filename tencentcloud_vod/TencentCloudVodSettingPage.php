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

use TencentCloudVod\TencentCloudVodActions;

function TencentCloudVodSettingPage()
{
    $ajaxUrl = admin_url('admin-ajax.php');
    $vodOptions = TencentCloudVodActions::getVodOptionsObject();
    $secretID = $vodOptions->getSecretID();
    $secretKey = $vodOptions->getSecretKey();
    $SubAPPID = $vodOptions->getSubAppID();
    $customKey = $vodOptions->getCustomKey();
    $transCode = $vodOptions->getTransCode();

    ?>
    <style type="text/css">
        .dashicons {
            vertical-align: middle;
            position: relative;
            right: 30px;
        }
    </style>
    <div id="vod-message" class="updated notice is-dismissible" style="margin-bottom: 1%;margin-left:0;"><p>
            腾讯云点播（VOD）插件启用生效中。</p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header ">
                <h1 id="forms">腾讯云点播（VOD）插件</h1>
            </div>
            <p>使WordPress上传视频，使用腾讯云点播功能</p>
        </div>
    </div>
    <div class="alert alert-dismissible alert-success" style="display: none;">
        <button type="button" id="vod-close-ajax-return-msg" class="close" data-dismiss="alert">&times;</button>
        <div id="vod-show-ajax-return-msg">操作成功.</div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);" id="vod-sub-tab-settings">插件配置</a>
        </li>
    </ul>
    <div id="post-body">
        <div class="postbox">
            <form method="post" id="tencnetcloud-vod-setting-form" action="" data-ajax-url="<?php echo $ajaxUrl ?>">
                <div id="vod-group-settings" class="group" style="display: block;">
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th scope="row"><label for="vod-option-custom-key"><h5>自定义密钥</h5></label></th>
                                <td>
                                    <div class="custom-control custom-switch div_custom_switch_padding_top">
                                        <input type="checkbox" class="custom-control-input"
                                               id="vod-option-custom-key" <?php if ($customKey === $vodOptions::CUSTOM_KEY) {
                                            echo 'checked';
                                        } ?> >
                                        <label class="custom-control-label"
                                               for="vod-option-custom-key">为该插件配置单独定义的腾讯云密钥</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="vod-option-secret-id"><h5>SecretId</h5></label></th>
                                <td><input type="password" autocomplete="off"
                                           value="<?php echo $secretID; ?>" <?php if ($customKey !== $vodOptions::CUSTOM_KEY) {
                                        echo 'disabled="disabled"';
                                    } ?>
                                           id="vod-option-secret-id" size="65"><span id="vod_secret_id_type_exchange"
                                                                                     class="dashicons dashicons-hidden"></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="vod-option-secret-key"><h5>SecretKey</h5></label></th>
                                <td><input type="password" autocomplete="off"
                                           value="<?php echo $secretKey; ?>" <?php if ($customKey !== $vodOptions::CUSTOM_KEY) {
                                        echo 'disabled="disabled"';
                                    } ?>
                                           id="vod-option-secret-key" size="65"><span id="vod_secret_key_type_exchange"
                                                                                      class="dashicons dashicons-hidden"></span>
                                    <p class="description">访问 <a href="https://console.qcloud.com/cam/capi"
                                                                 target="_blank">密钥管理</a>获取
                                        SecretId和SecretKey或通过"新建密钥"创建密钥串</p></td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="vod-option-sdk-appid"><h5>SubAppID</h5></label></th>
                                <td><input type="text" name="vod-option-sdk-appid" autocomplete="off"
                                           value="<?php echo $SubAPPID; ?>"
                                           id="vod-option-sdk-appid" size="65">
                                    <p class="description">访问<a
                                                href="https://console.cloud.tencent.com/vod/app-manage"
                                                target="_blank">应用列表</a>获取
                                        SubAppID 或通过"创建应用"创建 SubAppID</p></td>
                            </tr>


                            <tr>
                                <th scope="row"><label for="vod-option-transcode"><h5>是否开启转自适应码流</h5></label></th>
                                <td>
                                    <div class="custom-control custom-switch div_custom_switch_padding_top">
                                        <input type="checkbox" disabled class="custom-control-input"
                                               id="vod-option-transcode" <?php if ($transCode === $vodOptions::HLS_TRANSCODE) {
                                            echo 'checked';
                                        } ?> >
                                        <label class="custom-control-label" for="vod-option-transcode">自适应转码</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="vod-option-transcode"><h5>测试设置是否正确</h5></label></th>
                                <td>
                                    <button type="button" id="tencnetcloud-vod-setting-check-button" class="btn btn-info">验证设置</button>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <button type="button" id="tencnetcloud-vod-setting-update-button" class="btn btn-primary">保存设置</button>
        <div style="text-align: center;flex: 0 0 auto;margin-top: 3rem;">
            <a href="https://openapp.qq.com/docs/Wordpress/vod.html" target="_blank">文档中心</a> | <a
                    href="https://github.com/Tencent-Cloud-Plugins/tencentcloud-wordpress-plugin-vod" target="_blank">GitHub</a>
            | <a
                    href="https://support.qq.com/product/164613" target="_blank">意见反馈</a>
        </div>
    </div>
    <?php
}
