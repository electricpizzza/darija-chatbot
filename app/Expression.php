<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expression extends Model
{
    protected $fillable = [
        'type',
        'content',
    ];

    public function keywords()
    {
        return $this->hasMany(Keyword::class, "expression_id", "id");
    }

    public function attachement()
    {
        return $this->hasOne(Attachment::class, "expression_id", "id");
    }
}
