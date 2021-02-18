<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('webset.title') }}</title>
    <meta name="Keywords" content="{{ config('webset.keywords')  }}">
    <meta name="Description" content="{{ config('webset.description')  }}">
    <link rel="stylesheet" href="/assets/layui/css/layui.css">
    <link rel="stylesheet" href="/assets/style/main.css">
    <link rel="shortcut icon" href="/assets/style/favicon.ico" />
    @if(\request()->server()['REQUEST_SCHEME'] == "https")
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

</head>
<body>
<div class="sh-header">
    <div class="layui-row">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-sm12">
            <ul class="layui-nav layui-bg-blue" lay-filter="">
                <li class="layui-nav-item logo"><p style="font-size: 16px; font-weight: 500" href="javascript:;" >{{ config('webset.text_logo') }} | </p></li>
                <li class="layui-nav-item @if(\Illuminate\Support\Facades\Request::path() == '/') layui-this @endif" ><a href="/">{{__('system.home_page')}}</a></li>
                <li class="layui-nav-item @if(\Illuminate\Support\Facades\Request::path() == 'searchOrder') layui-this @endif"><a href="{{ url('searchOrder') }}"><i class="layui-icon layui-icon-search"></i>{{ __('system.order_search') }}</a></li>
            </ul>
        </div>
    </div>

</div>
