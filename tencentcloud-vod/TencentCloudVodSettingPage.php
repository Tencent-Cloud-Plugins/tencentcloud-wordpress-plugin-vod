<?php
/*
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
		    <?php _e('Tencent Cloud VOD plugin in effect.', 'tencentcloud-vod'); ?>
        </p>
        <p>
	        <?php _e('If you upload a video in the background of wordpress, the video will be uploaded to VOD automatically , and the video source on VOD will be used for subsequent playback, without consuming the traffic of the source site.', 'tencentcloud-vod'); ?>
        </p>
        <p>
	        <?php _e('Including WMV, AVI, FLV, MOV, MPEG, MP4, WEBM, MKV and other video formats.', 'tencentcloud-vod'); ?>
        </p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">
		    <?php _e('Ignore this notification.', 'tencentcloud-vod'); ?>
            </span></button>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header ">
                <h1 id="forms">
	                <?php _e('Tencent Cloud VOD plugin', 'tencentcloud-vod'); ?>
                </h1>
            </div>
            <p><?php _e('Videos will be uploaded to Tencent Cloud VOD automatically, and distributed and played through VOD', 'tencentcloud-vod'); ?></p>
        </div>
    </div>
    <div class="alert alert-dismissible alert-success" style="display: none;">
        <button type="button" id="vod-close-ajax-return-msg" class="close" data-dismiss="alert">&times;</button>
        <div id="vod-show-ajax-return-msg">
	        <?php _e('Success.', 'tencentcloud-vod'); ?>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);" id="vod-sub-tab-settings">
	            <?php _e('Plugin configuration', 'tencentcloud-vod'); ?>
            </a>
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
                                <th scope="row"><label for="vod-option-custom-key">
                                        <h5><?php _e('Custom key', 'tencentcloud-vod'); ?></h5>
                                    </label></th>
                                <td>
                                    <div class="custom-control custom-switch div_custom_switch_padding_top">
                                        <input type="checkbox" class="custom-control-input"
                                               id="vod-option-custom-key" <?php if ($customKey === $vodOptions::CUSTOM_KEY) {
                                            echo 'checked';
                                        } ?> >
                                        <label class="custom-control-label" for="vod-option-custom-key">
	                                        <?php _e('Configure a separate Tencent Cloud key for the plugin', 'tencentcloud-vod'); ?>
                                        </label>
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
                                    <p class="description">
                                        <?php _e("Access", "tencentcloud-vod"); ?>
                                        <a href="https://console.qcloud.com/cam/capi" target="_blank">
	                                        <?php _e("key management", "tencentcloud-vod"); ?>
                                        </a>
	                                    <?php _e("get the secret ID and secret key or create a key string through \"Create Key\"", "tencentcloud-vod"); ?>
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="vod-option-sdk-appid"><h5>SubAppID</h5></label></th>
                                <td><input type="text" name="vod-option-sdk-appid" autocomplete="off"
                                           value="<?php echo $SubAPPID; ?>"
                                           id="vod-option-sdk-appid" size="65">
                                    <p class="description">
	                                    <?php _e("Access", "tencentcloud-vod"); ?>
                                        <a href="https://console.cloud.tencent.com/vod/app-manage" target="_blank">
	                                        <?php _e("app management", "tencentcloud-vod"); ?>
                                        </a>
	                                    <?php _e("get SubAppID or create SubAppId through \"Create Application\"", "tencentcloud-vod"); ?>
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="vod-option-transcode">
                                        <h5><?php _e('Adaptive dynamic streaming', 'tencentcloud-vod'); ?></h5>
                                    </label>
                                </th>
                                <td>
                                    <div class="custom-control custom-switch div_custom_switch_padding_top">
                                        <input type="checkbox" class="custom-control-input"
                                               id="vod-option-transcode" <?php if ($transCode === $vodOptions::HLS_TRANSCODE) {
                                            echo 'checked';
                                        } ?> >
                                        <label class="custom-control-label" for="vod-option-transcode">
                                            <?php _e('Enable adaptive dynamic streaming', 'tencentcloud-vod'); ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="vod-option-transcode">
                                        <h5>
	                                        <?php _e('Verify configuration', 'tencentcloud-vod'); ?>
                                        </h5>
                                    </label>
                                </th>
                                <td>
                                    <button type="button" id="tencnetcloud-vod-setting-check-button" class="btn btn-info">
	                                    <?php _e('Verify', 'tencentcloud-vod'); ?>
                                    </button>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <button type="button" id="tencnetcloud-vod-setting-update-button" class="btn btn-primary">
	        <?php _e('Save', 'tencentcloud-vod'); ?>
        </button>
        <div style="text-align: center;flex: 0 0 auto;margin-top: 3rem;">
            <a href="https://openapp.qq.com/docs/Wordpress/vod.html" target="_blank">
	            <?php _e('Documentation', 'tencentcloud-vod'); ?>
            </a> | <a
                    href="https://github.com/Tencent-Cloud-Plugins/tencentcloud-wordpress-plugin-vod" target="_blank">GitHub</a>
            | <a
                    href="https://support.qq.com/product/164613" target="_blank">
		        <?php _e('Feedback', 'tencentcloud-vod'); ?>
            </a> | <a
            </a>
        </div>
    </div>
    <?php
}
