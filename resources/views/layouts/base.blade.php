<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
</head>
<body>
@yield('content')
</body>
</html>
