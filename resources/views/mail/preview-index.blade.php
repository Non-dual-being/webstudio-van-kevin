<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mailvoorbeelden — Kevin Webstudio</title>
</head>
<body style="margin: 32px auto; padding: 0 24px; max-width: 600px; font: 16px/1.6 Arial, Helvetica, sans-serif; color: #0f172a; background: #fafaf9;">
    <h1 style="font-size: 28px;">Mailvoorbeelden</h1>
    <p>Lokale weergave met fictieve contactgegevens onder example.com. Er is niets verzonden of als aanvraag opgeslagen. De handtekening bevat Kevins zakelijke contactgegevens.</p>
    <ul>
        @foreach (['aanvraag' => 'Aanvraagmelding', 'ontvangst' => 'Ontvangstbevestiging', 'aanvraag-lang' => 'Aanvraag met lange inhoud', 'ontvangst-lang' => 'Bevestiging bij lange invoer', 'handtekening' => 'Persoonlijke handtekening'] as $file => $label)
            <li>{{ $label }}: <a href="{{ $file }}.html">HTML</a> · <a href="{{ $file }}.txt">tekst</a></li>
        @endforeach
    </ul>
    <p>Deze browserweergave laat het ontwerp zien. Controle in echte mailprogramma's en ontvangen berichtheaders volgt na de bewuste SMTP-test.</p>
</body>
</html>
