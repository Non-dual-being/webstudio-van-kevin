<?php

namespace App\Http\Controllers;

use App\Actions\StoreContactSubmission;
use App\Enums\ContactService;
use App\Http\Requests\StoreContactRequest;
use App\Support\ContactFormAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ContactController extends Controller
{
    public function create(Request $request, ContactFormAttempt $attempt): Response
    {
        return Inertia::render('Contact', [
            'attempt' => $attempt->issue($request->session()),
            'services' => ContactService::options(),
            'received' => (bool) $request->session()->get('contact.received', false),
        ]);
    }

    public function store(StoreContactRequest $request, StoreContactSubmission $store): RedirectResponse
    {
        try {
            $store->store(
                [
                    'name' => $request->string('name')->toString(),
                    'email' => $request->string('email')->toString(),
                    'service' => $request->filled('service') ? $request->string('service')->toString() : null,
                    'body' => $request->string('body')->toString(),
                    'attempt' => $request->string('attempt')->toString(),
                ],
                $request->session()->get('contact.owner'),
                $request->ip() ?? 'unknown',
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable) {
            Log::error('Contactaanvraag niet opgeslagen.', ['category' => 'submission_store_failed']);

            return to_route('contact.create')->withErrors(['form' => 'Opslaan is nu niet gelukt. Je tekst blijft staan. Probeer opnieuw of mail naar info@kevinwebstudio.nl.']);
        }

        return to_route('contact.create')->with('contact.received', true);
    }
}
