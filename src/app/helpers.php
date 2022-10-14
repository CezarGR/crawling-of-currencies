<?php

if (!function_exists('json_response')) {
    function json_response($data, $message = null, $status_code = 200, $headers = []): \Illuminate\Http\JsonResponse
    {
        if (! $data) {
            return response()->json(
                [
                    'message' => $message,
                    'data' => []
                ],
                $status_code,
                $headers
            );
        }

        if (is_array($data)) {
            foreach ($data as $data_key => $data_value) {
                if ($data_value instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
                    $pagination = match (true) {
                        $data[$data_key]->resource instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator => $data[$data_key]->resource,
                        $data[$data_key]->resource instanceof \Illuminate\Pagination\CursorPaginator => $data[$data_key]->resource,
                        default => false
                    };

                    $response_data = [
                        'message' => $message,
                        'data' => $data,
                    ];

                    if ($pagination instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                        $response_data['pagination'] = [
                            'current_page' => $pagination->currentPage(),
                            'total_pages' => $pagination->lastPage(),
                            'items_per_page' => $pagination->perPage(),
                            'total_items' => $pagination->total()
                        ];
                    } else {
                        $response_data['pagination'] = [
                            'next_page_url' => $pagination->nextPageUrl(),
                            'previous_page_url' => $pagination->previousPageUrl(),
                        ];
                    }
                    break;
                } else {
                    $response_data = [
                        'message' => $message,
                        'data' => $data,
                    ];
                }
            }
        } else {
            if ($data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
                $collection = $data->collection;
                $pagination = ($data->resource instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    ? $data->resource
                    : false;

                $response_data = [
                    'message' => $message,
                    'data' => $collection,
                ];

                if ($pagination) {
                    $response_data['pagination'] = [
                        'current_page' => $pagination->currentPage(),
                        'total_pages' => $pagination->lastPage(),
                        'items_per_page' => $pagination->perPage(),
                        'total_items' => $pagination->total()
                    ];
                }
            } elseif ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                $response_data = [
                    'message' => $message,
                    'data' => $data->items(),
                    'pagination' => [
                        'current_page' => $data->currentPage(),
                        'total_pages' => $data->lastPage(),
                        'items_per_page' => $data->perPage(),
                        'total_items' => $data->total()
                    ]
                ];
            } elseif ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
                $response_data = [
                    'message' => $message,
                    'data' => $data,
                    'pagination' => [
                        'next_page_url' => $data->nextPageUrl(),
                        'previous_page_url' => $data->previousPageUrl(),
                    ]
                ];
            } else {
                $response_data = [
                    'message' => $message,
                    'data' => $data,
                ];
            }
        }

        return response()->json($response_data, $status_code, $headers);
    }
}

if (!function_exists('json_error_response')) {
    function json_error_response($message = null, $data = null, $status_code = 400, $headers = []): \Illuminate\Http\JsonResponse
    {
        if ($message instanceof Exception) {
            if (! app()->environment('production')) {
                return json_response([
                    'line' => $message->getLine(),
                    'file' => $message->getFile()
                ], $message->getMessage() ?? 'Um erro interno ocorreu', $status_code, $headers);
            }

            return json_response(null, $message->getMessage() ?? 'Um erro interno ocorreu', $status_code, $headers);
        }
        return json_response($data, $message ?: 'An error occurred.', $status_code, $headers);
    }
}

if (!function_exists('json_success_response')) {
    function json_success_response($data = null, $message = null, $status_code = 200, $headers = []): \Illuminate\Http\JsonResponse
    {
        return json_response($data, $message ?: 'Success.', $status_code, $headers);
    }
}