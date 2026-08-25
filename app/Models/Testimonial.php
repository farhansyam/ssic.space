<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role_or_status', 'content', 'rating', 'photo'])]
class Testimonial extends Model
{
    //
}
