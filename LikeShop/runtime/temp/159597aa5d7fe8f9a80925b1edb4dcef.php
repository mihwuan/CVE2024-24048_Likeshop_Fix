<?php /*a:2:{s:47:"/server/application/admin/view/my/password.html";i:1765987101;s:43:"/server/application/admin/view/layout1.html";i:1765987101;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo url(); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/layui/css/layui.css?v=<?php echo htmlentities($front_version); ?>" media="all">
    <link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/style/admin.css?v=<?php echo htmlentities($front_version); ?>" media="all">
    <link rel="stylesheet" href="/static/plug/layui-admin/dist/layuiadmin/style/like.css?v=<?php echo htmlentities($front_version); ?>" media="all">
</head>
<body>
<?php echo $js_code; ?>
<script src="/static/plug/layui-admin/dist/layuiadmin/layui/layui.js?v=<?php echo htmlentities($front_version); ?>"></script>
<script src="/static/common/js/function.js?v=<?php echo htmlentities($front_version); ?>"></script>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">修改密码</div>
        <div class="layui-card-body" pad15>
            <div class="layui-form" lay-filter="">
                <div class="layui-form-item">
                    <label class="layui-form-label">当前密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="old_password" lay-verify="required" lay-verType="tips"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码</label>
                    <div class="layui-input-inline">
                        <input type="password" maxlength="16" name="password" lay-verify="required|length"
                               lay-verType="tips" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">确认新密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="re_password" lay-verify="required|comparison" lay-verType="tips"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="setmypass">确认修改</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>


    layui.config({
        version:"<?php echo htmlentities($front_version); ?>",
        base: '/static/plug/layui-admin/dist/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table', 'like'], function () {
        var $ = layui.$
            , form = layui.form
            , like = layui.like;
        form.verify({
            length: function (value, item) {
                console.log(value);
                if (value.length < 6 || value.length > 16) {
                    return '长度为' + 6 + '到' + 16 + '之间';
                }
            },
            comparison: function (value, item) {
                password = layui.$('[name=password]').val();
                if (password != value) {
                    return '两次密码输入不一致';
                }
            }
        });
        form.on('submit(setmypass)', function (data) {
            layui.like.ajax({
                url: '<?php echo url(); ?>' //实际使用请改成服务端真实接口
                , data: data.field
                , type: 'post'
                , success: function (res) {

                    if (res.code == 0) {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 1000
                        });
                        return false;
                    }

                    //登入成功的提示与跳转
                    layer.msg(res.msg, {
                        offset: '15px'
                        , icon: 1
                        , time: 1500
                    }, function () {
                        location.href = location.href; //后台主页
                    });
                }
            });
        });
    });

</script>
</body>
</html>