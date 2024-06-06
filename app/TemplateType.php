<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateType extends Model
{
    //
    protected $table = 'pdms_template_types';
    protected $primaryKey = 'template_type_id';
    protected $fillable = ['template_type','status'];
}
