<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center border border-[var(--color-gold)]/30">
            <img src="{{ asset('images/D&B.png') }}" alt="D&B" class="h-10 w-auto" onerror="this.style.display='none'">
        </div>
        <h1 style="font-family: var(--font-serif); font-size: 1.75rem; color: #fff; margin-bottom: 0.25rem;">Admin Portal</h1>
        <p style="color: rgba(255,255,255,0.4); font-size: 0.875rem;">Dorothy & Ben Wedding</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="form-label" style="color: rgba(255,255,255,0.5);">Email Address</label>
            <input wire:model="email" type="email" placeholder="doroegede@yahoo.com"
                   class="form-input" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff;">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" style="color: rgba(255,255,255,0.5);">Password</label>
            <input wire:model="password" type="password" placeholder="••••••••"
                   class="form-input" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff;">
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <label class="flex items-center gap-2 cursor-pointer text-sm" style="color: rgba(255,255,255,0.5);">
            <input wire:model="remember" type="checkbox" class="w-4 h-4 accent-[var(--color-gold)]">
            Remember me
        </label>
        <button type="submit" class="btn-gold w-full" wire:loading.attr="disabled">
            <span wire:loading.remove>Sign In</span>
            <span wire:loading>Signing in...</span>
        </button>
    </form>
</div>
