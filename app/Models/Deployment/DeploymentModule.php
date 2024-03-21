<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeploymentModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];


    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function serverTypes()
    {
        return $this->hasMany(DeploymentServerType::class);
    }
}
