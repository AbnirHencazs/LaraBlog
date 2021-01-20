<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     * Con esto le decimos a Laravel que si estamos enviando datos de forma masiva ($request->all()) entonces los filtre
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'iframe',
        'image',
        'user_id'
    ];
    /**
     * Return the sluggable configuration array for this model
     * Slugg es el identificador unico que se podría usar en cambio de id, en éste caso el slug sera el título del Post
     * tambien se puede ver al deslug como la manera de crear una URL amigable, ésto se usa por ejemplo para llevarnos
     * al post localhost/blog/titulo-de-post en lugar de localhost/blog/1
     * Ésto tiene mucha ventaja para posicionarse ante Google, osea tiene SEO optimizado
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);//Un post pertenece a un usuario
    }

    public function getGetExcerptAttribute()
    {
        return substr($this->body, 0, 140);
    }

    public function getGetImageAttribute()
    {
        if($this->image){
            return url("storage/$this->image");
        }
    }
}
