@extends('errors.layouts.base')

@section('title', '405 Method Not Allowed')

@section('message', 'このリクエストに対するメソッドは許可されていません。')

@section('detail', 'サーバーは、リクエストされたリソースに対するメソッド（GET、POST、PUT、DELETEなど）をサポートしていないか、または適切に構成されていません。')
