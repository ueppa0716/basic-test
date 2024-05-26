@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('page_top')
    <form class="search-form" action="/show" method="get">
        @csrf
        <p class="page_txt">
            <!-- 入力フィールド -->
            <input class="search-form__date" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください"
                value="{{ request('keyword') }}">

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
    <table class="user__table">
        <tr class="user__row">
            <th class="user__label">名前</th>
            <th class="user__label">メールアドレス</th>
            <th class="user__label">勤怠実績</th>
        </tr>
        @if (isset($users))
            @foreach ($users as $user)
                <tr class="user__row">
                    <th class="user__label">{{ $user->name }}</th>
                    <th class="user__label">{{ $user->email }}</th>
                    <th class="user__label"><a class="" href="">詳細</a></th>
                </tr>
            @endforeach
        @endif
    </table>
@endsection('content')

@section('page_bottom')
    @if (isset($users))
        {{ $users->appends(request()->input())->links('vendor.pagination.custom') }}
    @endif
@endsection('page_bottom')
