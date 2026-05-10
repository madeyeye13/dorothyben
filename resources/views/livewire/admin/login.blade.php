<div>
    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="form-label">Email Address</label>
            <input wire:model="email"
                   type="email"
                   placeholder="Enter your email"
                   class="form-input"
                   autocomplete="email">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">Password</label>
            <input wire:model="password"
                   type="password"
                   placeholder="Enter your password"
                   class="form-input"
                   autocomplete="current-password">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2.5 cursor-pointer">
            <input wire:model="remember" type="checkbox"
                   class="w-4 h-4 accent-[var(--color-gold)]">
            <span class="text-sm" style="color: var(--color-muted);">Remember me</span>
        </label>

        <button type="submit"
                class="btn-gold w-full py-3"
                wire:loading.attr="disabled">
            <span wire:loading.remove>Sign In</span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Signing in...
            </span>
        </button>
    </form>

    <p class="text-xs mt-8 text-center" style="color: var(--color-muted);">
        Dorothy & Ben Wedding · Admin Access Only
    </p>
</div>
