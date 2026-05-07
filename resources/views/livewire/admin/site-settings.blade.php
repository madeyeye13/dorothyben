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

            <form wire:submit.prevent="uploadHero" class="space-y-4">
                <div>
                    <label class="form-label">Upload New Hero Image</label>
                    <input wire:model="hero_image" type="file" accept="image/*" class="form-input text-sm">
                    @error('hero_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div wire:loading.remove wire:target="uploadHero">
                    <button type="submit" class="btn-gold text-xs">Update Hero Image</button>
                </div>
                <div wire:loading wire:target="uploadHero" class="text-sm flex items-center gap-2" style="color: var(--color-gold);">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Uploading...
                </div>
            </form>
        </div>

        {{-- RSVP Toggle --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">RSVP Form</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">Enable or disable the RSVP form on the website.</p>
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

        {{-- Memories Page --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6">
            <h3 class="text-sm font-medium mb-1">Memories Upload Page</h3>
            <p class="text-xs mb-5" style="color: var(--color-muted);">Allow guests to upload photos and videos from the event. Activate on the day of the wedding.</p>
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
            <button wire:click="saveMemories" class="btn-gold text-xs">Save Memories Setting</button>
        </div>

    </div>
</div>
