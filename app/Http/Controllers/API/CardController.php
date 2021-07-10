<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Storage;
use Hash;

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

        $card = Card::where('slug', $slug)->first();

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
            'code' => 'required',
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
        $card_existed = $this->check_existing_card($request);

        if ($card_existed) {
            return response()->json([
                'success' => false,
                'error' => 'Hanya 1 rekod dengan status masih memerlukan bantuan dengan dibenarkan'
            ], 500);
        } else {
            $card = Card::create($credentials);
            return response()->json([
                'success' => true,
                'message' => 'Card created',
            ]);
        }
    }

    protected function check_existing_card($request)
    {
        $existed =  Card::where('tel', $request->tel)->where('is_enough', 0)->exists();
        if ($existed) {
            return true;
        }
        return false;
    }


    public function update($slug, Request $request)
    {
        $credentials = $request->except(['code']);
        $rules = [];

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

        $updated = $card->update($credentials);

        return response()->json([
            'success' => true,
            'message' => 'Card Updated'
        ]);
    }

    public function request_update($slug, Request $request)
    {
        if (!$request->code) {
            return response()->json(['success' => false, 'error' => 'Code is required'], 500);
        }

        $card = Card::where('slug', $slug)->first();

        //check code is valid
        $check_code = $this->check_code_valid($slug, $request->code, $card->code);

        if ($check_code == 'valid') {

            $credentials = $request->except(['code']);

            $rules = [];

            $validator = Validator::make($credentials, $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()], 500);
            }

            $card->update($credentials);

            return response()->json([
                'success' => true,
                'message' => 'Card Updated'
            ]);
        }

        if ($check_code == 'invalid') {

            return response()->json(['success' => false, 'error' => 'Code not valid'], 500);
        }

        return response()->json(['success' => false, 'error' => 'Technical Error'], 500);
    }

    protected function check_code_valid($slug, $requested_code, $hashedPassword)
    {

        if (Hash::check($requested_code, $hashedPassword)) {
            return "valid";
        }

        return "invalid";
    }
}
