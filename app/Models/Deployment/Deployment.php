<?php

namespace App\Models\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deployment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'title',
        'deploy_date',
        'document_status',
        'document_description',
        'cm_status',
        'cm_description',
    ];

    public function module(){
        return $this->belongsToMany(DeploymentModule::class, 'deployment_has_module', 'deployment_id', 'module_id');
    }

    public function serverType(){
        return $this->belongsToMany(DeploymentServerType::class, 'deployment_has_server_type', 'deployment_id', 'server_type_id');
    }
}
