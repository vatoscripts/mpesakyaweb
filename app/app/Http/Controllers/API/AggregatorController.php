<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GuzzleController as GuzzleController;
use App\Http\Requests\AggregatorRequest;
use session;
use Log;

class AggregatorController extends GuzzleController
{
    public function __construct()
    {
        parent::__constructor();
        // $this->middleware(['role:ADMIN,MPESA_ADMIN'])->except(['getZones', 'getRegions', 'getTerritory']);
    }

    public function onboardAggregatorNIDA(AggregatorRequest $request)
    {
        session::forget('AggregatorNIDA');

        $body = [
            'NIN' => $request->NIN,
            'FingerCode' => $request->fingerCode,
            'FingerData' => $request->fingerData,
            'platform' => 'web'
        ];

        $url = 'NIDA';

        $data = $this->postRequest($url, $body);

        unset($body['FingerData']);
        unset($data['PHOTO']);
        unset($data['SIGNATURE']);

        Log::channel('Aggregator-onboard')->debug(['user' => $this->user['UserName'], 'Request' => $body, 'Response' => $data]);

        if ($data['ErrorCode'] == 0) {
            session::put('AggregatorNIDA', $data);
            return response()
                ->json([
                    'message' => null,
                    'status' => $data['ErrorCode']
                ], 200);
        } elseif ($data['ErrorCode'] == '01') {
            return response()
                ->json([
                    'message' => 'Biometric verification failed',
                    'status' => $data['ErrorCode']
                ], 400);
        } elseif ($data['ErrorCode'] == '132') {
            return response()
                ->json([
                    'message' => 'NIN not found',
                    'status' => $data['ErrorCode']
                ], 400);
        } elseif ($data['ErrorCode'] == '141') {
            return response()
                ->json([
                    'message' => 'Biometric fingerprint verification failed 141. Use another finger !',
                    'status' => $data['ErrorCode']
                ], 400);
        } elseif ($data['ErrorCode'] == '-10') {
            return response()
                ->json([
                    'message' => 'NIDA connection error. Try again later !',
                    'status' => $data['ErrorCode']
                ], 400);
        } else {
            return response()
                ->json([
                    'message' => $data['ErrorMessage'],
                    'status' => $data['ErrorCode']
                ], 400);
        }
    }

    public function onboardAggregatorDB(AggregatorRequest $request)
    {
        $NIDA = $request->session()->get('AggregatorNIDA');

        $TINDoc = file_get_contents($request->TINFile);
        $TINDocBase64 = base64_encode($TINDoc);

        $licenceDoc = file_get_contents($request->businessLicenceFile);
        $LicenceDocBase64 = base64_encode($licenceDoc);

        $body = [
            'UserID' => $this->user['UserID'],
            'AgregatorTin' => $request->TIN,
            'AgregatorTinDoc' => $TINDocBase64,

            'AgregatorBusinessName' => $request->businessName,
            'AgregatorBusinessLicense' => $request->businessLicence,
            'AgregatorBusinessLicenseDoc' => $LicenceDocBase64,

            'AgregatorShortCode' => $request->shortCode,
            'AgregatorContactNumber' => $request->aggregatorPhone,
            'AgregatorNIN' => $NIDA['NIN'],

            'AgregatorDOB' => $NIDA['DATEOFBIRTH'],
            'AgregatorFirstName' => $NIDA['FIRSTNAME'],
            'AgregatorMiddleName' => $NIDA['MIDDLENAME'],

            'AgregatorSurname' => $NIDA['SURNAME'],
            'AgregatorGender' => $NIDA['SEX'],
            'AgregatorPhoto' => $NIDA['PHOTO'],

            'AgregatorEmail' => $request->aggregatorEmail,
            'AgregatorPhonenumber' => $request->mobilePhone,
            'Address' => $request->aggregatorAdress,

            'TerritoryID' => $request->aggregatorTerritory,
            'category' => $request->aggregatorCategory,
        ];

        $url = 'CreateAgregator';

        $data = $this->postRequest($url, $body);

        unset($body['AgregatorTinDoc']);
        unset($body['AgregatorBusinessLicenseDoc']);
        unset($body['AgregatorPhoto']);

        Log::channel('Aggregator-onboard')->debug(['user' => $this->user['UserName'], 'Request' => $body, 'Response' => $data]);

        if ($data['ID'] == 0) {
            session::forget('AggregatorNIDA');
            return response()
                ->json([
                    'message' => null,
                    'status' => $data['ID']
                ], 200);
        } elseif ($data['ID'] == 2) {
            return response()
                ->json([
                    'message' => 'Duplicate Till number or ShortCode !',
                    'status' => $data['ID']
                ], 400);
        }

        return response()
            ->json([
                'message' => $data['Description'],
                'status' => $data['ID']
            ], 400);
    }

