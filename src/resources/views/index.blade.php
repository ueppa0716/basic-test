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
                <button class="login-form__btn btn" type="submit" value="work_start" name="work_start">勤務開始
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/work_end" method="post">
                @csrf
                <button class="login-form__btn btn" type="submit" value="work_end" name="work_end">勤務終了
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/break_start" method="post">
                @csrf
                <button class="login-form__btn btn" type="submit" value="break_start" name="break_start">休憩開始
            </form>
        </div>
        <div class="login-form__inner">
            <form class="login-form__form" action="/break_end" method="post">
                @csrf
                <button class="login-form__btn btn" type="submit" value="break_end" name="break_end">休憩終了
            </form>
        </div>
    </div>
</div>
@endsection('content')