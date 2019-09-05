<?php

namespace Helldar\SpammersServer\Http\Controllers;

use Exception;
use Helldar\SpammersServer\Exceptions\UnknownServerTypeException;
use Helldar\SpammersServer\Facades\Email;
use Helldar\SpammersServer\Facades\Host;
use Helldar\SpammersServer\Facades\Ip;
use Helldar\SpammersServer\Facades\Phone;
use Helldar\SpammersServer\Http\Requests\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use function api_response;
use function array_key_exists;


class IndexController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private $services = [
        'email' => Email::class,
        'host'  => Host::class,
        'ip'    => Ip::class,
        'phone' => Phone::class,
    ];

    public function store(Request $request)
    {
        try {
            $service = $this->service($request);

            $result = $service::store($request->get('source'));

            return api_response($result);

        } catch (Exception $exception) {
            return api_response($exception->getMessage(), $exception->getCode() ?: 500);
        }
    }

    public function exists(Request $request)
    {
        try {
            $service = $this->service($request);

            $result = $service::exists($request->get('source'));

            return api_response($result);

        } catch (Exception $exception) {
            return api_response($exception->getMessage(), $exception->getCode() ?: 500);
        }
    }

    /**
     * @param \Helldar\SpammersServer\Http\Requests\Request $request
     *
     * @throws \Helldar\SpammersServer\Exceptions\UnknownServerTypeException
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
