<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * validation rules that apply to the request.
     */
    public function rules(): array
    {
       
        if ($this->routeIs('user.more-information')) {
            return [
                'phone'                 => 'required|string',
                'realtor_license_no'    => 'required|string',
                'state_province'        => 'required|string',
                'city_municipality'     => 'required|string',
                'timezone'              => 'required|string',
            ];
        }
        else if ($this->routeIs('user.team-members')) {
            return [
                'members'               => 'required|array',
                'members.*.email'       => 'required|email',
            ];
        }
        else if ($this->routeIs('user.image')) {
            return [
                'image'                 => 'required|image|mimes:jpeg,png,jpg,gif|max:8000',
            ];
        }
        else if ($this->routeIs('user.register')) {
            return [
                'firstname'             => 'required|string|max:255',
                'lastname'              => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|min:8',
            ];
        }
        else if ($this->routeIs('user.login')) {
            return [
                'email'                 => 'required|email',
                'password'              => 'required|string',
            ];
        }
        else if( request()->routeIs('user.firstname') ){
            return [
                'firstname'      => 'required|string|max:255'
            ];
        }
        else if( request()->routeIs('user.lastname') ){
            return [
                'lastname'      => 'required|string|max:255'
            ];
        }
        else if( request()->routeIs('user.username') ){
            return [
                'username'      => 'required|string|max:255'
            ];
        }
        else if( request()->routeIs('user.phone') ){
            return [
                'phone'      => 'required|string|max:255'
            ];
        }
        else if( request()->routeIs('user.email') ){
            return [
                'email'     => 'required|string|email|max:255',
            ];
        }
        else if( request()->routeIs('user.password') ){
            return [
                'password'  => 'required|string|confirmed|min:8',
            ];
        }
        else if( request()->routeIs('user.realtor_license_no') ){
            return [
                'realtor_license_no'     => 'required|string|max:255',
            ];
        }
        else if( request()->routeIs('user.state_province') ){
            return [
                'state_province'     => 'required|string|max:255',
            ];
        }
        else if( request()->routeIs('user.city_municipality') ){
            return [
                'city_municipality'     => 'required|string|max:255',
            ];
        }
        else if( request()->routeIs('user.timezone') ){
            return [
                'timezone'     => 'required|string|max:255',
            ];
        }
        else if( request()->routeIs('user.image') || request()->routeIs('profile.image') || request()->routeIs('ocr.image') ){
            return [
                'image'     => 'required|image|mimes:jpg,bmp,png|max:2048',
            ];
        }
        else if( request()->routeIs('user.postal_zip') ){
            return [
                'postal_zip'     => 'required|string|max:255',
            ];
        }
        else if ( request()->routeIs('user.country') ){
            return [
                'country'     => 'required|string|max:255',
            ];
        }
        else if ( request()->routeIs('user.state_province') ){
            return [
                'state_province'     => 'required|string|max:255',
            ];
        }
        else if ( request()->routeIs('user.city_municipality') ){
            return [
                'city_municipality'     => 'required|string|max:255',
            ];
        }
     

       
        return [];
    }

 
}
