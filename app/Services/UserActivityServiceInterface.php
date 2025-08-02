<?php
namespace App\Services;

interface UserActivityServiceInterface
{
    public function log(string $action);
}
