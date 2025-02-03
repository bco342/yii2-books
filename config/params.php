<?php

return [
    'rememberMeDuration' => getenv('REMEMBER_ME_DURATION') ?: 60 * 60 * 24 * 30, // 30 days
    'smsPilotApiUrl' => getenv('SMS_PILOT_API_URL') ?: 'https://smspilot.ru/api.php',
    'smsPilotApiKey' => getenv('SMS_PILOT_API_KEY') ?: '33FDXFOGZ7N383T2PN623ZE7G39XUG126U0PM2XPJ408J7A37G5RT0VG73FK231D',
];
