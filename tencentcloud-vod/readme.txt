=== 腾讯云点播 （VOD） ===

Contributors: Tencent
Donate link: https://www.tencent.com/
Tags:tencent,tencentcloud,qcloud,春雨,腾讯云点播,腾讯云VOD,腾讯云,云点播
Requires at least: 4.5.0
Tested up to: 5.7
Stable tag: 1.0.4
License:Apache 2.0
License URI:http://www.apache.org/licenses/LICENSE-2.0

== Description ==

<strong>腾讯云点播 （VOD），基于腾讯云点播在WordPress框架中实现播放和查看云视频的功能。</strong>

<strong>主要功能：</strong>

* 1、支持在 WordPress 中使用腾讯云点播功能

本项目由腾讯云中小企业产品中心建设和维护，了解与该插件使用相关的更多信息，请访问[春雨文档中心](https://openapp.qq.com/docs/Wordpress/vod.html)

请通过[咨询建议](https://txc.qq.com/)向我们提交宝贵意见。

== Installation ==

* 1、把tencentcloud-vod文件夹上传到/wp-content/plugins/目录下<br />
* 2、在后台插件列表中激活腾讯云点播插件<br />
* 3、在"设置""菜单中输入腾讯云点播相关参数信息<br />

== Frequently Asked Questions ==

* 1.当发现插件出错时，开启调试获取错误信息。

== 使用说明 ==

* 1、保存云点播插件设置前，可以先点击验证设置，查看配置是否正确可用
* 2、启用云点播插件之后，在 wordpress 后台上传视频文件，会自动将视频文件上传至腾讯云点播，并将在后续播放时使用腾讯云点播的视频源，不消耗源站流量
* 3、包括 wmv, avi, flv, mov, mpeg, mp4, webm, mkv 等视频格式

== Changelog ==

= 1.0.2 =
* 1、支持在 WordPress 中使用云点播功能；
* 2、更新过期版本 bootstrap.css；
* 3、change remote call curl_init to wp_remote_post；
* 4、modify generic function/class/define/namespace names；

= 1.0.3 =
* 1、remove redundant script and style files；

= 1.0.4 =
* 1. fixed an issue where the adaptive stream option could not be turned off;