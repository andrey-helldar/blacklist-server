<?php

namespace Helldar\BlacklistServer\Http\Controllers;

use Exception;
use Helldar\BlacklistServer\Facades\Blacklist;
use Helldar\BlacklistServer\Models\Blacklist as BlacklistModel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

use function api_response;
use function is_array;

class IndexController extends Controller
{
    use ValidatesRequests;

    private $code = 200;

    private $message = 'ok';

    public function store(Request $request)
    {
        try {
            $this->message = Blacklist::store($request);
        }
        catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());
        }
        catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();
        }
        finally {
            return $this->response();
        }
    }

    public function check(Request $request)
    {
        try {
            Blacklist::check($request);
        }
        catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());
        }
        catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();
        }
        finally {
            return $this->response();
        }
    }

    private function response(): JsonResponse
    {
        $message = is_array($this->message) || $this->message instanceof BlacklistModel
            ? $this->message
            : [$this->message];

        return api_response($message, $this->code);
    }
}
