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
<div class="x-nav">
            <span class="layui-breadcrumb">
              <a><cite>首页</cite></a>
              <a><cite>目视化管理</cite></a>
              <a><cite>目视化列表</cite></a>
            </span>

</div>
<!--        <div class="x-body">-->
<!--            <form class="layui-form x-center" action="" style="width:800px">-->
<!--                <div class="layui-form-pane" style="margin-top: 15px;">-->
<!--                  <div class="layui-form-item">-->
<!--                    <label class="layui-form-label">日期范围</label>-->
<!--                    <div class="layui-input-inline">-->
<!--                      <input class="layui-input" placeholder="开始日" id="LAY_demorange_s">-->
<!--                    </div>-->
<!--                    <div class="layui-input-inline">-->
<!--                      <input class="layui-input" placeholder="截止日" id="LAY_demorange_e">-->
<!--                    </div>-->
<!--                    <div class="layui-input-inline">-->
<!--                      <input type="text" name="username"  placeholder="标题" autocomplete="off" class="layui-input">-->
<!--                    </div>-->
<!--                    <div class="layui-input-inline" style="width:80px">-->
<!--                        <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
</form>
<xblock><button class="layui-btn" onclick="question_add('添加问题','question-add.html','600','500')"><i class="layui-icon">&#xe608;</i>添加</button><span class="x-right" style="line-height:40px">共有数据： 条</span>
    <button class="layui-btn" onclick="question_add('筛选','question-add.html','600','500')"><i class="layui-icon"></i>导入excel筛选</button>
    <button class="layui-btn" onclick="question_add('下载模板','question-add.html','600','500')"><i class="layui-icon"></i>下载excel模板</button>
</xblock>
<table class="layui-table">
    <thead>
    <tr>
        <th>
            ID
        </th>
        <th>
            存在的危害因素
        </th>
        <th>
            目视化解决措施
        </th>
        <th>
            具体内容/标识名称
        </th>
        <th>
            设置依据
        </th>
        <th>
            图例
        </th>
        <th>
            尺寸
        </th>
        <th>材质工艺</th>
        <th>单价</th>
        <th>备注</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<!--            <div id="page"></div>-->
</div>
<script src="/lib/layui/layui.js" charset="utf-8"></script>
<script src="/js/x-layui.js" charset="utf-8"></script>
<script>
    layui.use(['laydate','element','laypage','layer'], function(){
        $ = layui.jquery;//jquery
        laydate = layui.laydate;//日期插件
        lement = layui.element();//面包导航
        laypage = layui.laypage;//分页
        layer = layui.layer;//弹出层

        //以上模块根据需要引入
        laypage({
            cont: 'page'
            ,pages: 100
            ,first: 1
            ,last: 100
            ,prev: '<em><</em>'
            ,next: '<em>></em>'
        });

        var start = {
            min: laydate.now()
            ,max: '2099-06-16 23:59:59'
            ,istoday: false
            ,choose: function(datas){
                end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };

        var end = {
            min: laydate.now()
            ,max: '2099-06-16 23:59:59'
            ,istoday: false
            ,choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };

        document.getElementById('LAY_demorange_s').onclick = function(){
            start.elem = this;
            laydate(start);
        }
        document.getElementById('LAY_demorange_e').onclick = function(){
            end.elem = this
            laydate(end);
        }
    });

    //批量删除提交
    function delAll () {
        layer.confirm('确认要删除吗？',function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
        });
    }

    function question_show (argument) {
        layer.msg('可以跳到前台具体问题页面',{icon:1,time:1000});
    }
    /*添加*/
    function question_add(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    //编辑
    function question_edit (title,url,id,w,h) {
        x_admin_show(title,url,w,h);
    }

    /*删除*/
    function question_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
        });
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
