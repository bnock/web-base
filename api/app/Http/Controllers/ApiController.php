<?php
namespace App\Http\Controllers;

use http\Exception\BadUrlException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class ApiController extends Controller
{
    protected const ENTITY_NAMESPACE = 'App';

    public function all(Request $request): JsonResponse
    {

    }
}
