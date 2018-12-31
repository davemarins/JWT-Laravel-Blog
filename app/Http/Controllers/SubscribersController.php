<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnsubscribeRequest;
use App\Http\Requests\SubscribeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscribersController extends Controller
{
    /**
     * Create a new SubscribersController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['subscribe', 'unsubscribe']]);
    }

    /*
     * Get all users which have the subscription
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribers()
    {
        return DB::table('subscribers')->get();
    }

    public function validateEmail($email)
    {
        // with !! the result is just true or false
        return !!DB::table('subscribers')->where('email', $email)->first();
    }

    public function successResponse()
    {
        return response()->json([
            'success' => 'Unsubscription completed. Sorry for you to go away :('
        ], Response::HTTP_OK);
    }

    public function failedResponse()
    {
        return response()->json([
            'error' => 'Email not found in our DB.'
        ], Response::HTTP_NOT_FOUND);
    }

    /*
     * Unsubscribe from our newsletter
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(UnsubscribeRequest $request)
    {
        if(!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        } else {
            DB::table('subscribers')->where('email', $request->email)->delete();
            return $this->successResponse();
        }
    }

    /*
     * Subscribe to our newsletter
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(SubscribeRequest $request)
    {
        if(!$this->validateEmail($request->email)) {
            DB::table('subscribers')->insert(array('name' => $request->name, 'email' => $request->email));
            return $this->successResponse();
        } else {
            return $this->failedResponse();
        }
    }

}
