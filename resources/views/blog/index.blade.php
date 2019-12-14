<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
    </head>

<body>

    <div style="padding-left: 20%;padding-top: 10%">
        <a href="/blog_create_form">创建</a>

    <table>
        <tr>
            <th>title</th>
            <th>text</th>
        </tr>
        @foreach($blogs as $blog)
            <tr>
                <td>{{$blog->title}}</td>
                <td>{{$blog->text}}</td>
                <td><a href="/blog_detail/{{$blog->id}}">查看详情</a></td>
                <td><a href="/blog_edit_form/{{json_encode($blog)}}">编辑</a></td>
                <td><a href="/blog_del/{{$blog->id}}">删除</a></td>
            </tr>
        @endforeach
    </table>
    </div>

    </body>
</html>
