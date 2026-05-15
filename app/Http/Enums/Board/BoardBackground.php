<?php

namespace App\Http\Enums\Board;

enum BoardBackground: string
{
    case FILE = 'FILE';
    case COLOR = 'COLOR';
    case LOCAL_IMAGE = 'LOCAL_IMAGE';
}