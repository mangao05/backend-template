<?php 

namespace App\Traits;

trait FormatResponse
{
   /**
     * Returns success response
     *
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function successResponse(
        $data, 
        $message = null, 
        $status = 200
    ) : object
    {
        return response()->json(
            $this->formatSuccessResponse($data, $message),
            $status
        );
    }

    /**
     * Formats success response
     *
     * @param  array $data
     * @param  string $message
     * @return array
     */
    public function formatSuccessResponse($data, $message) : array
    {
        return [
            "success" => true,
            "error_code" => null,
            "message" => $message,
            "data" => $data
        ];
    }

    /**
     * Returns error response
     *
     * @param Illuminate\Validation\Validator $validator
     * @param string $error_code
     * @param array $data
     * @param int $status
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function errorResponse(
        $validator,
        $error_code = null,
        $data = [],
        $status = 400

    ) : object
    {
        return response()->json(
            $this->formatErrorResponse($validator, $error_code, $data),
            $status
        );
    }

    /**
     * Format error response
     *
     * @param Illuminate\Validation\Validator $validator
     * @param string $error_code
     * @param array $data
     * @param int $status
     * @return array
     */
    public function formatErrorResponse(
        $validator,
        $error_code = null,
        $data = []

    ) : array
    {
        $message = $validator;

        //Format error message
        if (!is_string($validator)) {
            $error = $validator->errors()->toArray();

            if ($validator->stopOnFirstFailure()->fails()) {
                foreach ($validator->errors()->toArray() as $key => $value) {
                    $message = $value[0];
                    break;
                }
            }
        }

        return [
            'success' => false,
            'error_code' => $error_code,
            'message' => $message,
            'data' => $data,
        ];

    }

    /**
     * Formats success response of a resource
     * Use this if the response data is a resource like paginated data.
     * Only mimics the formatSuccessResponse()
     *
     * @param $resource
     * @param $appends
     * @param $message
     * @return array
     */
    public function successResourceResponse(
        $resource,
        $message = null,
        $appends = []
    ) : array 
    {
        $additional = [
            'success' => true,
            'error_code' => null,
            'message' => $message,
        ];

        if ($appends) {
            $additional = array_merge($additional, $appends);
        }

        return $resource->additional($additional);
    }
}

