<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>

    <div style="padding-left: 20%;padding-top: 10%">
        @if(isset($blog))
        <form action="{{url('blog_edit')}}" method="post">
        @else
        <form action="blog_create" method="post">
        @endif
            {{ csrf_field() }}
            <div><label>标题</label><input type="text" name="title" value="{{isset($blog) ? json_decode($blog)->title : ''}}"></div>
            <div style="padding-top: 20px;"><label>内容</label><input type="text" name="text" value="{{isset($blog) ? json_decode($blog)->text : ''}}"></div>
            <input type="hidden" name="id" value="{{isset($blog) ? json_decode($blog)->id : ''}}">
            <div style="padding-top: 10px">
                @if(isset($blog))
                    <button type="submit">编辑</button>
                @else
                    <button type="submit">提交</button>
                @endif
            </div>
        </form>

    </div>

</body>
</html>
