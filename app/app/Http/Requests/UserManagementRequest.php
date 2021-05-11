<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserManagementRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Check if Bulk Registration rules Page 1
        if ($this->is('api/user/details')) {
            return $this->userDetails();
        }

        // Check if Bulk Registration rules Page 1
        if ($this->is('api/user/manage')) {
            return $this->userManage();
        }
    }

    private function userDetails()
    {
        return [
            'username' => 'required|regex:/^(255)-?([0-9]{3})-?([0-9]{6})$/',
        ];
    }

    private function userManage()
    {
        return [
            'blockReason' => 'required_if:actionID,0',
            'blockReference' => 'required_if:actionID,0',
            'otherReason' => 'required_if:blockReason,"Others –(Specify)"',

            'unblockReason' => 'required_if:actionID,1',
            'unblockReference' => 'required_if:actionID,1',
        ];
    }


    public function messages()
    {
        return [
            /**  REGISTRATION REPORTS **/
            'username.required' => 'Please input username !',
            'username.regex' => 'Invalid username format !',

            'blockReason.required_if' => 'Please select reason for blocking this user !',
            'blockReference.required_if' => 'Please input reference for blocking this user !',
            'otherReason.required_if' => 'Please input reason for blocking this user !',

            'unblockReason.required_if' => 'Please select reason for unblocking this user !',
            'unblockReference.required_if' => 'Please input reference for unblocking this user !',
        ];
    }
}
