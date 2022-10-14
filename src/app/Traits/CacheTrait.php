<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    public $cacheData;
    public string $cacheName;
    public bool $isUseCache;

    public function __construct()
    {
    }

    public static function useCache()
    {
        $cacheName = self::class;

        $instance = new self; 
        $instance->isUseCache = true;
        $instance->cacheName = $cacheName;
        
        $instance->cacheData = Cache::remember($cacheName, 300, fn () => self::all());
      
        return $instance;
    }

    public function whereCache(string $column, mixed $value, ?string $operation = '=') : self
    {
        $record = $this->cacheData->where($column, $operation, $value);

        if ($record->isEmpty()) {
            Cache::forget($this->cacheName);

            $this->cacheData = Cache::remember($this->cacheName, 300, fn () => self::all());

            $this->cacheData = $this->cacheData->where($column, $operation, $value);
            return $this;
        }

        $this->cacheData = $record;
        return $this;
    }

    public function whereInCache(string $column, array $value) : self
    {
        $record = $this->cacheData->whereIn($column, $value);

        if ($record->isEmpty()) {
            Cache::forget($this->cacheName);

            $this->cacheData = Cache::remember($this->cacheName, 300, fn () => self::all());
            $this->cacheData = $this->cacheData->whereIn($column, $value);
            return $this;
        }

        $this->cacheData = $record;
        return $this;
    }

    public function firstWhereCache(string $column, mixed $value, ?string $operation = '=')
    {
        $record = $this->cacheData->firstWhere($column, $operation, $value);

        if (empty($record)) {
            Cache::forget($this->cacheName);

            $this->cacheData = Cache::remember($this->cacheName, 300, fn () => self::all());

            return $this->cacheData->firstWhere($column, $operation, $value);
        }

        return $record;
    }

    public function firstCache(?string $column = null)
    {
        $record = $this->cacheData->first();
        
        if (empty($column)) {
            return $record;
        } else {
            return data_get($record, $column);
        }
    }

    public function getCache()
    {
        return $this->cacheData;
    }
}