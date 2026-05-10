<?php

namespace App\Livewire\Public;

use App\Jobs\SendCoupleNotificationEmail;
use App\Jobs\SendGuestRsvpEmail;
use App\Models\Guest;
use App\Models\GuestCompanion;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class RsvpForm extends Component
{
    public int    $step       = 1;
    public int    $totalSteps = 4;

    // Step 1
    public string $full_name = '';
    public string $email     = '';
    public string $phone     = '';

    // Step 2
    public string $attending = '';
    public string $decline_reason = '';

    // Companions
    public bool  $coming_with_someone = false;
    public array $companions = [];

    // Step 3
    public array $relationship = [];

    // State
    public bool   $submitted    = false;
    public bool   $isEditing    = false;
    public ?int   $editGuestId  = null;
    public string $qrCodeSvg    = '';
    public bool   $emailExists  = false;
    public string $existingName = '';

    protected function rules(): array
    {
        $rules = [];
        if ($this->step === 1) {
            $rules = [
                'full_name' => 'required|min:2|max:120',
                'email'     => 'required|email|max:120',
                'phone'     => 'nullable|max:30',
            ];
        }
        if ($this->step === 2) {
            $rules = ['attending' => 'required|in:yes,no'];
            if ($this->attending === 'yes' && $this->coming_with_someone) {
                $rules['companions.*.name'] = 'required|min:2';
            }
        }
        if ($this->step === 3) {
            $rules = ['relationship' => 'required|array|min:1'];
        }
        return $rules;
    }

    public function mount(): void
    {
        $this->companions = [];
    }

    public function updatedEmail(): void
    {
        $this->emailExists  = false;
        $this->existingName = '';
        if ($this->email && filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $existing = Guest::where('email', strtolower(trim($this->email)))->first();
            if ($existing) {
                $this->emailExists  = true;
                $this->existingName = $existing->full_name;
            }
        }
    }

    public function loadForEdit(): void
    {
        $guest = Guest::where('email', strtolower(trim($this->email)))->first();
        if (!$guest) return;

        $this->isEditing   = true;
        $this->editGuestId = $guest->id;
        $this->full_name   = $guest->full_name;
        $this->phone       = $guest->phone ?? '';
        $this->attending   = $guest->attending;
        $this->relationship = $guest->relationship ?? [];
        $this->decline_reason = $guest->decline_reason ?? '';
        $this->companions  = $guest->companions->map(fn($c) => ['name' => $c->name, 'relation' => $c->relation])->toArray();
        $this->coming_with_someone = count($this->companions) > 0;
        $this->emailExists = false;
        $this->step = 1;
    }

    public function nextStep(): void
    {
        $this->validate($this->rules());

        // Step 1: check email duplicate on fresh form (not editing)
        if ($this->step === 1 && !$this->isEditing) {
            $existing = Guest::where('email', strtolower(trim($this->email)))->first();
            if ($existing) {
                $this->emailExists  = true;
                $this->existingName = $existing->full_name;
                return;
            }
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) $this->step--;
    }

    public function addCompanion(): void
    {
        $this->companions[] = ['name' => '', 'relation' => ''];
    }

    public function removeCompanion(int $index): void
    {
        array_splice($this->companions, $index, 1);
    }

    public function toggleRelationship(string $value): void
    {
        if (in_array($value, $this->relationship)) {
            $this->relationship = array_values(array_filter($this->relationship, fn($r) => $r !== $value));
        } else {
            $this->relationship[] = $value;
        }
    }

    public function submit(): void
    {
        $this->validate($this->rules());

        $prevAttending = null;

        if ($this->isEditing && $this->editGuestId) {
            $guest = Guest::findOrFail($this->editGuestId);
            $prevAttending = $guest->attending;

            $guest->update([
                'full_name'      => trim($this->full_name),
                'phone'          => $this->phone,
                'attending'      => $this->attending,
                'decline_reason' => $this->attending === 'no' ? $this->decline_reason : null,
                'relationship'   => $this->relationship,
                'qr_token'       => $this->attending === 'yes'
                    ? ($guest->qr_token ?: Guest::generateQrToken())
                    : null,
                'qr_used'        => $this->attending === 'yes' ? $guest->qr_used : false,
            ]);

            $guest->companions()->delete();
        } else {
            $guest = Guest::create([
                'full_name'      => trim($this->full_name),
                'email'          => strtolower(trim($this->email)),
                'phone'          => $this->phone,
                'attending'      => $this->attending,
                'decline_reason' => $this->attending === 'no' ? $this->decline_reason : null,
                'relationship'   => $this->relationship,
                'qr_token'       => $this->attending === 'yes' ? Guest::generateQrToken() : null,
            ]);
        }

        // Save companions
        if ($this->attending === 'yes' && $this->coming_with_someone) {
            foreach ($this->companions as $comp) {
                if (!empty($comp['name'])) {
                    $guest->companions()->create(['name' => $comp['name'], 'relation' => $comp['relation'] ?? '']);
                }
            }
        }

        // Determine email type
        $emailType = 'attending';
        if ($this->isEditing) {
            $emailType = match (true) {
                $prevAttending === 'no' && $this->attending === 'yes'  => 'updated_attending',
                $prevAttending === 'yes' && $this->attending === 'no'  => 'updated_not_attending',
                default => $this->attending === 'yes' ? 'attending' : 'not_attending',
            };
        } else {
            $emailType = $this->attending === 'yes' ? 'attending' : 'not_attending';
        }

        // Queue emails
        SendGuestRsvpEmail::dispatch($guest->fresh(), $emailType);
        SendCoupleNotificationEmail::dispatch($guest->fresh(), $this->isEditing ? 'updated' : 'new');

        // Generate and save QR code file — base64 only for page display, file URL for email
        if ($guest->attending === 'yes' && $guest->qr_token) {
            $url      = route('verify', ['token' => $guest->qr_token]);
            $qrPath   = 'qrcodes/' . $guest->qr_token . '.png';
            $disk     = \Illuminate\Support\Facades\Storage::disk('public');

            $qrPng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->generate($url);

            // Save file for email use
            if (!$disk->exists($qrPath)) {
                $disk->put($qrPath, $qrPng);
            }

            // base64 for on-page display and download link
            $this->qrCodeSvg = base64_encode($qrPng);
        }

        $this->submitted = true;
        $this->step      = 4;
    }

    public function render()
    {
        $rsvpEnabled  = SiteSetting::get('rsvp_enabled', '1') === '1';
        $rsvpDeadline = SiteSetting::get('rsvp_deadline', '');

        // Auto-close if deadline has passed
        if ($rsvpEnabled && $rsvpDeadline) {
            try {
                if (now()->isAfter(\Carbon\Carbon::parse($rsvpDeadline)->endOfDay())) {
                    SiteSetting::set('rsvp_enabled', '0');
                    $rsvpEnabled = false;
                }
            } catch (\Exception $e) {}
        }

        if (!$rsvpEnabled) {
            return view('livewire.public.rsvp-disabled');
        }

        return view('livewire.public.rsvp-form');
    }
}