<?php

namespace App\Support;

use App\Data\ContactEnquiry;

class StudioMailExamples
{
    public static function enquiry(bool $long = false): ContactEnquiry
    {
        return new ContactEnquiry(
            name: $long ? 'Robin '.str_repeat('Voorbeeld', 14) : 'Robin Voorbeeld',
            email: $long
                ? str_repeat('voorbeeld', 6).'@'.str_repeat('voorbeeld', 6).'.example.com'
                : 'robin.voorbeeld@example.com',
            service: 'Website op maat',
            body: $long
                ? "Dit is een fictief lang voorbeeld voor de weergavecontrole.\n\n"
                    .str_repeat("Ik wil informatie voor bezoekers overzichtelijk presenteren. Ook op een telefoon moet de tekst prettig leesbaar zijn.\n\n", 5)
                    .'Een lang woord: '.str_repeat('voorbeeld', 30)
                    ."\n\nSpeciale tekens: café, ideeën, € en &. Ingevoerde HTML blijft tekst: <strong>voorbeeld</strong>."
                : "Dit is een fictieve aanvraag om de e-mailopmaak te bekijken.\n\nIk zoek een overzichtelijke website voor een klein initiatief. Kunnen we bespreken welke informatie en functies daarbij passen?\n\nMet vriendelijke groet,\nRobin Voorbeeld",
        );
    }
}
