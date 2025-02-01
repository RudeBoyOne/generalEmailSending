<?php
namespace General\EmailSending\Application\Response;

final class Response
{

    private static function json(int $status, $data = []): void
    {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public static function notFound($status, $message = 'Resource not found'): void
    {
        self::json($status, ['error' => $message]);
    }

    public static function error($status, $message = 'An error occurred', $data = []): void
    {
        self::json($status, ['error' => $message, 'data' => $data]);
    }

    public static function success($status, $message = 'Operation successful', $data = []): void
    {
        self::json($status, ['message' => $message, 'data' => $data]);
    }

    public static function noContent(): void
    {
        http_response_code(204);
        exit;
    }

}
