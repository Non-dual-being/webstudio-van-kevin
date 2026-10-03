@extends('mail.layout')

@section('title', 'Nieuwe contactaanvraag — Kevin Webstudio')

@section('content')
    <p class="accent" style="margin: 0 0 12px; color: #92400e; font-size: 12px; line-height: 18px; font-weight: bold; letter-spacing: 1px;">CONTACTAANVRAAG</p>
    <h1 class="ink" style="margin: 0 0 12px; color: #0f172a; font-size: 28px; line-height: 35px; font-weight: bold;">Een nieuw bericht voor je.</h1>
    <p class="muted" style="margin: 0 0 28px; color: #475569;">Hieronder staan de contactgegevens en de vraag.</p>

    <p style="margin: 0 0 16px;"><span class="muted" style="color: #475569; font-size: 12px; line-height: 18px;">Naam</span><br><strong>{{ $enquiry->name }}</strong></p>
    <p style="margin: 0 0 16px;"><span class="muted" style="color: #475569; font-size: 12px; line-height: 18px;">E-mailadres</span><br><a class="link" href="mailto:{{ rawurlencode($enquiry->email) }}" style="color: #334155; text-decoration: underline; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word;">{{ $enquiry->email }}</a></p>
    <p style="margin: 0 0 28px;"><span class="muted" style="color: #475569; font-size: 12px; line-height: 18px;">Gekozen dienst</span><br>{{ $enquiry->service }}</p>

    <h2 class="ink" style="margin: 0 0 12px; color: #0f172a; font-size: 18px; line-height: 25px;">Het bericht</h2>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width: 100%; table-layout: fixed;">
        <tr>
            <td class="detail ink" bgcolor="#f8fafc" style="padding: 20px; border-left: 3px solid #b45309; background-color: #f8fafc; color: #0f172a; font-size: 16px; line-height: 26px; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word;">
                {!! nl2br(e($enquiry->body)) !!}
            </td>
        </tr>
    </table>
    <p style="margin: 28px 0 0;"><a class="link" href="mailto:{{ rawurlencode($enquiry->email) }}" style="color: #0f172a; font-weight: bold; text-decoration: underline;">Beantwoord deze aanvraag</a></p>
    <p class="muted" style="margin: 8px 0 0; color: #475569; font-size: 13px; line-height: 21px;">Je kunt ook Beantwoorden in je mailprogramma gebruiken.</p>
@endsection
