<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentServerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function deployments()
    {
        return $this->belongsToMany(Deployment::class, 'deployment_has_server_type', 'server_type_id', 'deployment_id')
        ->withTimestamps();
    }
}
