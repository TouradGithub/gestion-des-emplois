<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model
{
    protected $fillable = ['name', 'code', 'color', 'icon', 'order'];

    /**
     * Get all subjects of this type
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_type_id');
    }

    /**
     * Get badge HTML for display
     */
    public function getBadgeAttribute()
    {
        $color = $this->color ?? '#6c757d';
        return "<span class='badge' style='background-color: {$color}; color: #fff;'><i class='{$this->icon}'></i> {$this->name}</span>";
    }
}
