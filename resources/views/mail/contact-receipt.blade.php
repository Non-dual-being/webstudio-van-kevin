@extends('mail.layout')

@section('title', 'Je bericht aan Kevin Webstudio is ontvangen')

@section('content')
    <p class="accent" style="margin: 0 0 12px; color: #92400e; font-size: 12px; line-height: 18px; font-weight: bold; letter-spacing: 1px;">BERICHT ONTVANGEN</p>
    <h1 class="ink" style="margin: 0 0 28px; color: #0f172a; font-size: 28px; line-height: 35px; font-weight: bold;">Bedankt voor je bericht.</h1>
    <p style="margin: 0 0 20px;">{{ $greeting }}</p>
    <p style="margin: 0 0 20px;">Ik bekijk je vraag en neem contact met je op om samen de volgende stap te bespreken.</p>
    <p style="margin: 0 0 28px;">Wil je nog iets toevoegen? Je kunt deze e-mail gewoon beantwoorden.</p>
    <p style="margin: 0;">Met vriendelijke groet,<br><strong>Kevin</strong></p>
@endsection
