<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use OpenApi\Annotations as OA;

define("API_HOST", env('API_HOST'));
/**
 * @OA\Server(url=API_HOST),
 * @OA\Info(
 *    title="Prejro Crawling of Currencies", 
 *    version="0.0.1",
 *    @OA\Contact(
 *       email="cgabriel.lourenzo@gmail.com"
 *    )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
