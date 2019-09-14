<?php

namespace Helldar\BlacklistServer\Http\Controllers;

use function api_response;
use Exception;
use Helldar\BlacklistServer\Facades\Blacklist;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

use function is_array;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    use ValidatesRequests;

    private $code = 200;

    private $message;

    public function store(Request $request)
    {
        try {
            $type  = $request->get('type');
            $value = $request->get('value');

            $this->message = Blacklist::store($type, $value);
        } catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());
        } catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();
        } finally {
            return $this->response();
        }
    }

    public function check(Request $request)
    {
        try {
            $type  = $request->get('type');
            $value = $request->get('value');

            $this->message = Blacklist::check($type, $value)
                ? 'ok'
                : null;
        } catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());
        } catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();
        } finally {
            return $this->response();
        }
    }

    private function response(): JsonResponse
    {
        $message = is_array($this->message)
            ? $this->message
            : [$this->message];

        return api_response($message, $this->code);
    }
}
