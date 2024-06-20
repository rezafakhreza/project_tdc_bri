<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsmanSAP extends Model
{
    use HasFactory;
    protected $table = 'user_sap_usman';

    protected $fillable = [
        'user_pn',
        'creation_date',
        'last_logon',
        'inactive_month',
        'inactive_category',
        'first_name',
        'last_name',
        'branch_code',
        'department',
        'branch_level',
        'lock_status',
        'user_group',
        'div_code',
    ];
}
