<div>
    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Site Settings</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Control website features and appearance.</p>
    </div>

    <div class="space-y-6 max-w-2xl">

        {{-- Hero Image --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Hero Image</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">This image is displayed on the home page and the Our Story page. Compressed automatically.</p>

            @if($currentHero)
            <div class="mb-4 relative inline-block">
                <img src="{{ asset('storage/' . $currentHero) }}" alt="Current Hero" class="h-40 w-auto object-cover border border-[var(--color-border)]">
                <p class="text-xs mt-1" style="color: var(--color-muted);">Current hero image</p>
            </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="form-label">Upload New Hero Image</label>
                    <input wire:model="hero_image" type="file" accept="image/*" class="form-input text-sm">
                    @error('hero_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <button wire:click="uploadHero" wire:loading.attr="disabled" wire:target="hero_image,uploadHero" class="btn-gold text-xs">
                        <span wire:loading.remove wire:target="uploadHero">Update Hero Image</span>
                        <span wire:loading wire:target="uploadHero">Uploading...</span>
                    </button>
                    <span wire:loading wire:target="hero_image" class="text-xs ml-3" style="color:var(--color-muted);">Processing image...</span>
                </div>
            </div>
        </div>

        {{-- Our Story Hero Image --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Our Story Hero Image</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">
                Separate hero image for the Our Story page. If not set, the home page hero image will be used as fallback.
            </p>

            @if($currentOurStoryHero)
            <div class="mb-4 relative inline-block">
                <img src="{{ asset('storage/' . $currentOurStoryHero) }}" alt="Our Story Hero"
                     class="h-40 w-auto object-cover border border-[var(--color-border)]">
                <p class="text-xs mt-1" style="color: var(--color-muted);">Current Our Story hero</p>
            </div>
            @else
            <div class="mb-4 p-3 border border-dashed border-[var(--color-border)] text-center" style="color:var(--color-muted);">
                <p class="text-xs">No separate image set — using home page hero as fallback.</p>
            </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="form-label">Upload Our Story Hero Image</label>
                    <input wire:model="our_story_hero_image" type="file" accept="image/*" class="form-input text-sm">
                    @error('our_story_hero_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <button wire:click="uploadOurStoryHero"
                            wire:loading.attr="disabled"
                            wire:target="our_story_hero_image,uploadOurStoryHero"
                            class="btn-gold text-xs">
                        <span wire:loading.remove wire:target="uploadOurStoryHero">Update Our Story Image</span>
                        <span wire:loading wire:target="uploadOurStoryHero">Uploading...</span>
                    </button>
                    <span wire:loading wire:target="our_story_hero_image" class="text-xs ml-3" style="color:var(--color-muted);">Processing image...</span>
                </div>
            </div>
        </div>

        {{-- RSVP Toggle --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">RSVP Form</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">Enable or disable the RSVP form. Set a deadline to auto-close it on a specific date.</p>
            <div class="flex items-center gap-6 mb-5">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input wire:model="rsvp_enabled" type="radio" value="1" class="accent-[var(--color-gold)]">
                    <span class="text-emerald-600 font-medium">Enabled</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input wire:model="rsvp_enabled" type="radio" value="0" class="accent-[var(--color-gold)]">
                    <span class="text-red-500 font-medium">Disabled</span>
                </label>
            </div>
            <div class="mb-5">
                <label class="form-label">RSVP Deadline (auto-closes on this date)</label>
                <input wire:model="rsvp_deadline" type="date" class="form-input" style="max-width:220px;">
                <p class="text-xs mt-1" style="color:var(--color-muted);">
                    Leave empty to control manually. When the date passes, RSVP closes automatically.
                    @if(!empty($this->rsvp_deadline))
                    <span style="color:var(--color-gold);">Deadline set: {{ \Carbon\Carbon::parse($this->rsvp_deadline)->format('d M Y') }}</span>
                    @endif
                </p>
            </div>
            <button wire:click="saveRsvp" class="btn-gold text-xs">Save RSVP Setting</button>
        </div>

        {{-- Gallery Password --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Gallery Password Protection</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">
                Set a password to protect the gallery. Leave empty to make the gallery open to everyone.
            </p>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Password</label>
                    <input wire:model="gallery_password" type="text" placeholder="Leave empty for open access" class="form-input" style="max-width: 300px;">
                </div>
                <div class="flex gap-3">
                    <button wire:click="saveGalleryPassword" class="btn-gold text-xs">Save Password</button>
                    @if($gallery_password)
                    <button wire:click="clearGalleryPassword" class="btn-outline-gold text-xs">Remove Password</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Venue Check-in PIN --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Venue Check-in PIN</h3>
            <p class="text-xs mb-1" style="color: var(--color-muted);">
                Staff at the venue use this PIN when scanning QR codes to mark guests as checked in.
                Guests who scan their own code just see their details — only staff with the PIN can trigger check-in.
            </p>
            <p class="text-xs mb-5 p-3 border border-[var(--color-gold)]/30 bg-[var(--color-gold)]/5" style="color: var(--color-gold-dark);">
                <strong>Venue staff scan URL:</strong> Add <code>?check_in=1</code> to any verify URL.
                e.g. <code>/verify/TOKEN?check_in=1</code> — they will be prompted for this PIN.
            </p>
            <div class="space-y-4">
                <div>
                    <label class="form-label">PIN (4–8 digits recommended)</label>
                    <input wire:model="venue_pin" type="text" placeholder="e.g. 2026 or leave empty for no PIN" class="form-input" style="max-width: 240px;">
                    <p class="text-xs mt-1" style="color: var(--color-muted);">Leave empty to allow check-in without a PIN in venue mode.</p>
                </div>
                <button wire:click="saveVenuePin" class="btn-gold text-xs">Save PIN</button>
            </div>
        </div>

        {{-- Memories Page --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Memories Upload Page</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">
                Allow guests to upload photos and videos from the event.
                Set an activation date to open it automatically on the wedding day.
            </p>

            <div class="flex items-center gap-6 mb-5">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input wire:model="memories_active" type="radio" value="1" class="accent-[var(--color-gold)]">
                    <span class="text-emerald-600 font-medium">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input wire:model="memories_active" type="radio" value="0" class="accent-[var(--color-gold)]">
                    <span class="text-red-500 font-medium">Inactive</span>
                </label>
            </div>

            <div class="mb-5">
                <label class="form-label">Auto-Activate On Date</label>
                <input wire:model="memories_activation_date" type="date" class="form-input" style="max-width:220px;">
                <p class="text-xs mt-1" style="color:var(--color-muted);">
                    Leave empty to control manually. The page will automatically activate on this date.
                    @if(!empty($this->memories_activation_date))
                    <span style="color:var(--color-gold);">
                        Activation date set: {{ \Carbon\Carbon::parse($this->memories_activation_date)->format('d M Y') }}
                        @if(now()->isSameDay(\Carbon\Carbon::parse($this->memories_activation_date)) || now()->isAfter(\Carbon\Carbon::parse($this->memories_activation_date)))
                        — <strong>Active now ✓</strong>
                        @else
                        — {{ \Carbon\Carbon::parse($this->memories_activation_date)->diffForHumans() }}
                        @endif
                    </span>
                    @endif
                </p>
            </div>

            <button wire:click="saveMemories" class="btn-gold text-xs">Save Memories Setting</button>
        </div>

    </div>
</div>