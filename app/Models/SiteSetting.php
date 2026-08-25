<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected static ?array $cached = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        if (static::$cached === null) {
            static::$cached = static::query()->pluck('value', 'key')->all();
        }

        return static::$cached[$key] ?? $default;
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        static::$cached = null;
    }
}
