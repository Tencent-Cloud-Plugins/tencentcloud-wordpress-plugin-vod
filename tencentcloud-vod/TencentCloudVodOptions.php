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

namespace TencentCloudVod;

class TencentCloudVodOptions
{
    //使用全局密钥
    const GLOBAL_KEY = 0;
    //使用自定义密钥
    const CUSTOM_KEY = 1;
    //转自适应码流
    const HLS_TRANSCODE = 1;
    const DO_NOT_TRANSCODE = 0;

    private $secretID;
    private $customKey;
    private $secretKey;
    private $SubAppID;
    private $activation = true;
    private $transcode;
    private $commonOptions;

    public function __construct($customKey = self::GLOBAL_KEY,
                                $secretID = '',
                                $secretKey = '',
                                $SubAppID = '',
                                $transcode = self::DO_NOT_TRANSCODE)
    {
        $this->customKey = $customKey;
        $this->secretID = $secretID;
        $this->secretKey = $secretKey;
        $this->SubAppID = $SubAppID;
        $this->transcode = $transcode;
        $this->commonOptions = $this->getCommonOptions();
    }

    /**
     * 获取全局的配置项
     */
    public function getCommonOptions()
    {
        return get_option(TENCENT_WORDPRESS_COMMON_OPTIONS);
    }

    public function setTranscode($transcode) {
        if ( !in_array($transcode, array(self::DO_NOT_TRANSCODE, self::HLS_TRANSCODE)) ) {
            wp_send_json_error(array('msg' => '开启自适应码流传参错误'));
        }
        $this->transcode = intval($transcode);
    }

    public function setActivation($activation) {
        if (!is_bool($activation)) {
            throw new \Exception('activation 值必须为 boolean 值');
        }
        $this->activation = $activation;
    }

    public function setSecretID($secretID)
    {
        if ( empty($secretID) && $this->customKey == self::CUSTOM_KEY) {
            wp_send_json_error(array('msg' => 'secretID不能为空'));
        }
        $this->secretID = $secretID;
    }

    public function setCustomKey($customKey)
    {
        if ( !in_array($customKey, array(self::GLOBAL_KEY, self::CUSTOM_KEY)) ) {
            wp_send_json_error(array('msg' => '自定义密钥传参错误'));
        }
        $this->customKey = intval($customKey);
    }

    public function setSecretKey($secretKey)
    {
        if ( empty($secretKey) && $this->customKey == self::CUSTOM_KEY ) {
            wp_send_json_error(array('msg' => 'secretKey不能为空'));
        }
        $this->secretKey = $secretKey;
    }

    public function setSubAppID($SubAppID)
    {
        if ( empty($SubAppID) ) {
            wp_send_json_error(array('msg' => 'SubAppID不能为空'));
        }
        $this->SubAppID = $SubAppID;
    }

    public function getSecretID()
    {
        if ( $this->customKey === self::GLOBAL_KEY && isset($this->commonOptions['secret_id']) ) {
            $this->secretID = $this->commonOptions['secret_id'] ?: '';
        }
        return $this->secretID;
    }

    public function getSecretKey()
    {
        if ( $this->customKey === self::GLOBAL_KEY && isset($this->commonOptions['secret_key']) ) {
            $this->secretKey = $this->commonOptions['secret_key'] ?: '';
        }
        return $this->secretKey;
    }

    public function getSubAppID()
    {
        return $this->SubAppID;
    }

    public function getCustomKey()
    {
        return $this->customKey;
    }

    public function getTranscode() {
        return $this->transcode;
    }

    public function getActivation() {
        return $this->activation;
    }
}