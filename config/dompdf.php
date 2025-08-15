<?php

return [
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [
        'font_dir' => storage_path('app/fonts/'),
        'font_cache' => storage_path('app/fonts/'),
        'temp_dir' => storage_path('app/temp/'),
        'chroot' => realpath(base_path()),
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_font' => 'Arial',
        'dpi' => 150,
        'enable_php' => false,
        'enable_javascript' => false,
        'enable_remote' => true,
        'font_height_ratio' => 0.9,
        'enable_html5_parser' => true,
        'enable_css_float' => true,
        'enable_inline_css' => true,
    ],
];
