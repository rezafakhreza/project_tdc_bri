<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brisol\IncidentBrisol;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'usman_branch';
    protected $primaryKey = 'branch_code';
    public $incrementing = false;
    
    protected $fillable = [
        'branch_code', 
        'branch_name', 
        'level_uker', 
        'uker_induk_wilayah_code', 
        'kanwil_name', 
        'uker_induk_kc', 
        'sbo', 
        'is_active'
    ];

    public function usman()
    {
        return $this->hasMany(Incident::class, 'branch_code');
    }

    public function brisol()
    {
        return $this->hasMany(IncidentBrisol::class, 'branch_id');
    }
}