<?php

namespace App\Http\Middleware\Core;

use App\Exceptions\ApplicationException;
use App\Exceptions\TestPspHeaderException;
use App\Http\Requests\FormRequest;
use Closure;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

final class PspScenario
{
    private Request $request;

    /**
     * Handle an incoming request and define PSP and TestCase
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->request = $request;
        $psp = $this->request->header('Test-Psp');

        $pspNamespace = config('psp.' . $psp . '.namespace') ?: config('psp.default_settings.namespace');
        if (!$pspNamespace) {
            throw new ApplicationException('Unable to determine namespace');
        }

        $pspClass = $pspNamespace . $psp;
        if (!class_exists($pspClass)) {
            throw new TestPspHeaderException($psp . ' psp service is absent');
        }

        $pspTestCaseMethod = $this->getPspTestCaseMethod($psp);
        if (!method_exists($pspClass, $pspTestCaseMethod)) {
            throw new ApplicationException($pspClass . ' service doesnt have method ' . $pspTestCaseMethod);
        }


        $validationNamespace = 'App\\Http\\Requests\\' . $psp . '\\' . $pspTestCaseMethod . '\\';
        if (class_exists($validationNamespace . 'Headers')) {
            /** @var FormRequest $headersRequest */
            $headersRequest = new ($validationNamespace . 'Headers')();
            $this->validateHeaders($headersRequest);
        }

        if (class_exists($validationNamespace . 'Request')) {
            /** @var FormRequest $formRequest */
            $formRequest = new ($validationNamespace . 'Request')();
            $this->validateRequest($formRequest);
        }

        $this->request->headers->set('Test-Psp-ClassName', $pspClass);
        $this->request->headers->set('Test-Psp-MethodName', $pspTestCaseMethod);

        return $next($this->request);
    }

    /**
     * validating headers based on determined PSP
     *
     * @param FormRequest $headersRequest
     * @return void
     */
    private function validateHeaders(FormRequest $headersRequest): void
    {
        $headersValidator = Validator::make(
            getallheaders(),
            $headersRequest->rules(),
            Lang::get('headerValidation'),
        );
        $headersValidator->setCustomMessages($headersRequest->messages());


        if ($headersValidator->fails()) {
            throw new HttpResponseException(response()->json($headersValidator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
        }
    }

    /**
     *  validating request data based on determined PSP
     *
     * @param FormRequest $formRequest
     * @return void
     */
    private function validateRequest(FormRequest $formRequest): void
    {
        $validator = Validator::make(
            $this->request->all(),
            $formRequest->rules(),
            $formRequest->messages()
        );

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
        }
    }

    /**
     * Determines Psp Test case method inside the PSP service
     *
     * @param string $psp
     * @return string
     * @throws TestPspHeaderException
     */
    private function getPspTestCaseMethod(string $psp): string
    {
        $pspTestCaseField = config('psp.' . $psp . '.case.field_name') ?: config('psp.default_settings.case.field_name');
        $pspTestCaseId = $this->request->get($pspTestCaseField);
        $pspTestCaseMethod = config('psp.' . $psp . '.case.amount.' . $pspTestCaseId) ?: config('psp.default_settings.case.amount.' . $pspTestCaseId);
        if (!isset($pspTestCaseField, $pspTestCaseId, $pspTestCaseMethod)) {
            logger()->channel('init-flow')->error("Middleware.Core.PspScenario", [
                'message' => 'Cant determine test case method',
                'trace' => [
                    'pspTestCaseField' => $pspTestCaseField,
                    'pspTestCaseId' => $pspTestCaseId,
                    'pspTestCaseMethod' => $pspTestCaseMethod,
                ]
            ]);
            throw new TestPspHeaderException('Cant determine test case method');
        }

        return $pspTestCaseMethod;
    }
}
