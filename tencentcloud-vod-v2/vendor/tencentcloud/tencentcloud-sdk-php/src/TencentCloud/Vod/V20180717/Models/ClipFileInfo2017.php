<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Vod\V20180717\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method integer getErrCode() 获取错误码
<li>0：成功；</li>
<li>其他值：失败。</li>
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setErrCode(integer $ErrCode) 设置错误码
<li>0：成功；</li>
<li>其他值：失败。</li>
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getMessage() 获取错误描述。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setMessage(string $Message) 设置错误描述。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getFileId() 获取输出目标文件的文件 ID。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFileId(string $FileId) 设置输出目标文件的文件 ID。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getFileUrl() 获取输出目标文件的文件地址。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFileUrl(string $FileUrl) 设置输出目标文件的文件地址。
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getFileType() 获取输出目标文件的文件类型。
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFileType(string $FileType) 设置输出目标文件的文件类型。
注意：此字段可能返回 null，表示取不到有效值。
 */

/**
 *视频裁剪结果文件信息（2017 版）
 */
class ClipFileInfo2017 extends AbstractModel
{
    /**
     * @var integer 错误码
<li>0：成功；</li>
<li>其他值：失败。</li>
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ErrCode;

    /**
     * @var string 错误描述。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Message;

    /**
     * @var string 输出目标文件的文件 ID。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FileId;

    /**
     * @var string 输出目标文件的文件地址。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FileUrl;

    /**
     * @var string 输出目标文件的文件类型。
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FileType;
    /**
     * @param integer $ErrCode 错误码
<li>0：成功；</li>
<li>其他值：失败。</li>
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Message 错误描述。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $FileId 输出目标文件的文件 ID。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $FileUrl 输出目标文件的文件地址。
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $FileType 输出目标文件的文件类型。
注意：此字段可能返回 null，表示取不到有效值。
     */
    function __construct()
    {

    }
    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("ErrCode",$param) and $param["ErrCode"] !== null) {
            $this->ErrCode = $param["ErrCode"];
        }

        if (array_key_exists("Message",$param) and $param["Message"] !== null) {
            $this->Message = $param["Message"];
        }

        if (array_key_exists("FileId",$param) and $param["FileId"] !== null) {
            $this->FileId = $param["FileId"];
        }

        if (array_key_exists("FileUrl",$param) and $param["FileUrl"] !== null) {
            $this->FileUrl = $param["FileUrl"];
        }

        if (array_key_exists("FileType",$param) and $param["FileType"] !== null) {
            $this->FileType = $param["FileType"];
        }
    }
}
