<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use URL;

class ConnectAccount extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $errors = [];
        if( !empty($this->due_fields) ){
            $due_fields = json_decode($this->due_fields);
            if( count($due_fields) ){
                foreach( $due_fields as $due_field ){
                    if( $due_field == 'external_account' ){
                        $errors['bank_account'] = 'Bank account is required';
                    } else if(  $due_field == 'line1' ){
                        $errors['street'] = 'Street address is not valid';
                    }else if( $due_field == 'day' || $due_field == 'month' || $due_field == 'year' ){
                        $errors['dob'] = 'Date of birth is not valid';
                    } else if( $due_field == 'ssn_last_4' ){
                        $errors['ssn'] = 'SSN is not valid';
                    } else {
                        $due_field_arr = explode('.',$due_field);
                        $field_name = end($due_field_arr);
                        $errors[$field_name] = $field_name . ' is not valid';
                    }
                }
            }
        }

        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'ssn'           => $this->ssn,
            'id_front'      => URL::to('gateway/'.$this->id_front),
            'id_back'       => URL::to('gateway/'.$this->id_back),
            'city'          => $this->city,
            'state'         => $this->state,
            'street'        => $this->street,
            'phone'         => $this->phone,
            'postal_code'   => $this->postal_code,
            'status'        => $this->status,
            'due_fields'    => $errors,
            'created_at'    => $this->created_at
        ];
    }
}
