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

    public function module()
    {
        return $this->hasMany(DeploymentModule::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }
}
