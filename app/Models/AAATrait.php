<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2021/5/10
 * Time: 2:43 下午
 */

namespace App\Models;

use HughCube\Laravel\Knight\Database\Eloquent\Traits\Model as KnightModel;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $data_version
 */
trait AAATrait
{
    use KnightModel;
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (defined(sprintf('%s::DELETED_AT', static::class))) {
            $this->casts[static::DELETED_AT] = 'datetime';
        }
    }

    public function getModelCachePrefix(): ?string
    {
        return 'm1';
    }

    public function getCache(): null|Repository
    {
        return null;
    }
}
