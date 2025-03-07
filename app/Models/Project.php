<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status'];

    // Many-to-Many: Project has many users
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // One-to-Many: Project has many timesheets
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    // EAV: Retrieve all attribute values for the project
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class, 'entity_id');
    }
}
