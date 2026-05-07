<div class="max-w-md">
    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">My Profile</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Change your admin password.</p>
    </div>

    <div class="admin-card border border-[var(--color-border)] bg-white p-6">
        <div class="mb-6 pb-6 border-b border-[var(--color-border)]">
            <p class="text-sm font-medium">{{ auth()->user()?->name }}</p>
            <p class="text-xs" style="color: var(--color-muted);">{{ auth()->user()?->email }}</p>
        </div>

        <h3 class="text-sm font-medium mb-5">Change Password</h3>
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="form-label">Current Password *</label>
                <input wire:model="current_password" type="password" placeholder="••••••••" class="form-input">
                @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">New Password *</label>
                <input wire:model="password" type="password" placeholder="Min 8 characters" class="form-input">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Confirm New Password *</label>
                <input wire:model="password_confirmation" type="password" placeholder="••••••••" class="form-input">
            </div>
            <button type="submit" class="btn-gold text-xs mt-2">Update Password</button>
        </form>
    </div>
</div>
