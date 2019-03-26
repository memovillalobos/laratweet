<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Tweet;

class TweetsController extends Controller
{
    //
    public function index(Request $request)
    {
        $tweets = Tweet::whereNull('tweet_id')->orderBy('created_at', 'DESC');
        if ($request->has('to_tweet_id') && !empty($request->to_tweet_id)) {
            $tweets = $tweets->where('id', '<', $request->to_tweet_id);
        }
        if ($request->has('from_tweet_id') && !empty($request->from_tweet_id)) {
            $tweets = $tweets->where('id', '>', $request->from_tweet_id);
        }
        $tweets = $tweets->limit(10)->get();
        return response()->json([
            'status'    => 'ok',
            'code'      => 200,
            'messages'  => [
                'Tweets were successfully retrieved'
            ],
            'data'      => [
                'tweets' => $tweets->toArray()
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author' => 'required',
            'message' => 'required',
            'tweet_id' => 'exists:tweets,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'code'      => 422,
                'messages'  => $validator->errors()->all(),
                'data'      => []
            ], 422);
        }
        $tweet = Tweet::create($request->only(['author', 'tweet_id', 'message']));
        return response()->json([
            'status'    => 'ok',
            'code'      => 201,
            'messages'  => [
                'Tweet was created'
            ],
            'data'      => [
                'tweet' => $tweet->fresh()->toArray()
            ]
        ], 201);
    }

    public function destroy(Tweet $tweet)
    {
        $tweet->delete();
        return response()->json([
            'status'    => 'ok',
            'code'      => 200,
            'messages'  => [
                'Tweet was destroyed'
            ],
            'data'      => [
                'tweet' => $tweet->toArray()
            ]
        ], 200);
    }
}
