<?php

namespace Helldar\BlacklistServer\Http\Controllers;

use Exception;
use Helldar\BlacklistCore\Exceptions\BlacklistDetectedException;
use Helldar\BlacklistServer\Facades\Blacklist;
use Helldar\BlacklistServer\Facades\Validator;
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

    private $additional_msg = [];

    private $code = 200;

    private $message = 'ok';

    public function store(Request $request)
    {
        try {
            $value = $request->get('value');
            $type  = $request->get('type');

            $this->message = Blacklist::store($value, $type);
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
            $value = $request->get('value');
            $type  = $request->get('type');

            Blacklist::check($value, $type);
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
            $type  = $request->get('type');

            $is_exists = Blacklist::exists($value, $type);

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
