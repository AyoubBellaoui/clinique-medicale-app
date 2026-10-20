<?php

/*
|--------------------------------------------------------------------------
| Clinic identity
|--------------------------------------------------------------------------
|
| Printed in the header and footer of documents handed to patients
| (invoices). Any value left empty is simply omitted from the printout.
|
*/

return [
    'name'    => env('CLINIC_NAME', 'ClinicPro'),
    'tagline' => env('CLINIC_TAGLINE', 'Cabinet Médical'),
    'address' => env('CLINIC_ADDRESS'),
    'phone'   => env('CLINIC_PHONE'),
    'email'   => env('CLINIC_EMAIL'),
    'ice'     => env('CLINIC_ICE'),
];
