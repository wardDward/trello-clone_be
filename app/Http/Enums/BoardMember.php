<?php

namespace App\Http\Enums;

/**
 * Board member roles:
 * - ADMIN: Full permissions, can manage board and members
 * - MEMBER: Can view and interact with the board, but cannot manage it
 * - VIEWER: Read-only access to the board
 */

enum BoardMember: string{
    case ADMIN = 'ADMIN';
    case MEMBER = 'MEMBER';
    case VIEWER = 'VIEWER';
}