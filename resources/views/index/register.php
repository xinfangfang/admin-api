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
<!--    <form class="layui-form">-->
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>用户名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="username" required="" lay-verify="required" value=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>密码
            </label>
            <div class="layui-input-inline">
                <input type="password" id="password" name="password" required="" lay-verify="required" value=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" onclick="add()" lay-filter="save" lay-submit="">
                保存
            </button>
        </div>
<!--    </form>-->
</div>
<script src="/lib/layui/layui.js" charset="utf-8">
</script>
<script src="/js/x-layui.js" charset="utf-8">
</script>
<script src="/js/jquery-3.5.1.min.js" charset="utf-8">
</script>
<script>
    function add()
    {
        var file = document.getElementById('file').files[0];
        console.log(file);
        var formData = new FormData();
        formData.append('file',file);
        $.ajax({
            url:"excel/add",
            // data:{'file':file},
            type:'post',
            data:formData,
            cache:false,  //默认是true，但是一般不做缓存
            processData:false, //用于对data参数进行序列化处理，这里必须false；如果是true，就会将FormData转换为String类型
            contentType:false,  //一些文件上传http协议的关系，自行百度，如果上传的有文件，那么只能设置为false
            success:function(res){
                // console.log(res);
                if(res.code == 0){
                    alert('添加成功');
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

                    parent.layer.close(index); //再执行关闭
                }else{
                    alert('添加失败')
                }
                console.log(res);
            }
        })
    }
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
