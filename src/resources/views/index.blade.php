@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading content__heading">{{ $user->name }}さんお疲れ様です！</h2>
    @if (session('status'))
    <div class="alert">
        {{ session('status') }}
    </div>
    @endif
    <div class="login-form__group">
        <div class="login-form__inner">
            <form class="login-form__form" action="/work_start" method="post">
                @csrf
                @if(optional($workInfo)->work_start && empty(optional($workInfo)->work_end))
                <button class="login-form__btn btn" type="submit" value="work_start" name="work_start" disabled>勤務開始</button>
                @else
                <button class="login-form__btn btn" type="submit" value="work_start" name="work_start">勤務開始</button>
                @endif
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/work_end" method="post">
                @csrf
                @if(empty(optional($workInfo)->work_start))
                <button class="login-form__btn btn" type="submit" value="work_end" name="work_end" disabled>勤務終了</button>
                @elseif(optional($workInfo)->work_start && optional($workInfo)->work_end)
                <button class="login-form__btn btn" type="submit" value="work_end" name="work_end" disabled>勤務終了</button>
                @else
                <button class="login-form__btn btn" type="submit" value="work_end" name="work_end">勤務終了</button>
                @endif
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/break_start" method="post">
                @csrf
                @if(optional($breakInfo)->break_start && empty(optional($breakInfo)->break_end))
                <button class="login-form__btn btn" type="submit" value="break_start" name="break_start" disabled>休憩開始</button>
                @elseif(optional($workInfo)->work_start && empty(optional($workInfo)->work_end))
                <button class="login-form__btn btn" type="submit" value="break_start" name="break_start">休憩開始</button>
                @else
                <button class="login-form__btn btn" type="submit" value="break_start" name="break_start" disabled>休憩開始</button>
                @endif
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/break_end" method="post">
                @csrf
                @if(empty(optional($breakInfo)->break_start))
                <button class="login-form__btn btn" type="submit" value="break_end" name="break_end" disabled>休憩終了</button>
                @elseif(optional($breakInfo)->break_start && optional($breakInfo)->break_end)
                <button class="login-form__btn btn" type="submit" value="break_end" name="break_end" disabled>休憩終了</button>
                @else
                <button class="login-form__btn btn" type="submit" value="break_end" name="break_end">休憩終了</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection('content')