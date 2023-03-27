<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Parsedown;

class Announcement extends Model
{
    /**
     * The options provided by announcements.
     *
     * @var string[]
     */
    public const ENUM = [
        'All',
        'All Day',
        'Evening',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'family_type',
        'content',
    ];

    public function getFormattedContentAttribute()
    {
        $parsedown = new Parsedown();
        $formattedContent = $parsedown->text($this->content);

        // The content can include Blade variables!
        $content = Blade::compileString($formattedContent);

        return new HtmlString($content);
    }

    public static function forFamily(Family $family)
    {
        $familyTypes = $family->announcement_types;

        return self::whereIn('family_type', $familyTypes)->latest()->get();
    }
}
