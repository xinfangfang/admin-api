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
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <link rel="stylesheet" href="/css/x-admin.css" media="all">
    </head>

    <body>
        <div class="x-body">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>个人防护用品名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="username" name="labour_name" required="" lay-verify="required" value="admin"
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>危害因素
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" value="18925139194" name="labour_harm" required="" lay-verify="phone"
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>接触部位/伤害部位
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="labour_contact" required="" lay-verify="email" value="113664000@qq.com"
                        autocomplete="off" class="layui-input">
                    </div>

                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>参数要求
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="labour_requirement" required="" lay-verify="email" value="113664000@qq.com"
                               autocomplete="off" class="layui-input">
                    </div>

                </div>
                <div class="layui-form-item">
                    <label for="role" class="layui-form-label">
                        <span class="x-red">*</span>品牌
                    </label>
                    <div class="layui-input-inline">
                      <select name="brand_id">
                        <option value="">请选择品牌</option>
                      </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                        <span class="x-red">*</span>型号
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_pass" name="labour_model" required="" lay-verify="pass"
                        autocomplete="off" class="layui-input" value="123456">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        <span class="x-red">*</span>防护参数
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="labour_protectd" required="" lay-verify="repass" value="123456"
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        <span class="x-red">*</span>价格
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="labour_price" required="" lay-verify="repass" value="123456"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        <span class="x-red">*</span>备注
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="remarks" required="" lay-verify="repass" value="123456"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="save" lay-submit="">
                        保存
                    </button>
                </div>
            </form>
        </div>
        <script src="/lib/layui/layui.js" charset="utf-8">
        </script>
        <script src="/js/x-layui.js" charset="utf-8">
        </script>
        <script>
            layui.use(['form','layer'], function(){
                $ = layui.jquery;
              var form = layui.form()
              ,layer = layui.layer;

              //自定义验证规则
              form.verify({
                nikename: function(value){
                  if(value.length < 5){
                    return '昵称至少得5个字符啊';
                  }
                }
                ,pass: [/(.+){6,12}$/, '密码必须6到12位']
                ,repass: function(value){
                    if($('#L_pass').val()!=$('#L_repass').val()){
                        return '两次密码不一致';
                    }
                }
              });

              //监听提交
              form.on('submit(save)', function(data){
                console.log(data);
                //发异步，把数据提交给php
                layer.alert("保存成功", {icon: 6},function () {
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
                return false;
              });


            });
        </script>
        <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
          var s = document.getElementsByTagName("script")[0];
          s.parentNode.insertBefore(hm, s);
        })();
        </script>
    </body>

</html>
