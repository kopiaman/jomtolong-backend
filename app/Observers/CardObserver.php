<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CardObserver
{
    public function creating(Card $card)
    {
        $name = Str::slug($card->name, '-');
        $state = Str::slug($card->state);
        $district = Str::slug($card->district);
        $card->slug = $district . '-' . $name . '-' . rand(10, 9000);

        // hashing code
        $card->code = Hash::make($card->code);
    }
}
