<?php

namespace App\Models\Brisol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundationIEM extends Model
{
    use HasFactory;
    protected $table = 'brisol_foundation_iem';
    
    protected $fillable = [
        'buss_service_iem', 
        'prd_tier1', 
        'prd_tier2', 
        'prd_tier3', 
        'op_tier1', 
        'op_tier2', 
        'op_tier3', 
        'resolution_category'
    ];
}
