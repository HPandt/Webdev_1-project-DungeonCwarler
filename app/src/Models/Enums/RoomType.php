<?php

namespace App\Models\Enums;

enum RoomType: string {
    case entrace = 'entrance';
    case empty = 'empty';
    case trap = 'trap';
    case monster = 'monster';
    case exit = 'exit';
}