<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GuzzleController as GuzzleController;
use App\Http\Requests\UserManagementRequest;
use session;
use Log;

class UserManagementController extends GuzzleController
{
    public function __construct()
    {
        parent::__constructor();
        $this->middleware(['role:ADMIN,MPESA_ADMIN,BACKOFFICE'])->only(['viewUnverifiedWakala', 'verifyWakala', 'getApprovedWakala', 'getDeclinedWakala', 'getUnverifiedWakala', 'getWakalaListWithStatus']);
    }

    public function getUserDetails(UserManagementRequest $request)
    {
        $url = 'GetusersForActivation/' . $request->username;

        $data = $this->getRequest($url);

        if ($data['responseCode'] == 50) {
            $request->session()->put('blockUserID', $data['getUser']['UserID']);
            return response()->json($data, 200);
        } elseif ($data['responseCode'] == 51) {
            return response()
                ->json([
                    'message' => 'User not found !',
                    'status' => $data['responseCode']
                ], 400);
        } elseif ($data['responseCode'] == 99) {
            return response()
                ->json([
                    'message' => 'Something went wrong . Try again !',
                    'status' => $data['responseCode']
                ], 400);
        }
    }

    function userManage(UserManagementRequest $request)
    {
        if ($request->actionID  == 0) {
            $reason = ($request->blockReason == "Others –(Specify)" ? $request->otherReason : $request->blockReason);
        } elseif ($request->actionID  == 1) {
            $reason = $request->unblockReason;
        }

        $body = [
            'UserID' => $request->session()->get('blockUserID'),
            'Reason' => $reason,
            'ActionID' => $request->actionID,
            'ActionedBy' => $this->user['UserID'],
            'Reference' => $request->actionID == 0 ? $request->blockReference : $request->unblockReference
        ];

        $url = 'UserActivation';

        $data = $this->postRequest($url, $body);

        if ($data['ID'] == 0) {
            $request->session()->forget('blockUserID');
            return response()->json(null, 200);
        } elseif ($data['ID'] == 1) {
            return response()
                ->json([
                    'message' => $request->actionID == 0 ? 'User blocking failed !' : 'User unblocking failed !',
                    'status' => $data['ID']
                ], 400);
        } else {
            return response()
                ->json([
                    'message' => 'Something went wrong . Try again !',
                    'status' => $data['ID']
                ], 400);
        }
    }
}
