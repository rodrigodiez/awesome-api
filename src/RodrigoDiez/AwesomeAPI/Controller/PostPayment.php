<?php

namespace RodrigoDiez\AwesomeAPI\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class PostPayment
{
    private $db_connection;

    public function __construct(Connection $db_connection)
    {
        $this->db_connection = $db_connection;
    }

    public function index(Request $request)
    {
        $status_code = 200;
        $required_fields = ['amount', 'table_number', 'restaurant_location', 'reference', 'card_type'];

        foreach ($required_fields as $required_field) {
            if ($request->get($required_field) === null) {
                return new JsonResponse(['error' => sprintf("%s field is mandatory", $required_field)], 400);
            }
        }

        try {
            $this->db_connection->insert('checks', [
                'amount' => $request->get('amount'),
                'table_number' => $request->get('table_number'),
                'restaurant_location' => $request->get('restaurant_location'),
                'reference' => $request->get('reference'),
                'card_type' => $request->get('card_type')
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Oops! Something went wrong. Please try again later'], 500);
        }

        return new JsonResponse(['message' => 'Payment check processed successfully'], 200);
    }
}
