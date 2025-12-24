<?php /*a:2:{s:49:"/server/application/admin/view/basic/website.html";i:1765987101;s:43:"/server/application/admin/view/layout1.html";i:1765987101;}*/ ?>
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

<style>
    .layui-form-label{
        width: 140px;
    }
    .goods-li {
        float: left;
        opacity: 1;
        position: relative;
    }

    .goods-img {
        width: 80px;
        height: 80px;
        padding: 4px;
    }
    .goods-img-del-x {
        position: absolute;
        z-index: 100;
        top: -4px;
        right: -2px;
        width: 20px;
        height: 20px;
        font-size: 16px;
        line-height: 16px;
        color: #fff;
        text-align: center;
        cursor: pointer;
        background: hsla(0, 0%, 60%, .6);
        border-radius: 10px;
    }
</style>
<div class="layui-col-md12">

    <div class="layui-fluid">
        <div class="layui-card">

        <div class="layui-card-body" >
            <div class="layui-form" lay-filter="">
                <!--商城名称-->
                <div class="layui-form-item">
                    <label class="layui-form-label"><font color="red">*</font>商城名称：</label>
                    <div class="layui-input-block">
                        <div class="layui-col-md4">
                            <input type="text" name="name"  lay-verify="required" lay-verType="tips" autocomplete="off" value="<?php echo htmlentities($config['name']); ?>" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">在需要显示商城名称的位置使用</span>
                </div>


                <!--商城浏览器图标-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">商城浏览器图标：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="web_favicon" type="hidden" value="<?php echo htmlentities($config['web_favicon']); ?>" >
                            <?php if(!empty($config['web_favicon'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img"  src="<?php echo htmlentities($config['web_favicon']); ?> " style="background-color: #EEEEEE;height: 50px;width:auto">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">商城在浏览器标签页显示的图标,favicon.ico文件</div>
                    </div>
                </div>


                <!--移动端登录页logo-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">移动端登录页logo：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="shop_login_logo" type="hidden" value="<?php echo htmlentities($config['shop_login_logo']); ?>" >
                            <?php if(!empty($config['shop_login_logo'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img"  src="<?php echo htmlentities($config['shop_login_logo']); ?> " style="background-color: #EEEEEE;height: 50px;width:auto">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">移动端登录页logo，建议尺寸：宽116px*高30px。jpg，jpeg，png格式</div>
                    </div>
                </div>


                <!--移动端商城logo-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">移动端商城logo：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="shop_logo" type="hidden" value="<?php echo htmlentities($config['shop_logo']); ?>" >
                            <?php if(!empty($config['shop_logo'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" src="<?php echo htmlentities($config['shop_logo']); ?>" style="background-color:#EEEEEE ;height: 50px;width:auto">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">商城首页左上角logo，建议尺寸：宽172px*高48px。jpg，jpeg，png格式</div>
                    </div>
                </div>

                <!--pc端商城logo-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">pc端商城logo：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="pc_logo" type="hidden" value="<?php echo htmlentities($config['pc_logo']); ?>" >
                            <?php if(!empty($config['pc_logo'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" src="<?php echo htmlentities($config['pc_logo']); ?>" style="background-color:#EEEEEE ;height: 50px;width:auto">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">pc端商城首页左上角logo，建议尺寸：宽172px*高48px。jpg，jpeg，png格式</div>
                    </div>
                </div>


                <!--后台登录页logo-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">管理后台登录页logo：</label>
                    <div class="layui-inline" id="icon">
                        <div class="" style="height:80px;line-height:80px;">
                            <input name="login_logo" type="hidden" value="<?php echo htmlentities($config['login_logo']); ?>" >
                            <?php if(!empty($config['login_logo'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" style="width: auto;height: 50px" src="<?php echo htmlentities($config['login_logo']); ?>">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">管理后台登录页左上角logo，建议尺寸：宽152px*高42px。jpg，jpeg，png格式</div>
                    </div>
                </div>

                <!--后台登录页表单主图-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">管理后台登录页主图：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="admin_image" type="hidden" value="<?php echo htmlentities($config['admin_image']); ?>" >
                            <?php if(!empty($config['admin_image'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" src="<?php echo htmlentities($config['admin_image']); ?>">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">管理后台登录页主图，建议尺寸：宽500px*高500px。jpg，jpeg，png格式</div>
                    </div>
                </div>


                <!--后台登录页表单标题-->
                <div class="layui-form-item">
                    <label class="layui-form-label">管理后台登录页标题：</label>
                    <div class="layui-input-block">
                        <div class="layui-col-md4">
                            <input type="text" name="admin_title"  lay-verType="tips" autocomplete="off"  value="<?php echo htmlentities($config['admin_title']); ?>"  class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <span style="color: #a3a3a3;font-size: 9px">管理后台登录页表单标题</span>
                </div>

                <!--后台logo-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">后台logo：</label>
                    <div class="layui-inline" >
                        <div class="" style="height:80px;line-height:80px">
                            <input name="backstage_logo" type="hidden" value="<?php echo htmlentities($config['backstage_logo']); ?>" >
                            <?php if(!empty($config['backstage_logo'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img"  src="<?php echo htmlentities($config['backstage_logo']); ?> " style="background-color: #EEEEEE;height: 50px;width:auto">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">登录管理后台之后，左上角logo。建议尺寸：宽116px*高30px。jpg，jpeg，png格式</div>
                    </div>
                </div>


                <!--会员默认头像-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">会员默认头像：</label>
                    <div class="layui-inline" >

                        <div class="" style="height:80px;line-height:80px">
                            <input name="user_image" type="hidden" value="<?php echo htmlentities($config['user_image']); ?>" >
                            <?php if(!empty($config['user_image'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" src="<?php echo htmlentities($config['user_image']); ?>">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">商城会员默认头像，建议尺寸：宽200px*高200px。jpg，jpeg，png格式</div>

                    </div>
                </div>

                <!--  商品默认主图-->
                <div class="layui-form-item ">
                    <label class="layui-form-label">商品默认封面图：</label>
                    <div class="layui-inline" >

                        <div class="" style="height:80px;line-height:80px">
                            <input name="goods_image" type="hidden" value="<?php echo htmlentities($config['goods_image']); ?>" >
                            <?php if(!empty($config['goods_image'])): ?>
                            <div class="goods-img-add" style="display: none"></div>
                            <div class="goods-li">
                                <img class="goods-img" src="<?php echo htmlentities($config['goods_image']); ?>">
                                <a class="goods-img-del-x" style="display: none">x</a>
                            </div>
                            <?php else: ?>
                            <div class="goods-img-add"></div>
                            <?php endif; ?>
                        </div>
                        <div class=" layui-form-mid layui-word-aux">商城商品默认封面图，建议尺寸：宽400px*高400px。jpg，jpeg，png格式</div>

                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-sm <?php echo htmlentities($view_theme_color); ?>" lay-submit lay-filter="setmnp">确认</button>
                    </div>
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
    }).use(['index','table','like'], function(){
        var $ = layui.$
            ,form = layui.form
            ,like = layui.like;

        form.verify({

        });
        form.on('submit(setmnp)',function (data) {
            like.ajax({
                url: '<?php echo url("Basic/setWebsite"); ?>'
                ,data: data.field
                ,type: 'post'
                ,success: function(res){
                    //登入成功的提示与跳转
                    if(res.code == 1)
                    {
                        layer.msg(res.msg, {
                            offset: '15px'
                            ,icon: 1
                            ,time: 1500
                        }, function(){
                            location.href = location.href;
                        });
                     }}
            });
        });
        //上传图片
        like.imageUpload('.goods-img-add', function (uri, element) {
            var html = '<div class="goods-li">\n' +
                '<img class="goods-img" ' +
                'src="' + '/' + uri + '">' +
                '<a class="goods-img-del-x" style="display: none">x</a>' +
                '</div>';
            element.prev().val('/'+uri);
            element.parent().append(html);
            element.css('display','none');
        }, true);
        //删除图片
        $(document).on('click', '.goods-img-del-x', function () {
            $(this).parent().siblings('input').val('');
            $(this).parent().prev().css('display','block');
            $(this).parent().remove();
        });
        //显示图片
        $(document).on('click', '.goods-img', function () {
            var image = $(this).attr('src');
            like.showImg(image,600);
        });
        //  删除按钮的显示与隐藏
        $(document).on('mouseover', '.goods-img', function () {
            $(this).next().show();
        });
        $(document).on('mouseout', '.goods-img', function () {
            $(this).next().hide();
        });
        $(document).on('mouseover', '.goods-img-del-x', function () {
            $(this).show();
        });
        $(document).on('mouseout', '.goods-img-del-x', function () {
            $(this).hide();
        });
    });

</script>
</body>
</html>