<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'usman_branch';
    protected $primaryKey = 'branch_code';
    protected $fillable = ['branch_code', 'branch_name', 'kanwil_code', 'kanwil_name'];
}