@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css')}}">
@endsection

@section('page_top')
<form class="search-form" action="/search" method="get">
    @csrf
    <p class="page_txt">
        <input class="page_txt--deco" type="submit" value="<"></input>
        &nbsp;&nbsp;&nbsp;
        <input class="search-form__date" type="date" name="date" value="{{request('date')}}">
        &nbsp;&nbsp;&nbsp;
        <input class="page_txt--deco" type="submit" value=">"></input>
    </p>
</form>
@if (session('status'))
<div class="alert">
    {{ session('status') }}
</div>
@endif
@endsection('page_top')

@section('content')
<table class="attendance__table">
    <tr class="attendance__row">
        <th class="attendance__label">名前</th>
        <th class="attendance__label">勤務開始</th>
        <th class="attendance__label">勤務終了</th>
        <th class="attendance__label">休憩時間</th>
        <th class="attendance__label">勤務時間</th>
    </tr>
    @if(isset($userInfos))
    @foreach($userInfos as $userInfo)
    <tr class="attendance__row">
        <th class="attendance__label">{{ $userInfo->user->name }}</th>
        <th class="attendance__label">{{ date('H:i:s', strtotime($userInfo->work_start)) }}</th>
        <th class="attendance__label">@if (!empty($userInfo->work_end))
            {{ date('H:i:s', strtotime($userInfo->work_end)) }}
            @else
            <!-- ここに何か表示したい場合のコードを追加 -->
            @endif
        </th>
        <th class="attendance__label">{{ $userInfo->total_rest }}</th>
        <th class="attendance__label">{{ $userInfo->total_work }}</th>
    </tr>
    @endforeach
    @endif
</table>
@endsection('content')

@section('page_bottom')
@if(isset($userInfos))
{{ $userInfos->appends(request()->input())->links('vendor.pagination.custom') }}
@endif
@endsection('page_bottom')