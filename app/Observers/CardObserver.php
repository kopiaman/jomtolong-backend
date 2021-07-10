<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Str;

class CardObserver
{
    public function creating(Card $card)
    {
        $name = Str::slug($card->name, '-');
        $state = Str::slug($card->state);
        $district = Str::slug($card->district);
        $card->slug = $district . '-' . $name . '-' . rand(10, 9000);
    }
}
