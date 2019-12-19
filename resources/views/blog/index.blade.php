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
        <table class="layui-table" id="index_data">
        <colgroup>
            <col width="100">
            <col width="150">
            <col width="100">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>title</th>
                <th>text</th>
                <th>option</th>
            </tr>
        </thead>
        @foreach($blogs as $blog)
            <tr>
                <td>{{$blog->title}}</td>
                <td>{{$blog->text}}</td>
                <td>
                    <a class="layui-btn layui-btn-xs" href="/blog_detail/{{$blog->id}}">查看详情</a>
                    <a class="layui-btn layui-btn-xs"href="/blog_edit_form/{{json_encode($blog)}}">编辑</a>
                    <a class="layui-btn layui-btn-xs"href="/blog_del/{{$blog->id}}">删除</a>
                </td>
            </tr>
        @endforeach
    </table>
    </div>

<div style="display: none" id="blog-form-box">
    <form class="layui-form"  id="blog-form" style="width: 400px;padding-top: 30px;">
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
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

</div>

</body>

<script>
    layui.use(['layer','table'],function () {
        let table = layui.table;
        let layer = layui.layer;
        let form = layui.form;
        let $ = layui.$;

        let active = {
            add : function () {
                layer.open({
                    type:1,
                    title:'添加',
                    // content:'blog_create_form',
                    content:$('#blog-form-box'),
                    area:['500px','500px'],
                    success:function (index) {
                        // form.render(null, 'blog-form');  //加载成功后重新渲染当前表单
                        form.on('submit(formDemo)', function(data){
                            $.post('blog_create',data.field,function (res) {
                                    layer.msg(res.msg,{time:2000});
                            });
                            // table.reload('index_data');
                            layer.close(index);
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

