@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/work.css') }}">
@endsection

@section('page_top')
    <form class="search-form" action="/work" method="get">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="page_txt">
            <!-- 前の月に移動するボタン -->
            <button class="page_txt--deco" type="submit" name="prev" value="prev">&lt;</button>
            &nbsp;&nbsp;&nbsp;

            <!-- 年月入力フィールド -->
            <input class="search-form__date" type="month" name="date" value="{{ $date }}">
            &nbsp;&nbsp;&nbsp;

            <!-- 次の月に移動するボタン -->
            <button class="page_txt--deco" type="submit" name="next" value="next">&gt;</button>
            <!-- 検索ボタン -->
            <input class="search-form__btn" type="submit" value="検索">
            <p class="page_top__heading">{{ $user->name }}さんの勤怠実績</p>
        </div>
    </form>

    @if (session('status'))
        <div class="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection('page_top')

@section('content')
    <table class="work__table">
        <tr class="work__row">
            <th class="work__label">日付</th>
            <th class="work__label">勤務開始</th>
            <th class="work__label">勤務終了</th>
            <th class="work__label">休憩時間</th>
            <th class="work__label">勤務時間</th>
        </tr>
        @if (isset($workInfos))
            @foreach ($workInfos as $workInfo)
                <tr class="work__row">
                    <th class="work__label">{{ date('m/d', strtotime($workInfo->work_start)) }}</th>
                    <th class="work__label">{{ date('H:i:s', strtotime($workInfo->work_start)) }}</th>
                    <th class="work__label">
                        @if (!empty($workInfo->work_end))
                            {{ date('H:i:s', strtotime($workInfo->work_end)) }}
                        @else
                            <!-- ここに何か表示したい場合のコードを追加 -->
                        @endif
                    </th>
                    <th class="work__label">{{ $workInfo->total_rest }}</th>
                    <th class="work__label">{{ $workInfo->total_work }}</th>
                </tr>
            @endforeach
        @endif
    </table>
@endsection('content')

@section('page_bottom')
    @if (isset($workInfos))
        {{ $workInfos->appends(request()->input())->links('vendor.pagination.custom') }}
    @endif
@endsection('page_bottom')
