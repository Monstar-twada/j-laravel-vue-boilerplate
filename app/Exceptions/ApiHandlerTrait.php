<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Exceptions\CustomException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait ApiHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJsonResponseForException(Request $request, Exception $e)
    {
        switch (true) {
            case ($e instanceof  \Prettus\Validator\Exceptions\ValidatorException):
                $retval = $this->prettusValidatorException($e);
                break;
            case ($e instanceof \Swift_TransportException):
                $retval = $this->swiftTransportException($e);
                break;
            case ($e instanceof ModelNotFoundException):
                $retval = $this->modelNotFound($e);
                break;
            case ($e instanceof QueryException):
                $retval = $this->queryException($e);
                break;
            case ($e instanceof UnauthorizedHttpException):
                $retval = $this->unauthorizedException($e);
                break;
            case ($e instanceof CustomException):
                $retval = $this->customException($e);
                break;
            default:
                $retval = $this->badRequest($e);
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest(Exception $e, $statusCode = 500)
    {
        $payload = [
            'message'
            => "システムエラーが発生しました。メンテナンス担当者にお問い合わせ下さい。",
        ];

        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }


    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    protected function transformResponse(array $payload = null, Exception $e)
    {
        if (!isset($payload['display'])) {
            $payload['display'] = 'alert|error';
        }

        //only display these when environment is not production
        if (!\Config::get('app.debug')) {
            return $payload;
        }
        if (!isset($payload['code'])) {
            $payload['code'] = $e->getCode();
        }
        if (!isset($payload['file'])) {
            $payload['file'] = $e->getFile();
        }
        if (!isset($payload['line'])) {
            $payload['line'] = $e->getLine();
        }
        if (!isset($payload['error'])) {
            $payload['error'] = $e->getMessage();
        }
        if (!isset($payload['trace'])) {
            $payload['trace'] = $e->getTrace();
        }

        return $payload;
    }

    protected function modelNotFound($e, $statusCode = 404)
    {
        $payload = [
            'type'    => "ModelNotFoundException",
            'message' => "The Item you're searching is not available",
            'model'   => $e->getModel(),
        ];
        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }

    protected function queryException($e, $statusCode = 500)
    {
        $message = "システムエラーが発生しました。メンテナンス担当者にお問い合わせ下さい。";
        $code = $e->getCode();

        //switch($code)
        //{
        //    case 22001: $message = "input data is too long";break;
        //    case 'HY000':break;
        //}

        $payload = [
            'type'    => "QueryException",
            'message' => $message,
            'info' => $e->errorInfo,
        ];

        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }

    protected function prettusValidatorException($e, $statusCode = 422)
    {
        $messages = $bag = $e->getMessageBag()->getMessages();
        $message = "";
        foreach ($messages as $key => $msg) {
            $message .= is_array($msg) ? implode($msg, "\r\n") : $msg;
        }
        $payload = [
            'type'    => "ValidatorException",
            'message' => $message,
            'info' => $e->getMessageBag(),
        ];

        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }

    protected function swiftTransportException($e, $statusCode = 503)
    {
        $message = "メールエラーが発生しました。受信者にメールを送信できません。保守員に連絡してください。";

        $payload = [
            'type'    => "SwiftTransportException",
            'message' => $message,
            'info' => $e->getMessage(),
        ];

        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }

    protected function unauthorizedException($e, $statusCode = 401)
    {
        $payload = [
            'type'    => "UnauthorizedException",
            'message' => $e->getMessage(),
        ];
        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }

    protected function customException($e, $statusCode = 500)
    {
        $payload = [
            'type'    => $e->getType(),
            'message' => $e->getMessage(),
        ];
        return $this->jsonResponse($this->transformResponse($payload, $e), $statusCode);
    }
}
