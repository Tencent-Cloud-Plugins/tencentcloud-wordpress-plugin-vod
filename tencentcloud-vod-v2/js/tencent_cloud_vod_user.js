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
    //屏蔽提交按钮
    $('#codePassButton').hide();
    //验证按钮的点击时间
    $('#codeVerifyButton').click(function () {
        //重置ticket和随机字符串的值
        $('#codeVerifyTicket').val('');
        $('#codeVerifyRandstr').val('');
        //初始化验证码
        var captcha1 = new TencentCaptcha($('#codeVerifyButton').attr('data-appid'), function (res) {
            //判断是否验证成功
            if (res.ret == 0) {
                //将返回的ticket赋值给表单
                $('#codeVerifyTicket').val(res.ticket);
                $('#codeVerifyRandstr').val(res.randstr);
                //隐藏验证按钮
                $('#codeVerifyButton').hide();
                //展示通过按钮
                $('#codePassButton').show();
            }
        });
        //显示验证码
        captcha1.show();
    });


});