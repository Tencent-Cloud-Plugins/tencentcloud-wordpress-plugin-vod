/**
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
jQuery(function ($) {
    //获取自定义场景的按钮值
    var codefreeshow = $('#vod-option-codeFree').val();
    //判断是否默认勾选，如果勾选隐藏表单否则展示
    if (codefreeshow != 2) {
        $('#codeVerifyFree').hide();
    } else {
        $('#codeVerifyFree').show();
    }
    //获取表单的提交url
    var ajaxUrl = $("#tencnetcloud-vod-setting-form").data("ajax-url")
    //插件设置保存按钮点击事件
    $('#tencnetcloud-vod-setting-update-button').click(function () {
        var secretCustom = $("#vod-option-custom-key").is(":checked") ? 1 : 0;
        var secretID = $("#vod-option-secret-id").val();
        var secretKey = $("#vod-option-secret-key").val();
        var SubAppId = $("#vod-option-sdk-appid").val();
        var transcode = $('#vod-option-transcode').is(":checked") ? 1 : 0;

        $.ajax({
            type: "post",
            url: ajaxUrl,
            dataType: "json",
            data: {
                action: "update_vod_settings",
                secretCustom: secretCustom,
                secretId: secretID,
                secretKey: secretKey,
                SubAppId: SubAppId,
                transcode: transcode
            },
            success: function (response) {
                showAjaxReturnMsg(response.data.msg, response.success)
                if (response.success) {
                    setTimeout(function () {
                        // window.location.reload();//刷新当前页面.
                    }, 2000)
                }
            }
        });
    });

    $('#tencnetcloud-vod-setting-check-button').click(function () {
        var secretCustom = $("#vod-option-custom-key").is(":checked") ? 1 : 0;
        var secretID = $("#vod-option-secret-id").val();
        var secretKey = $("#vod-option-secret-key").val();
        var SubAppId = $("#vod-option-sdk-appid").val();
        var transcode = $('#vod-option-transcode').is(":checked") ? 1 : 0;

        $.ajax({
            type: "post",
            url: ajaxUrl,
            dataType: "json",
            data: {
                action: "check_vod_settings",
                secretCustom: secretCustom,
                secretId: secretID,
                secretKey: secretKey,
                SubAppId: SubAppId,
                transcode: transcode
            },
            success: function (response) {
                showAjaxReturnMsg(response.data.msg, response.success)
                if (response.success) {
                    setTimeout(function () {
                        // window.location.reload();//刷新当前页面.
                    }, 2000)
                }
            }
        });
    });

    //展示异步返回消息
    function showAjaxReturnMsg(msg, success) {
        var parent = $('#vod-show-ajax-return-msg').parent();
        if (!success) {
            parent.removeClass('alert-success');
            parent.hasClass('alert-danger') || parent.addClass('alert-danger');
        } else {
            parent.removeClass('alert-danger');
            parent.hasClass('alert-success') || parent.addClass('alert-success');
        }
        $('#vod-show-ajax-return-msg').text(msg);
        parent.show();
        goToTheTop();
    }

    //获取元素的上级
    function goToTheTop() {
        $('html ,body').animate({scrollTop: 0}, 330);
    }

    //异步消息关闭按钮
    $('#vod-close-ajax-return-msg').click(function () {
        $(this).parent().hide();
    });
    //自定义业务按钮点击事件
    $("#vod-option-codeFree").click(function () {
        if (this.checked === true) {
            $('#codeVerifyFree').show();
        } else {
            $('#codeVerifyFree').hide();
        }
    });
    //自定义密钥点击事件
    $("#vod-option-custom-key").click(function () {
        if (this.checked === true) {
            $('#vod-option-secret-id').removeAttr('disabled');
            $('#vod-option-secret-key').removeAttr('disabled');
        } else {
            $('#vod-option-secret-id').attr('disabled', "disabled");
            $('#vod-option-secret-key').attr('disabled', "disabled");
        }

    });
    //secretId展示按钮点击事件
    $('#vod_secret_id_type_exchange').click(function () {
        change_type($('#vod-option-secret-id'), $(this));
    });
    //secretKey展示按钮点击事件
    $('#vod_secret_key_type_exchange').click(function () {
        change_type($('#vod-option-secret-key'), $(this));
    });

    //将隐藏的值展示出来
    function change_type(input_element, span_eye) {
        if (input_element[0].type === 'password') {
            input_element[0].type = 'text';
            span_eye.addClass('dashicons-visibility').removeClass('shicons-hidden');
        } else {
            input_element[0].type = 'password';
            span_eye.addClass('shicons-hiddenda').removeClass('dashicons-visibility');
        }
    }
});