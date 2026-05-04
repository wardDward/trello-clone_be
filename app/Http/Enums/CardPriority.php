<?php

namespace App\Http\Enums;

/**
 * Card priority levels:
 * - LOW: Low priority
 * - MEDIUM: Medium priority
 * - HIGH: High priority
 */

enum CardPriority: string
{
    case LOW = 'LOW';
    case MEDIUM = 'MEDIUM';
    case HIGH = 'HIGH';
}

