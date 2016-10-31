<?php

namespace RodrigoDiez\AwesomeAPI\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use RodrigoDiez\AwesomeAPI\Response\Factory\JsonResponseFactory;

class PaymentController
{
    private $db_connection;
    private $response_factory;
    private $error_message = 'Oops! Something went wrong. Please try again later';

    public function __construct(Connection $db_connection, JsonResponseFactory $response_factory)
    {
        $this->db_connection = $db_connection;
        $this->response_factory = $response_factory;
    }

    public function post(Request $request)
    {
        $required_fields = ['amount', 'table_number', 'restaurant_location', 'reference', 'card_type'];

        foreach ($required_fields as $required_field) {
            if ($request->get($required_field) === null) {
                return $this->response_factory->createBadRequest(sprintf("%s field is mandatory", $required_field));
            }
        }

        try {
            $this->db_connection->insert('checks', [
                'amount' => $request->get('amount'),
                'table_number' => $request->get('table_number'),
                'restaurant_location' => $request->get('restaurant_location'),
                'reference' => $request->get('reference'),
                'card_type' => $request->get('card_type'),
                'gratuity' => $request->get('gratuity')
            ]);
        } catch (\Exception $e) {
            return $this->response_factory->createServerError($this->error_message);
        }

        return $this->response_factory->createSuccessfull('Payment check processed successfully');
    }

    public function get(Request $request)
    {
        $restaurant_location = $request->get('restaurant_location');

        if ($restaurant_location === null) {
            $query = 'SELECT * from checks WHERE created_at > :created_since ORDER BY created_at DESC';
        } else {
            $query = 'SELECT * from checks WHERE created_at > :created_since AND restaurant_location = :restaurant_location ORDER BY created_at DESC';
        }

        try {
            $db_statement = $this->db_connection->prepare($query);
        } catch (\Exception $e) {
            return $this->response_factory->createServerError($this->error_message);
        }

        $db_statement->bindValue('created_since', (new \DateTime())->modify('-24 hours'), "datetime");

        if ($restaurant_location !== null) {
            $db_statement->bindValue('restaurant_location', $restaurant_location);
        }

        if ($db_statement->execute() === false) {
            return $this->response_factory->createServerError($this->error_message);
        }

        return $this->response_factory->createSuccessfull('Success', $db_statement->fetchAll());
    }
}
