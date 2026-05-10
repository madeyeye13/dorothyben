<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Admin Users</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">Create and manage admin accounts.</p>
        </div>
        <button wire:click="openForm()" class="btn-gold text-xs">+ Add User</button>
    </div>

    <div class="admin-card border border-[var(--color-border)] bg-white overflow-hidden">
        <table class="w-full admin-table">
            <thead>
                <tr style="background: #fafaf9; border-bottom: 1px solid var(--color-border);">
                    <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Name</th>
                    <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Email</th>
                    <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Role</th>
                    <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-border)]">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-sm font-medium">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-sm" style="color: var(--color-muted);">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5" style="background: rgba(201,168,76,0.1); color: var(--color-gold-dark);">
                            {{ $user->getRoleNames()->first() ?? 'admin' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <button wire:click="openForm({{ $user->id }})" class="text-xs px-3 py-1 border border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors">Edit</button>
                            @if($user->email !== 'doroegede@yahoo.com')
                            <button wire:click="confirmDelete({{ $user->id }})" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-md w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.5rem;">
                {{ $editId ? 'Edit User' : 'New Admin User' }}
            </h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="form-label">Name *</label>
                    <input wire:model="name" type="text" placeholder="Full Name" class="form-input">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input wire:model="email" type="email" placeholder="email@example.com" class="form-input">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Role *</label>
                    <div class="flex gap-6 mt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input wire:model="role" type="radio" value="admin" class="accent-[var(--color-gold)]">
                            <span>
                                <span class="font-medium">Admin</span>
                                <span style="color:var(--color-muted);font-size:12px;display:block;">Full access to all admin pages</span>
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input wire:model="role" type="radio" value="scanner" class="accent-[var(--color-gold)]">
                            <span>
                                <span class="font-medium">Scanner</span>
                                <span style="color:var(--color-muted);font-size:12px;display:block;">QR scanner only — for venue staff</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="form-label">Password {{ $editId ? '(leave blank to keep current)' : '*' }}</label>
                    <input wire:model="password" type="password" placeholder="••••••••" class="form-input">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input wire:model="password_confirmation" type="password" placeholder="••••••••" class="form-input">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-gold text-xs py-2.5 px-6">Save</button>
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
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete User?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This admin user will be permanently removed.</p>
            <div class="flex gap-3">
                <button wire:click="deleteUser" class="btn-gold text-xs py-2.5 px-5" style="background: #dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>