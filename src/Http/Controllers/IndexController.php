<?php

namespace Helldar\BlacklistServer\Http\Controllers;

use Exception;
use Helldar\BlacklistCore\Exceptions\UnknownServerTypeException;
use Helldar\BlacklistServer\Facades\Email;
use Helldar\BlacklistServer\Facades\Helpers\Validator;
use Helldar\BlacklistServer\Facades\Host;
use Helldar\BlacklistServer\Facades\Ip;
use Helldar\BlacklistServer\Facades\Phone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function api_response;
use function array_key_exists;
use function trans;

class IndexController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private $services = [
        'email' => Email::class,
        'host'  => Host::class,
        'ip'    => Ip::class,
        'phone' => Phone::class,
    ];

    private $code = 200;

    private $message;

    public function store(Request $request)
    {
        try {
            $service = $this->service($request);

            $this->message = $service::store($request->get('source')) ?: trans('blacklist_server::trans');

        } catch (ValidationException $exception) {

            $this->code    = $exception->getCode() ?: 400;
            $this->message = Validator::flatten($exception->errors());

        } catch (Exception $exception) {

            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();

        } finally {
            return api_response($this->message, $this->code);
        }
    }

    public function check(Request $request)
    {
        try {
            $service = $this->service($request);

            $this->message = $service::check($request->get('source')) ?: trans('blacklist_server::trans');

        } catch (ValidationException $exception) {

            $this->code    = $exception->getCode() ?: 400;
            $this->message = Validator::flatten($exception->errors());

        } catch (Exception $exception) {

            $this->code    = $exception->getCode() ?: 400;
            $this->message = $exception->getMessage();

        } finally {
            return api_response($this->message, $this->code);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Helldar\BlacklistCore\Exceptions\UnknownServerTypeException
     * @return mixed
     */
    private function service(Request $request)
    {
        $type = $request->get('type');

        if (array_key_exists($type, $this->services)) {
            return $this->services[$type];
        }

        throw new UnknownServerTypeException($type);
    }
}