    public function verifyAggregator(AggregatorRequest $request)
    {
        //return response()->json($request->all(), 200);

        $body = [
            'AgregatorID' => $request->MpesaAgentID,
            'AgregatorBusinessName' => $request->businessName,

            'AgregatorShortCode' => $request->shortCode,
            'AgregatorTin' => $request->tinNo,
            'AgregatorBusinessLicense' => $request->businessLicenceNo,

            'Address' => $request->adress,
            'Phonenumber' => $request->phoneNumber,

            'VerifiedBy' => $this->user['UserID'],

            'VerificationStatus' => $request->VerificationStatus,
        ];

        $url = 'VerifyAggregatorTDR';

        $data = $this->postRequest($url, $body);

        Log::channel('Aggregator-verify')->debug(['user' => $this->user['UserName'], 'Request' => $body, 'Response' => $data]);

        if ($data['ID'] == 0) {
            return response()
                ->json([
                    'message' => 'Aggregator verified successfully !',
                    'status' => $data['ID']
                ], 200);
        } elseif ($data['ID' != 0]) {
            return response()
                ->json([
                    'message' => $data['Description'],
                    'status' => $data['ID']
                ], 400);
        }
    }

    public function listAggregators()
    {
        $url = 'ListAggregators';

        $data = $this->getRequest($url);

        return response()->json($data, 200);
    }

    public function allUnverifiedAggregators()
    {
        $url = 'GetAggregators';

        $data = $this->getRequest($url);

        $finalArray = [];

        if ($data['responseCode'] == 50) {
            if (is_array($data)) {
                foreach ($data['UnverifiedAggregators'] as $item) {
                    $item['CreatedDate'] = date('Y-m-d  G:i:s', strtotime($item['CreatedDate']));
                    array_push($finalArray, $item);
                }

                return response()->json($finalArray, 200);
            } else {
                return response()
                    ->json([
                        'message' => 'Error while fetching aggregators/TDRs !'
                    ], 400);
            }
        }

        return response()->json($data, 200);
    }

    public function unverifiedAggregator($id)
    {
        $url = 'ViewSingleAggregator/' . $id;

        $data = $this->getRequest($url);

        $finalArray = [];

        if ($data['responseCode'] == 50) {
            if (is_array($data)) {

                $item = $data['SingleUnverifiedTDR'];

                $item['CreatedDate'] = date('Y-m-d  G:i:s', strtotime($item['CreatedDate']));

                return response()->json($item, 200);
            } else {
                return response()
                    ->json([
                        'message' => 'Error while fetching aggregator/TDR !'
                    ], 400);
            }
        }

        return response()->json($data, 200);
    }


    public function getZones()
    {
        $url = '/zone/';
        $zone = $this->getRequest($url);

        return response()->json($zone, 200);
    }

    public function getRegions($Id)
    {
        $url = '/region/' . $Id;
        $region = $this->getRequest($url);

        return response()->json($region, 200);
    }

    public function getTerritory($Id)
    {
        $url = '/Territory/' . $Id;
        $territory = $this->getRequest($url);

        return response()->json($territory, 200);
    }
}
