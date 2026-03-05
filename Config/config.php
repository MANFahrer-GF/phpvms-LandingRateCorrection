<?php
return [
    'name'                  => 'LandingRateCorrection',
    'admin_email'           => env('LRC_ADMIN_EMAIL', ''),
    'allowed_mimes'         => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
    'max_upload_size'       => 5120,
    'correction_window_days'=> 0,
    'min_plausible_rate'    => -20,
];
