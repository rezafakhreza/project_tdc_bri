<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'usman_branch';
    protected $primaryKey = 'branch_code';
    public $incrementing = false;
    
    protected $fillable = ['branch_code', 'branch_name', 'kanwil_code', 'kanwil_name'];
}