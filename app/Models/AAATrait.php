<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2021/5/10
 * Time: 2:43 下午
 */

namespace App\Models;

use Exception;
use HughCube\Laravel\Knight\Database\Eloquent\Traits\Model as KnightModel;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $data_version
 */
trait AAATrait
{
    use KnightModel;
    use HasFactory;

    public function isAvailable(): bool
    {
        return !$this->isDeleted();
    }

    public function getCacheVersion(): string
    {
        return '1.0.0';
    }

    public function getCache(): ?Repository
    {
        /** @var null|string $driver */
        $driver = config('cache.model_cache_driver') ?: null;

        return empty($driver) ? null : Cache::store($driver);
    }

    /**
     * 不存在的记录, 不做缓存
     */
    public function getCachePlaceholder(): ?string
    {
        return null;
    }

    /**g
     * @throws Exception
     */
    public function getCacheTtl(int $duration = null): int
    {
        return $duration ?: random_int((5 * 24 * 3600), (7 * 24 * 3600));
    }

    /**
     * @throws Exception
     */
    public function genDataVersion(): int
    {
        return abs(crc32(serialize([random_bytes(100), microtime(), $this->getAttributes()])));
    }

    /**
     * @throws Exception
     */
    public function resetDataVersion(): static
    {
        $this->data_version = $this->genDataVersion();
        return $this;
    }
}
