<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>
            X-admin v1.0
        </title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <link rel="stylesheet" href="/css/x-admin.css" media="all">
    </head>

    <body style="background-color: #393D49">
        <div class="x-box">
            <div class="x-top">

            </div>
            <div class="x-mid">
                <div class="x-avtar">
                    <img src="/images/logo.png" alt="">
                </div>
                <div class="input">
<!--                    <form class="layui-form" action="user/check" method="post">-->
                        <div class="layui-form-item x-login-box">
                            <label for="username" class="layui-form-label">
                                <i class="layui-icon">&#xe612;</i>
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" id="username" name="username" required="" lay-verify="用户名"
                                autocomplete="off" placeholder="username" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item x-login-box">
                            <label for="pass" class="layui-form-label">
                                <i class="layui-icon">&#xe628;</i>
                            </label>
                            <div class="layui-input-inline">
                                <input type="password" id="pass" name="pass" required="" lay-verify="pass"
                                autocomplete="off" placeholder="密码" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" id="loginbtn">
                            <button  class="layui-btn" onclick="login()" lay-filter="save" lay-submit="">
                                登 录
                            </button>
                        </div>
<!--                    </form>-->
                </div>
            </div>
        </div>
        <p style="color:#fff;text-align: center;">Copyright © 2017.Company name All rights X-admin </p>
        <script src="/lib/layui/layui.js" charset="utf-8">
        </script>
        <script src="/js/jquery-3.5.1.min.js" charset="utf-8"></script>
        <script>
            function login()
            {
                var username = $('#username').val();
                var password = $('#pass').val();
                console.log(username+password);
                $.ajax({
                    url:"user/check",
                    data:{'username':username,'password':password},
                    type:'post',
                    dataType:'json',
                    // data:formData,
                    // cache:false,  //默认是true，但是一般不做缓存
                    // processData:false, //用于对data参数进行序列化处理，这里必须false；如果是true，就会将FormData转换为String类型
                    // contentType:false,  //一些文件上传http协议的关系，自行百度，如果上传的有文件，那么只能设置为false
                    success:function(res){
                        // window.location.href='index';
                        if(res.code == 0){
                            alert('登录成功');
                            window.location.href='index';
                        }else{
                            alert(res.data[0])
                        }
                        console.log(res);
                    }
                })
            }
            // layui.use(['form'],
            // function() {
            //     $ = layui.jquery;
            //     var form = layui.form(),
            //     layer = layui.layer;
            //
            //     $('.x-login-right li').click(function(event) {
            //         color = $(this).attr('color');
            //         $('body').css('background-color', color);
            //     });
            //
            //     //监听提交
            //     form.on('submit(save)',
            //     function(data) {
            //         console.log(data);
            //         layer.alert(JSON.stringify(data.field), {
            //           title: '最终的提交信息'
            //         },function  () {
            //             location.href = "./index.html";
            //         })
            //         return false;
            //     });
            //
            // });

        </script>
    </body>

</html>
