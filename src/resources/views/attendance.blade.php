@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('page_top')
    <form class="search-form" action="/search" method="get">
        @csrf
        <p class="page_txt">
            <!-- 前の日付に移動するボタン -->
            <button class="page_txt--deco" type="submit" name="prev" value="prev">&lt;</button>
            &nbsp;&nbsp;&nbsp;

            <!-- 日付入力フィールド -->
            <input class="search-form__date" type="date" name="date" value="{{ $date }}">
            &nbsp;&nbsp;&nbsp;

            <!-- 次の日付に移動するボタン -->
            <button class="page_txt--deco" type="submit" name="next" value="next">&gt;</button>
            <!-- 検索ボタン -->
            <input class="search-form__btn" type="submit" value="検索">
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
        @if (isset($userInfos))
            @foreach ($userInfos as $userInfo)
                <tr class="attendance__row">
                    <th class="attendance__label">
                        <form class="" action="/work" method="get">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $userInfo->user_id }}">
                            <input class="work__btn" type="submit" value="{{ $userInfo->user->name }}">
                        </form>
                    </th>
                    <th class="attendance__label">{{ date('H:i:s', strtotime($userInfo->work_start)) }}</th>
                    <th class="attendance__label">
                        @if (!empty($userInfo->work_end))
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
    @if (isset($userInfos))
        {{ $userInfos->appends(request()->input())->links('vendor.pagination.custom') }}
    @endif
@endsection('page_bottom')
