<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->iCan('user', 'store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => 'required|email|min:8|max:35|unique:users|DomainValid',
            'last_name' => 'required|alfa_space|min:3|max:15',
            'module_id' => 'required|numeric',
            'name'      => 'required|alfa_space|min:3|max:15',
            'num_id'    => 'required|numeric|digits_between:6,8|exr_ced|unique:users',
            'password'  => 'required|string|min:6|max:20|confirmed',
            'roles'     => 'nullable|array|max:2'
        ];
    }

    /**
     * mensajes personalizados.
     *
     * @return array
     */
    public function messages()
    {
        return ['email.required' => 'El campo :attribute es requerido.'];
    }

    /**
     * Cambio de nombres de los atributos.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email'     => 'correo',
            'last_name' => 'apellido',
            'module_id' => 'módulo',
            'name'      => 'nombre',
            'num_id'    => 'cédula',
            'password'  => 'contraseña',
            'roles'     => 'roles'
        ];
    }
}
