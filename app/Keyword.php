<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable  = [
        "value", "expression_id"
    ];

    public function expression()
    {
        return $this->belongsTo(Expression::class, "expression_id", "id");
    }
}
