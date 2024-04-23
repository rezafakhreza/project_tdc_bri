<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeploymentModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];


    public function deployments()
    {
        return $this->belongsToMany(Deployment::class, 'deployment_has_module', 'module_id', 'deployment_id');
    }
}
