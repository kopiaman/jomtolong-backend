<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Storage;

class CardController extends Controller
{

    public function index(Request $request)
    {

        //custom limit pagination
        // $request->limit ? $limit = $request->limit : $limit = $this->limit;

        $cards = Card::query();

        if ($request->category_slug != '') {
            $cards->whereJsonContains('category->slug', $request->category_slug);
        }

        //donaters or donatees or providers
        if ($request->type != '') {
            $cards->where('type', $request->type);
        }

        if ($request->district != '') {
            $cards->where('district', $request->district);
        }

        if ($request->state != '') {
            $cards->where('state', $request->state);
        }

        $cards = $cards->paginate();

        return $cards;
    }


    public function show($slug)
    {
        return Card::where('slug', $slug)->first();
    }



    public function remove($slug)
    {

        $card = Card::where('slug', $slug);

        if ($card) {

            $card->delete();

            return ['success' => true, 'message' => 'Card Successfully Removed'];
        } else {

            return response()->json(['success' => false, 'error' => 'Card not existed'], 500);
        }
    }

    public function store(Request $request)
    {
        $credentials = $request->except(['']);

        $rules = [
            'name'   => 'required',
            'tel'    => 'required',
            'type'   => 'required',
            // 'services'   => 'required',
            'info' => 'required',

            'street' => 'required',
            'district' => 'required',
            'state'  => 'required',

        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 500);
        }

        // $credentials['image'] = Storage::disk('s3')->put('image', $request->file('image'));

        $card = Card::create($credentials);

        // tags for card
        //        $card->syncTags(json_decode($request->tags));

        return response()->json([
            'success' => true,
            'message' => 'Card created',
        ]);
    }


    public function update($slug, Request $request)
    {
        $credentials = $request->except(['']);

        $rules = [
            'name'   => 'required',
            'tel'    => 'required',
            'type'   => 'required',
            'info' => 'required',
            'street' => 'required',
            'district' => 'required',
            'state'  => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 500);
        }


        $card = Card::where('slug', $slug)->first();

        if (!$card) {
            return response()->json(['success' => false, 'error' => 'not find card id'], 500);
        }

        //if got image file
        if ($request->file('image')) {
            $credentials['image'] = Storage::disk('s3')->put('image', $request->file('image'));
        }

        $card->update($credentials);

        return response()->json([
            'success' => true,
            'message' => 'Card created',
        ]);
    }
}
