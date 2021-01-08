<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Server",
 *      required={"name", "ip_code", "locale"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ip_code",
 *          description="ip_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="locale",
 *          description="locale",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Server extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'servers';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'ip_code',
        'locale'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'ip_code' => 'string',
        'locale' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:3|max:64',
        'ip_code' => 'required|ip',
        'locale' => 'required|min:2|max:5'
    ];
}
