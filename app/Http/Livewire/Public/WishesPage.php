<?php

namespace App\Livewire\Public;

use App\Jobs\SendNewWishNotification;
use App\Models\Wish;
use App\Models\WishReaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class WishesPage extends Component
{
    use WithPagination;

    public string $name    = '';
    public string $message = '';
    public bool   $sent    = false;

    protected $rules = [
        'name'    => 'required|min:2|max:100',
        'message' => 'required|min:5|max:1000',
    ];

    public function submit(): void
    {
        $this->validate();
        $wish = Wish::create([
            'name'     => $this->name,
            'message'  => $this->message,
            'approved' => false,
        ]);

        SendNewWishNotification::dispatch($wish);

        $this->sent    = true;
        $this->name    = '';
        $this->message = '';
        $this->resetPage();
        $this->dispatch('toast', message: "Your wish has been sent! We'll publish it shortly. 💛", type: 'success');
    }

    public function dismissSent(): void
    {
        $this->sent = false;
    }

    public function react(int $wishId, string $type): void
    {
        $sessionId = session()->getId();
        $existing  = WishReaction::where('wish_id', $wishId)
            ->where('reaction_type', $type)
            ->where('session_id', $sessionId)
            ->first();

        if ($existing) {
            $existing->delete();
            Wish::where('id', $wishId)->decrement($type === 'heart' ? 'heart_count' : 'congrats_count');
        } else {
            WishReaction::create(['wish_id' => $wishId, 'reaction_type' => $type, 'session_id' => $sessionId]);
            Wish::where('id', $wishId)->increment($type === 'heart' ? 'heart_count' : 'congrats_count');
        }
    }

    public function render()
    {
        $sessionId   = session()->getId();
        $wishes      = Wish::where('approved', true)->latest()->paginate(8);
        $myReactions = WishReaction::where('session_id', $sessionId)
            ->whereIn('wish_id', $wishes->pluck('id'))
            ->get()->groupBy('wish_id');
        $accounts     = \App\Models\BankAccount::where('active', true)->orderBy('sort_order')->get();
        $paymentLinks = \App\Models\PaymentLink::where('active', true)->orderBy('sort_order')->get();

        return view('livewire.public.wishes-page', [
            'wishes'       => $wishes,
            'myReactions'  => $myReactions,
            'accounts'     => $accounts,
            'paymentLinks' => $paymentLinks,
        ]);
    }
}