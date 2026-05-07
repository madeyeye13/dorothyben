<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Gift Accounts</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">Manage bank accounts displayed in the gift section.</p>
        </div>
        <button wire:click="openForm()" class="btn-gold text-xs">
            + Add Account
        </button>
    </div>

    {{-- Accounts List --}}
    <div class="space-y-4 mb-8">
        @forelse($accounts as $account)
        <div class="admin-card border border-[var(--color-border)] bg-white p-5 flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs px-2 py-0.5 border" style="{{ $account->currency === 'NGN' ? 'border-emerald-200; background: #ecfdf5; color: #065f46;' : 'border-blue-200; background: #eff6ff; color: #1e40af;' }}">
                        {{ $account->currency }}
                    </span>
                    <p class="text-sm font-medium">{{ $account->bank_name }}</p>
                </div>
                <p class="text-xs mb-0.5" style="color: var(--color-muted);">{{ $account->account_name }}</p>
                <p style="font-family: var(--font-serif); font-size: 1.25rem; letter-spacing: 0.04em;">{{ $account->account_number }}</p>
                @if($account->sort_code)<p class="text-xs" style="color: var(--color-muted);">Sort: {{ $account->sort_code }}</p>@endif
                @if($account->routing_number)<p class="text-xs" style="color: var(--color-muted);">Routing: {{ $account->routing_number }}</p>@endif
            </div>
            <div class="flex gap-2 shrink-0">
                <button wire:click="openForm({{ $account->id }})" class="btn-outline-gold text-xs py-1.5 px-3">Edit</button>
                <button wire:click="confirmDelete({{ $account->id }})" class="text-red-400 hover:text-red-600 p-1.5 transition-colors border border-red-100 hover:border-red-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-16 border border-[var(--color-border)] bg-white" style="color: var(--color-muted);">
            <p class="text-sm">No accounts yet. Add your bank details above.</p>
        </div>
        @endforelse
    </div>

    {{-- Add/Edit Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-md w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.5rem;">
                {{ $editId ? 'Edit Account' : 'Add Account' }}
            </h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="form-label">Currency *</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input wire:model.live="currency" type="radio" value="NGN" class="accent-[var(--color-gold)]"> 🇳🇬 Nigerian Naira (NGN)
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input wire:model.live="currency" type="radio" value="USD" class="accent-[var(--color-gold)]"> 🇺🇸 US Dollar (USD)
                        </label>
                    </div>
                </div>
                <div>
                    <label class="form-label">Bank Name *</label>
                    <input wire:model="bank_name" type="text" placeholder="e.g. Access Bank" class="form-input">
                    @error('bank_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Account Name *</label>
                    <input wire:model="account_name" type="text" placeholder="e.g. Dorothy Okafor" class="form-input">
                    @error('account_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Account Number *</label>
                    <input wire:model="account_number" type="text" placeholder="e.g. 0123456789" class="form-input">
                    @error('account_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                @if($currency === 'NGN')
                <div>
                    <label class="form-label">Sort Code <span style="color: var(--color-muted);">(Optional)</span></label>
                    <input wire:model="sort_code" type="text" placeholder="e.g. 044" class="form-input">
                </div>
                @endif
                @if($currency === 'USD')
                <div>
                    <label class="form-label">Routing Number <span style="color: var(--color-muted);">(Optional)</span></label>
                    <input wire:model="routing_number" type="text" placeholder="e.g. 021000021" class="form-input">
                </div>
                @endif
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-gold text-xs py-2.5 px-6">Save Account</button>
                    <button type="button" wire:click="$set('showForm', false)" class="btn-outline-gold text-xs py-2.5 px-6">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Account?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This account will be permanently removed from the gift section.</p>
            <div class="flex gap-3">
                <button wire:click="deleteAccount" class="btn-gold text-xs py-2.5 px-5" style="background: #dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
