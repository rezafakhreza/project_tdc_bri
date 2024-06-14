<?php

namespace App\Models\Brisol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    use HasFactory;

    protected $table = 'brisol_foundation';
    
    protected $fillable = [
        'buss_service', 
        'prd_tier1', 
        'prd_tier2', 
        'prd_tier3', 
        'op_tier1', 
        'op_tier2', 
        'op_tier3', 
        'resolution_category'
    ];
}
