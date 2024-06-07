<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentCMStatus extends Model
{
    use HasFactory;

    protected $table = 'deployment_cm_status';
    protected $fillable = [
        'cm_status_name', 
        'colour',
    ];

    public function deploymentCMStatus()
    {
        return $this->hasMany(Deployment::class, 'cm_status_id');
    }
}
