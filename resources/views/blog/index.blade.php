<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>首页</title>
        <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
        <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
</head>

<body>

    <div style="padding-left: 20%;padding-top: 10%;width: 800px;">
        <button type="button" class="layui-btn" id="add" data-type="add">
            <i class="layui-icon">&#xe608;</i> 添加
        </button>
        <table id="table-blog" lay-filter="blog-test"></table>
    </div>

<div style="display: none" id="blog-form-box">
    <form class="layui-form"  id="blog-form" lay-filter="blog-form"style="width: 400px;padding-top: 30px;">
        {{csrf_field()}}
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea name="text" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input type="hidden" name="id" autocomplete="off" class="layui-input" value="">
    </form>

</div>

</body>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>
    layui.use(['layer','table'],function () {
        let table = layui.table;
        let layer = layui.layer;
        let form = layui.form;
        let $ = layui.$;
        //第一个实例
        table.render({
            elem: '#table-blog'
            ,url: '/blog/getIndex' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', width:80, sort: true}
                ,{field: 'title', title: '标题', width:80}
                ,{field: 'text', title: '内容', minWidth:80}
                ,{field: 'option', title: '操作', width:220,toolbar:'#barDemo'}
            ]]
        });

        table.on('tool(blog-test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

            if(layEvent === 'detail'){ //查看
                //do somehing
            } else if(layEvent === 'del'){ //删除
                layer.confirm('确定要删除吗', function(index){
                    $.post('/blog/blog_del',{id:data.id, '_token':'{{csrf_token()}}'},function (res) {
                        layer.msg(res.msg);
                        if (res.code == 0){
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                        }
                        layer.close(index);
                    });
                });
            } else if(layEvent === 'edit'){ //编辑
                layer.open({
                    type:1,
                    title:'编辑',
                    content:$('#blog-form-box'),
                    area:['500px','500px'],
                    success:function (layero,index) {
                        form.val("blog-form", data);    //表单赋值
                        form.on('submit(submit)', function(data){
                            $.post('/blog/blog_edit',data.field,function (res) {
                                layer.msg(res.msg,{time:2000});
                                if (res.code == 0){
                                    layer.close(index);
                                    //同步更新缓存对应的值
                                    obj.update(data.field);
                                }
                            });
                            return false;
                        });
                    }
                });
            }
        });


        let active = {
            add : function () {
                layer.open({
                    type:1,
                    title:'添加',
                    content:$('#blog-form-box'),
                    area:['500px','500px'],
                    success:function (layero,index) {
                        // form.render(null, 'blog-form');  //加载成功后重新渲染当前表单
                        form.on('submit(submit)', function(data){
                            $.post('blog_create',data.field,function (res) {
                                    layer.msg(res.msg,{time:2000});
                            });
                            layer.close(index);
                            table.reload('table-blog');
                            return false;
                        });
                    }
                })
            }
        };

        $('#add').on('click',function () {
            let type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        })

    });

</script>
</html>

