<?php

return [
    'ghostscript_binary' => env(
        'PDF_GHOSTSCRIPT_BINARY',
        PHP_OS_FAMILY === 'Windows' ? 'gswin64c' : 'gs'
    ),
];
