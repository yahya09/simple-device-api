<?php

namespace App\Models;

enum UserRole: int
{
    case User = 0;
    case Admin = 1;
}
