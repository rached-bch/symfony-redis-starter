<?php
namespace App\Util;

interface CacheHelper
{
    public function get(string $key);
 
    public function set(array $options);

    public function delete(array $options);
 
    public function persist(array $options);
}