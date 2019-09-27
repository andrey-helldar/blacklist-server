<?php

namespace Helldar\BlacklistServer\Http\Controllers;

use Exception;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistCore\Facades\Validator;
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

    private $additional_msg = [];

    public function store(Request $request)
    {
        try {
            $this->message = Blacklist::store($request->all());
        }
        catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        finally {
            return $this->response();
        }
    }

    public function check(Request $request)
    {
        try {
            Blacklist::check($request->get('value'));
        }
        catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        finally {
            return $this->response();
        }
    }

    public function exists(Request $request)
    {
        try {
            Validator::validate($request->all(), false);

            $value = $request->get('value');

            $is_exists = Blacklist::exists($value);

            if ($is_exists) {
                throw new BlacklistDetectedException($value);
            }
        }
        catch (ValidationException $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = Arr::flatten($exception->errors());

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        catch (Exception $exception) {
            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();

            Arr::set($this->additional_msg, 'request', $request->all());
        }
        finally {
            return $this->response();
        }
    }

    private function response(): JsonResponse
    {
        $message = $this->code == 200 || is_array($this->message) || $this->message instanceof BlacklistModel
            ? $this->message
            : [$this->message];

        return api_response($message, $this->code, [], $this->additional_msg);
    }
}
