@extends('mails.template.default_template')
@section('content')
    You are receiving this because you (or someone else) requested the reset of the '{{$data->user}}' npm user account.<br><br>

    Please click on the following link, or paste this into your browser to complete the process: <br>
    ※This link is only valid for 24 hours<br><br>
    <a href="{{ $data->link }}">{{ $data->link}}</a><br><br>

    If you received this in error, you can safely ignore it.<br><br>

    You can reply to this message, or email {{$data->admin}} if you have questions.
@endsection
