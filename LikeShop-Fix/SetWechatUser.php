<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\api\validate;


use think\Validate;

/**
 * 更新微信信息验证器
 * Class UpdateWechatUser
 * @package app\api\validate
 */
class SetWechatUser extends Validate
{
    protected $rule = [
        'nickname'  => 'require',
        'avatar'    => 'require|url|checkAvatarUrl',  // Thêm 'url' và custom check
        'sex'       => 'require',
    ];

    protected $message = [
        'nickname.require'  => '参数缺失',
        'avatar.require'    => '参数缺失',
        'avatar.url'        => '头像必须是有效的URL',
        'avatar.checkAvatarUrl' => '头像URL不安全或不允许',
        'sex.require'       => '参数缺失',
    ];

    // Custom method để kiểm tra URL an toàn
    protected function checkAvatarUrl($value, $rule, $data)
    {
        // Decode URL để kiểm tra encoded payloads
        $decoded_value = urldecode($value);
        
        // Chặn file://, ftp://, data://, dict://, ldap://, gopher://, etc.
        if (preg_match('/^(file|ftp|data|dict|ldap|gopher):\/\//i', $decoded_value)) {
            return false;
        }
        // Chặn localhost, 127.0.0.1, private IPs
        $parsed = parse_url($decoded_value);
        if (isset($parsed['host'])) {
            $host = strtolower($parsed['host']);
            if ($host === 'localhost' || $host === '127.0.0.1' || 
                filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }
        }
        // Chỉ cho phép HTTP/HTTPS
        if (!preg_match('/^https?:\/\//i', $decoded_value)) {
            return false;
        }
        return true;
    }
}
