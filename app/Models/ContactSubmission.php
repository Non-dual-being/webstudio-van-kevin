<?php

namespace App\Models;

use App\Data\ContactEnquiry;
use App\Enums\ContactService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSubmission extends Model
{
    protected $guarded = ['id'];

    // This model is never exposed as an Inertia prop or public resource.
    protected $hidden = ['name', 'email', 'body', 'attempt_hash', 'session_hash', 'payload_hash'];

    /** @return HasMany<ContactMailDelivery, $this> */
    public function deliveries(): HasMany
    {
        return $this->hasMany(ContactMailDelivery::class);
    }

    public function enquiry(): ContactEnquiry
    {
        return new ContactEnquiry(
            $this->name,
            $this->email,
            $this->service ? ContactService::from($this->service)->label() : 'Ik weet het nog niet',
            $this->body,
        );
    }
}
