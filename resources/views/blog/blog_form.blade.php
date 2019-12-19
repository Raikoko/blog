<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>添加</title>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
</head>

<body>

        <form class="layui-form" id="blog-form" style="width: 400px;padding-top: 30px;">
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

</body>
<script>
    layui.use('form', function(){
        let form = layui.form;
        let $ = layui.$;
        let layer = layui.layer;


    });
</script>
</html>
