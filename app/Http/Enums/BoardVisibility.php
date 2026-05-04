<?php

namespace App\Enums;

/**
 * Board visibility levels:
 * - PRIVATE: Only owner and invited members
 * - WORKSPACE: Visible to all workspace members
 * - PUBLIC: Accessible by anyone
 */
enum BoardVisibility: string
{
    case PRIVATE = 'PRIVATE';
    case WORKSPACE = 'WORKSPACE';
    case PUBLIC = 'PUBLIC';
}