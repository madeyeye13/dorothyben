<div>
    {{-- Page Header --}}
    <div class="text-center py-16 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-2" style="color: var(--color-gold-light);">You're Invited</p>
        <h1 class="section-title mb-3" style="color: #fff;">Dorothy <em>&</em> Ben</h1>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.875rem; letter-spacing: 0.15em; text-transform: uppercase;">
            {{ config('wedding.wedding_date') }} &nbsp;·&nbsp; {{ config('wedding.general_location') }}
        </p>
    </div>

    {{-- Form Container --}}
    <div class="py-16 px-6" style="background: var(--color-ivory);">
        <div class="max-w-lg mx-auto">

            @if(!$submitted)

            {{-- Stepper --}}
            <div class="flex items-center justify-center mb-12 gap-0">
                @foreach([1,2,3] as $s)
                <div class="flex items-center">
                    <div class="stepper-dot {{ $step > $s ? 'done' : ($step === $s ? 'active' : '') }}">
                        @if($step > $s)
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                        {{ $s }}
                        @endif
                    </div>
                    @if($s < 3)
                    <div class="h-px w-16 {{ $step > $s ? 'bg-[var(--color-obsidian)]' : 'bg-[var(--color-border)]' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="text-center mb-8">
                <p class="text-xs uppercase tracking-widest" style="color: var(--color-gold);">
                    Step {{ $step }} of 3
                </p>
                <p class="text-sm mt-1" style="color: var(--color-muted);">
                    @if($step === 1) Your Details
                    @elseif($step === 2) Attendance
                    @elseif($step === 3) Your Relationship with the Couple
                    @endif
                </p>
            </div>

            {{-- Email exists notice --}}
            @if($emailExists)
            <div class="mb-6 p-4 border border-[var(--color-gold)]/30 bg-[var(--color-gold)]/5">
                <p class="text-sm mb-3" style="color: var(--color-obsidian);">
                    <strong>{{ $existingName }}</strong> has already submitted an RSVP with this email.
                </p>
                <div class="flex gap-3">
                    <button wire:click="loadForEdit" class="btn-gold text-xs py-2 px-4">Edit My Submission</button>
                    <button wire:click="$set('emailExists', false); $set('email', '')" class="btn-outline-gold text-xs py-2 px-4">Use Different Email</button>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="{{ $step === 3 ? 'submit' : 'nextStep' }}">

                {{-- ─── STEP 1: Personal Details ─── --}}
                @if($step === 1)
                <div class="space-y-5">
                    @if($isEditing)
                    <div class="p-3 border border-[var(--color-gold)]/30 bg-[var(--color-gold)]/5 text-sm" style="color: var(--color-gold);">
                        ✎ You are editing your existing RSVP submission.
                    </div>
                    @endif

                    <div>
                        <label class="form-label">Full Name *</label>
                        <input wire:model.live.debounce.300ms="full_name" type="text" placeholder="e.g. Chukwuemeka Obi" class="form-input">
                        @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address *</label>
                        <input wire:model.live.debounce.500ms="email" type="email" placeholder="your@email.com" class="form-input" {{ $isEditing ? 'readonly' : '' }}>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input wire:model="phone" type="tel" placeholder="+234 800 000 0000" class="form-input">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                @endif

                {{-- ─── STEP 2: Attendance ─── --}}
                @if($step === 2)
                <div class="space-y-6">
                    <div>
                        <label class="form-label mb-3">Will You Be Attending? *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="attending" value="yes" class="sr-only">
                                <div class="border-2 p-5 text-center transition-all
                                    {{ $attending === 'yes' ? 'border-[var(--color-gold)] bg-[var(--color-gold)]/5' : 'border-[var(--color-border)] hover:border-[var(--color-gold)]/40' }}">
                                    <div class="text-2xl mb-1">🎉</div>
                                    <p class="text-sm font-medium">Yes, I'll be there!</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="attending" value="no" class="sr-only">
                                <div class="border-2 p-5 text-center transition-all
                                    {{ $attending === 'no' ? 'border-[var(--color-gold)] bg-[var(--color-gold)]/5' : 'border-[var(--color-border)] hover:border-[var(--color-gold)]/40' }}">
                                    <div class="text-2xl mb-1">😔</div>
                                    <p class="text-sm font-medium">I can't make it</p>
                                </div>
                            </label>
                        </div>
                        @error('attending')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    {{-- Coming With Someone --}}
                    @if($attending === 'yes')
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="coming_with_someone" class="w-4 h-4 accent-[var(--color-gold)]">
                            <span class="text-sm">I'm coming with someone</span>
                        </label>
                    </div>

                    @if($coming_with_someone)
                    <div class="space-y-4 pl-4 border-l-2 border-[var(--color-gold)]/30">
                        <p class="text-xs uppercase tracking-widest" style="color: var(--color-muted);">Guest(s) you're bringing</p>
                        @foreach($companions as $i => $companion)
                        <div class="flex gap-3 items-start">
                            <div class="flex-1 space-y-2">
                                <input wire:model="companions.{{ $i }}.name" type="text" placeholder="Full Name *" class="form-input">
                                <input wire:model="companions.{{ $i }}.relation" type="text" placeholder="Relation (e.g. Spouse, Friend)" class="form-input" style="font-size: 0.875rem;">
                                @error("companions.{$i}.name")<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                            <button type="button" wire:click="removeCompanion({{ $i }})" class="mt-2 text-red-400 hover:text-red-500 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                        <button type="button" wire:click="addCompanion" class="text-sm flex items-center gap-1" style="color: var(--color-gold);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add another guest
                        </button>
                    </div>
                    @endif
                    @endif

                    {{-- Not Attending Reason --}}
                    @if($attending === 'no')
                    <div>
                        <label class="form-label">Reason for not attending <span style="color: var(--color-muted);">(Optional)</span></label>
                        <textarea wire:model="decline_reason" rows="3" placeholder="We'd love to know..." class="form-input resize-none"></textarea>
                    </div>
                    @endif
                </div>
                @endif

                {{-- ─── STEP 3: Relationship ─── --}}
                @if($step === 3)
                <div class="space-y-5">
                    <div>
                        <label class="form-label mb-3">Your Relationship with the Couple *</label>
                        <p class="text-sm mb-4" style="color: var(--color-muted);">Select all that apply</p>
                        <div class="space-y-3">
                            @foreach([
                                ['value' => 'groom_friend', 'label' => "Groom's Friend", 'icon' => '🤝'],
                                ['value' => 'bride_friend', 'label' => "Bride's Friend",  'icon' => '💐'],
                                ['value' => 'family',       'label' => 'Family Member',   'icon' => '👨‍👩‍👧‍👦'],
                            ] as $rel)
                            <label class="flex items-center gap-4 cursor-pointer group">
                                <div class="w-5 h-5 border-2 flex items-center justify-center shrink-0 transition-colors
                                    {{ in_array($rel['value'], $relationship) ? 'bg-[var(--color-gold)] border-[var(--color-gold)]' : 'border-[var(--color-border)] group-hover:border-[var(--color-gold)]' }}">
                                    @if(in_array($rel['value'], $relationship))
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </div>
                                <input type="checkbox" wire:click="toggleRelationship('{{ $rel['value'] }}')"
                                       {{ in_array($rel['value'], $relationship) ? 'checked' : '' }} class="sr-only">
                                <span class="text-sm">{{ $rel['icon'] }} {{ $rel['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('relationship')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>
                @endif

                {{-- Navigation Buttons --}}
                <div class="flex justify-between mt-10">
                    @if($step > 1)
                    <button type="button" wire:click="prevStep" class="btn-outline-gold text-xs py-3 px-6">
                        ← Back
                    </button>
                    @else
                    <div></div>
                    @endif

                    <button type="submit" class="btn-gold text-xs py-3 px-8" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            @if($step === 3) Submit RSVP @else Next → @endif
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>

            @else
            {{-- ─── SUCCESS STATE ─── --}}
            <div>
                @if($attending === 'yes')

                {{-- Congrats header --}}
                <div class="text-center mb-8">
                    <div style="width:56px;height:56px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <svg style="width:28px;height:28px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="section-title mb-3">We Can't Wait to See You!</h2>
                    <p style="color: var(--color-muted); font-size: 0.9375rem; line-height: 1.8;">
                        Thank you, <strong>{{ $full_name }}</strong>! Your RSVP is confirmed.
                        A copy of this QR code has been sent to <strong>{{ $email }}</strong>.
                    </p>
                </div>

                @if($qrCodeSvg)
                {{-- QR Entry Pass Card --}}
                <div style="max-width:320px;margin:0 auto 2rem;border:1px solid var(--color-border);background:#fff;">

                    {{-- Card header --}}
                    <div style="background:var(--color-obsidian);padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin:0 0 2px;">Entry Pass</p>
                            <p style="font-family:var(--font-serif);color:#fff;font-size:1rem;margin:0;">Dorothy & Ben</p>
                        </div>
                        <p style="font-size:11px;color:var(--color-gold);margin:0;letter-spacing:0.05em;">10 Jul 2026</p>
                    </div>

                    {{-- QR code with download button overlay --}}
                    <div style="position:relative;padding:1.25rem;background:#fff;">
                        {{-- Download button — top right of QR --}}
                        <a href="data:image/png;base64,{{ $qrCodeSvg }}"
                           download="Dorothy-Ben-Entry-Pass.png"
                           title="Download QR Code"
                           style="position:absolute;top:12px;right:12px;width:32px;height:32px;background:var(--color-obsidian);display:flex;align-items:center;justify-content:center;z-index:2;text-decoration:none;"
                           onmouseover="this.style.background='var(--color-gold)'"
                           onmouseout="this.style.background='var(--color-obsidian)'">
                            <svg style="width:15px;height:15px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>

                        {{-- QR image --}}
                        <img src="data:image/png;base64,{{ $qrCodeSvg }}"
                             alt="Your Entry QR Code"
                             style="width:100%;height:auto;display:block;image-rendering:pixelated;">
                    </div>

                    {{-- Guest name strip --}}
                    <div style="padding:0.875rem 1.25rem;border-top:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--color-muted);margin:0 0 2px;">Guest</p>
                            <p style="font-size:0.9375rem;font-weight:500;color:var(--color-obsidian);margin:0;">{{ $full_name }}</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--color-muted);margin:0 0 2px;">Venue</p>
                            <p style="font-size:11px;color:var(--color-muted);margin:0;">Abuja, Nigeria</p>
                        </div>
                    </div>

                    {{-- Instruction --}}
                    <div style="padding:0.625rem 1.25rem;background:#fafaf9;border-top:1px solid var(--color-border);">
                        <p style="font-size:11px;color:var(--color-muted);text-align:center;margin:0;">
                            Present this QR code at the venue entrance for check-in
                        </p>
                    </div>
                </div>
                @endif

                @else
                {{-- Not attending --}}
                <div class="text-center mb-8">
                    <div style="width:56px;height:56px;background:#f9f9f9;border:1px solid #e5e5e5;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <span style="font-size:1.5rem;">💛</span>
                    </div>
                    <h2 class="section-title mb-3">We'll Miss You</h2>
                    <p style="color: var(--color-muted); font-size: 0.9375rem; line-height: 1.8;">
                        Thank you for letting us know, <strong>{{ $full_name }}</strong>. We'll miss having you there.
                        You're still in our hearts on that special day.
                    </p>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                    <a href="{{ route('wishes') }}" class="btn-outline-gold text-xs">Leave a Wish</a>
                    <a href="{{ route('our-story') }}" class="btn-gold text-xs">Our Story</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>