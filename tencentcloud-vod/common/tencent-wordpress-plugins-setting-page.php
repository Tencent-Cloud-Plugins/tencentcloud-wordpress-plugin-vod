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
// Check that the file is not accessed directly.
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
function tencent_wordpress_plugin_common_page()
{
    $tencent_wordpress_common_options = get_option(TENCENT_WORDPRESS_COMMON_OPTIONS);
    $ajax_url = admin_url('admin-ajax.php');
    $tencent_plugins = TencentWordpressPluginsSettingActions::getTencentWordpressPluggins();
    ?>
    <!--add style file-->
    <style>
        .dashicons {
            vertical-align: middle;
            position: relative;
            right: 30px;
        }

        .pluging-space-center {
            display: flex;
            align-items: center;
        }

        .plugin-button-close {
            margin-left: 20px;
        }

        .lable_padding_left {
            padding-left: 30px
        }

        .div_custom_switch_padding_top {
            padding-top: 15px;
            padding-left: 75px
        }
    </style>
    <div class="wrap">
        <div class="bs-docs-section">
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header ">
                        <h1 id="forms">
                            <?php _e('Tencent Cloud Configuration', 'tencentcloud-vod'); ?>
                        </h1>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a id="tencent_wordpress_plugins_home_id" class="nav-link active" data-toggle="tab"
                       href="#tencent_wordpress_plugins_home">
	                    <?php _e('Plugin configuration center', 'tencentcloud-vod'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="tencent_wordpress_secret_home_id" class="nav-link" data-toggle="tab"
                       href="#tencent_wordpress_secret_home">
	                    <?php _e('Tencent Cloud key', 'tencentcloud-vod'); ?>
                    </a>
                </li>
            </ul>

            <div id="myTabContent" class="tab-content">
                <!-- Tencent Cloud Wordpress Plugins Setting Page-->
                <div class="tab-pane fade active show" style="padding-left: 20px" id="tencent_wordpress_plugins_home">
                    <div class="form-group">
                        <br class="my-4">
                        <div class="row">
                            <span class="col-lg-4"><h5><?php _e('Functionality', 'tencentcloud-vod'); ?></h5></span>
                            <span class="col-lg-1"><h5><?php _e('Version', 'tencentcloud-vod'); ?></h5></span>
                            <span class="col-lg-1"><h5><?php _e('Status', 'tencentcloud-vod'); ?></h5></span>
                            <span class="col-lg-2"><h5><?php _e('Operation', 'tencentcloud-vod'); ?></h5></span>
                        </div>
                        <hr>
                        <?php
                        foreach ($tencent_plugins as $path => $plugin) {
                            echo '<div class="row">';
                            if (isset($plugin['nick_name'])) {
                                echo '<span class="col-lg-4"><h5>' . $plugin['nick_name'] . '</h5>' . $plugin['Description'] . '</span>';
                            } else {
                                echo '<span class="col-lg-4"><h5>' . $plugin['Name'] . '</h5>' . $plugin['Description'] . '</span>';
                            }

                            echo '<span class="col-lg-1 pluging-space-center">' . $plugin['Version'] . '</span>';

                            $status = __('Close', 'tencentcloud-vod');
                            $op_status = __('Open', 'tencentcloud-vod');
                            if (isset($plugin['status']) && $plugin['status'] == 'true') {
	                            $status = __('Open', 'tencentcloud-vod');
                                $op_status = __('Close', 'tencentcloud-vod');
                            }
	                        echo '<span class="col-lg-1 pluging-space-center"> ' . $status . ' </span>';

                            if (isset($plugin['activation']) && $plugin['activation'] == 'true') {
                                echo '<span class="col-lg-2 pluging-space-center">';
                                if (isset($plugin['status']) && $plugin['status'] == 'true') {
                                    echo '<a type="button" class="btn btn-primary" href="' . $plugin['href'] . '">' . __('Configuration', 'tencentcloud-vod') . '</a>';
                                    echo '<button title="' . $plugin['plugin_dir'] . '" id="button_close_tencent_plugin_' . $plugin['Name'] . '"  name="' . $plugin['Name'] . '" type="button" class="btn btn-primary plugin-button-close">' . $op_status . '</button>';
                                } else {
                                    echo '<button title="' . $plugin['plugin_dir'] . '" id="button_open_tencent_plugin_' . $plugin['Name'] . '"  name="' . $plugin['Name'] . '" type="button" class="btn btn-primary">' . $op_status . '</button>';
                                }
                                echo '</span>';
                            } else {
                                echo '<span class="col-lg-1 pluging-space-center"><button type="button" disabled class="btn btn-primary">安装</button></span>';
                            }
                            echo '</div>';
                            echo '<hr class="my-4">';
                        }
                        ?>
                    </div>
                </div>

                <!-- Tencent Could Common SecretId and SecretKey Setting Page-->
                <div class="tab-pane fade" id="tencent_wordpress_secret_home">
                    <br class="my-4">
                    <div class="postbox">
                        <div class="inside">
                            <div class="row">
                                <div class="col-lg-9">
                                    <form id="tencent_wordpress_common_secert_info_form"
                                          data-ajax-url="<?php echo esc_attr($ajax_url) ?>" name="twpcommomsecret" method="post"
                                          class="bs-component">
                                        <!-- Setting Option no_local_file-->
                                        <div class="row form-group">
                                            <label class="col-form-label col-lg-2 lable_padding_left"
                                                   for="inputDefault">
                                                <?php _e('Turn on the global key', 'tencentcloud-vod'); ?>
                                            </label>
                                            <div class="custom-control custom-switch div_custom_switch_padding_top">
                                                <input name="tencent_wordpress_common_secret" type="checkbox"
                                                       class="custom-control-input"
                                                       id="tencent_wordpress_common_secret_checkbox_id"
                                                    <?php
                                                    if (isset($tencent_wordpress_common_options)
                                                        && isset($tencent_wordpress_common_options['site_sec_on'])
                                                        && $tencent_wordpress_common_options['site_sec_on'] === true) {
                                                        echo 'checked="true"';
                                                    }
                                                    ?>
                                                >
                                                <label class="custom-control-label"
                                                       for="tencent_wordpress_common_secret_checkbox_id">
                                                    <?php _e('Configure the universal Tencent cloud key for each plugin', 'tencentcloud-vod'); ?>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Setting Option SecretId-->
                                        <div class="form-group">
                                            <label class="col-form-label col-lg-2" for="inputDefault">SecretId</label>
                                            <input id="input_twp_common_secret_id" name="twp_common_secret_id"
                                                   type="password" class="col-lg-5 is-invalid" placeholder="SecretId"
                                                   value="<?php if (isset($tencent_wordpress_common_options) && isset($tencent_wordpress_common_options['secret_id'])) {
                                                       echo esc_attr($tencent_wordpress_common_options['secret_id']);
                                                   } ?>">

                                            <span id="twp_common_secret_id_change_type"
                                                  class="dashicons dashicons-hidden"></span>
                                            <span id="span_twp_common_secret_id"
                                                  class="invalid-feedback offset-lg-2"></span>
                                        </div>
                                        <!-- Setting Option SecretKey-->
                                        <div class="form-group">
                                            <label class="col-form-label col-lg-2" for="inputDefault">SecretKey</label>
                                            <input id="input_twp_common_secret_key" name="twp_common_secret_key"
                                                   type="password" class="col-lg-5 is-invalid" placeholder="SecretKey"
                                                   value="<?php if (isset($tencent_wordpress_common_options) && isset($tencent_wordpress_common_options['secret_key'])) {
                                                       echo esc_attr($tencent_wordpress_common_options['secret_key']);
                                                   } ?>">
                                            <span id="twp_common_secret_key_change_type"
                                                  class="dashicons dashicons-hidden"></span>
                                            <span id="span_twp_common_secret_key"
                                                  class="invalid-feedback offset-lg-2"></span>
                                            <div class="offset-lg-2">
                                                <p>
	                                                <?php _e("Access", "tencentcloud-vod"); ?>
                                                    <a href="https://console.qcloud.com/cam/capi"
                                                         target="_blank">
		                                                <?php _e('key management', 'tencentcloud-vod'); ?>
                                                    </a>
	                                                <?php _e("get the secret ID and secret key or create a key string through \"Create Key\"", "tencentcloud-vod"); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Setting Option no_local_file-->
                                        <div class="row form-group">
                                            <label class="col-form-label col-lg-2 lable_padding_left"
                                                   for="inputDefault">
	                                            <?php _e("Participate in experience optimization projects", "tencentcloud-vod"); ?>
                                            </label>
                                            <div class="custom-control custom-switch div_custom_switch_padding_top">
                                                <input name="customize_optimize_project" type="checkbox"
                                                       class="custom-control-input"
                                                       id="customize_optimize_project_checkbox_id"
                                                    <?php
                                                    if (isset($tencent_wordpress_common_options)
                                                        && isset($tencent_wordpress_common_options['site_report_on'])
                                                        && $tencent_wordpress_common_options['site_report_on'] === true) {
                                                        echo 'checked="true"';
                                                    }
                                                    ?>
                                                >
                                                <label class="custom-control-label"
                                                       for="customize_optimize_project_checkbox_id">
	                                                <?php _e('Allows Tencent Cloud to collect the necessary plugin data to optimize and improve the product experience', 'tencentcloud-vod'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="button_twp_common_secret_save" type="button" class="btn btn-primary">
	                    <?php _e("Save", "tencentcloud-vod"); ?>
                    </button>
                    <span id="span_twp_common_secret_save" class="invalid-feedback offset-lg-2"></span>
                </div>
            </div>
            <br>
            <div style="text-align: center" class="setting_page_footer">
                <a href="https://openapp.qq.com/" target="_blank">
	                <?php _e('Documentation', 'tencentcloud-vod'); ?>
                </a> | <a
                        href="https://github.com/Tencent-Cloud-Plugins/" target="_blank">GitHub</a> | <a
                        href="https://support.qq.com/product/164613" target="_blank">
		            <?php _e('Feedback', 'tencentcloud-vod'); ?>
                </a>
            </div>
        </div>
    </div>


    <script>
        jQuery(function ($) {
            function eye_change_type(input_element, span_eye) {
                if (input_element[0].type === 'password') {
                    input_element[0].type = 'text';
                    span_eye.addClass('dashicons-visibility').removeClass('shicons-hidden');
                } else {
                    input_element[0].type = 'password';
                    span_eye.addClass('shicons-hiddenda').removeClass('dashicons-visibility');
                }
            }

            $('#twp_common_secret_id_change_type').click(function () {
                eye_change_type($('#input_twp_common_secret_id'), $('#twp_common_secret_id_change_type'));
            });

            $('#twp_common_secret_key_change_type').click(function () {
                eye_change_type($('#input_twp_common_secret_key'), $('#twp_common_secret_key_change_type'));
            });

            $('#tencent_wordpress_plugins_home_id').click(function () {
                $('#tencent_wordpress_plugins_home_id').removeClass('active').addClass('active');
                $('#tencent_wordpress_secret_home_id').removeClass('active');

                $('#tencent_wordpress_plugins_home').addClass('active show');
                $('#tencent_wordpress_secret_home').removeClass('active show');

            });

            $('#tencent_wordpress_secret_home_id').click(function () {
                $('#tencent_wordpress_plugins_home_id').removeClass('active');
                $('#tencent_wordpress_secret_home_id').removeClass('active').addClass('active');

                $('#tencent_wordpress_plugins_home').removeClass('active show');
                $('#tencent_wordpress_secret_home').addClass('active show');
            });

            var ajaxUrl = $("#tencent_wordpress_common_secert_info_form").data("ajax-url");
            $('#button_twp_common_secret_save').click(function () {
                var secret_id = $('#input_twp_common_secret_id').val();
                var secret_key = $('#input_twp_common_secret_key').val();
                var site_secret_on
                var site_report_on
                if ($('#tencent_wordpress_common_secret_checkbox_id').get(0).checked === false) {
                    site_secret_on = false;
                } else {
                    site_secret_on = true;
                }

                if ($('#customize_optimize_project_checkbox_id').get(0).checked === false) {
                    site_report_on = false;
                } else {
                    site_report_on = true;
                }

                if (!secret_key || !secret_id) {
                    alert("SecretId、SecretKey的值都不能为空！");
                    return false;
                }

                $.ajax({
                    type: "post",
                    url: ajaxUrl,
                    dataType: "json",
                    data: {
                        action: "save_tencent_wordpress_common_options",
                        secret_id: secret_id,
                        secret_key: secret_key,
                        site_secret_on: site_secret_on,
                        site_report_on: site_report_on
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#span_twp_common_secret_save')[0].innerHTML = "保存成功！";

                        } else {
                            $('#span_twp_common_secret_save')[0].innerHTML = "保存失败！";
                        }
                        $('#span_twp_common_secret_save').show().delay(3000).fadeOut();
                    }
                });
            });

            $('button[id^="button_close_tencent_plugin_"]').click(function () {
                var plugin_name = $(this)[0].name;
                var plugin_dir = $(this)[0].title;

                $.ajax({
                    type: "post",
                    url: ajaxUrl,
                    dataType: "json",
                    data: {
                        action: "close_tencent_wordpress_plugin",
                        plugin_name: plugin_name,
                        plugin_dir: plugin_dir
                    },
                    success: function (response) {
                        if (response.success) {
                            if (response.data.redirect) {
                                $(location).attr('href', response.data.redirect);
                            } else {
                                setTimeout(location.reload.bind(location), 1000);
                            }
                        }
                    }
                });
            });

            $('button[id^="button_open_tencent_plugin_"]').click(function () {
                var plugin_name = $(this)[0].name;
                var plugin_dir = $(this)[0].title;
                $.ajax({
                    type: "post",
                    url: ajaxUrl,
                    dataType: "json",
                    data: {
                        action: "open_tencent_wordpress_plugin",
                        plugin_name: plugin_name,
                        plugin_dir: plugin_dir
                    },
                    success: function (response) {
                        if (response.success) {
                            setTimeout(location.reload.bind(location), 1000);
                        }
                    }
                });
            });
        });
    </script>
    <?php
}
