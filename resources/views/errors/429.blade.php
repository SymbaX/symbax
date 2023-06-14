@extends('errors.layouts.base')

@section('title', '429 Too Many Requests')

@section('message', 'アクセスしようとしたページは表示できませんでした。')

@section('detail', 'このエラーは、一定時間内に多くのリクエストがあったことを意味します。時間を置いて再度アクセスしてください。')