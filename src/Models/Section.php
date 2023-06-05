<?php

namespace LaraZeus\Bolt\Models;

use Database\Factories\SectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    protected $guarded = [];

    protected static function newFactory()
    {
        return SectionFactory::new();
    }

    public function fields()
    {
        return $this->hasMany(config('zeus-bolt.models.Field'), 'section_id', 'id')->orderBy('ordering');
    }

    public function form()
    {
        return $this->belongsTo(config('zeus-bolt.models.Form'));
    }
}
