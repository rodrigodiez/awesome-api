<?php

namespace spec\RodrigoDiez\AwesomeAPI\Controller;

use RodrigoDiez\AwesomeAPI\Controller\PaymentController;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use RodrigoDiez\AwesomeAPI\Response\Factory\JsonResponseFactory;
use Doctrine\DBAL\Statement;

class PaymentControllerSpec extends ObjectBehavior
{
    function let(Request $getRequest, Connection $db_connection, JsonResponseFactory $response_factory, Statement $db_statement)
    {
        $getRequest->get('amount')->willReturn(42);
        $getRequest->get('table_number')->willReturn(101);
        $getRequest->get('restaurant_location')->willReturn(2);
        $getRequest->get('reference')->willReturn('foo');
        $getRequest->get('card_type')->willReturn('visa');
        $getRequest->get('gratuity')->willReturn(5);

        $db_connection->prepare(Argument::cetera())->willReturn($db_statement);
        $db_connection->insert(Argument::cetera())->willReturn(null);

        $db_statement->bindValue(Argument::cetera())->willReturn(true);
        $db_statement->execute()->willReturn(true);
        $db_statement->fetchAll()->willReturn([]);

        $this->beConstructedWith($db_connection, $response_factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PaymentController::class);
    }

    function its_post_should_return_a_successfull_json_response_if_check_was_successfully_processed(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $response_factory->createSuccessfull(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_success_message_if_check_was_successfully_processed(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $response_factory->createSuccessfull('Payment check processed successfully')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_amount_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('amount')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_amount_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('amount')->willReturn(null);
        $response_factory->createBadRequest('amount field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_table_number_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('table_number')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_table_number_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('table_number')->willReturn(null);
        $response_factory->createBadRequest('table_number field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_restaurant_location_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('restaurant_location')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_restaurant_location_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('restaurant_location')->willReturn(null);
        $response_factory->createBadRequest('restaurant_location field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_reference_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('reference')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_reference_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('reference')->willReturn(null);
        $response_factory->createBadRequest('reference field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_card_type_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('card_type')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_card_type_is_missing(Request $getRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $getRequest->get('card_type')->willReturn(null);
        $response_factory->createBadRequest('card_type field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_save_payment_data_on_the_database(Request $getRequest, Connection $db_connection)
    {
        $amount = 42;
        $table_number = 101;
        $restaurant_location = 2;
        $reference = 'foo';
        $card_type = 'visa';
        $gratuity = 5;

        $getRequest->get('amount')->willReturn($amount);
        $getRequest->get('table_number')->willReturn($table_number);
        $getRequest->get('restaurant_location')->willReturn($restaurant_location);
        $getRequest->get('reference')->willReturn($reference);
        $getRequest->get('card_type')->willReturn($card_type);
        $getRequest->get('gratuity')->willReturn($gratuity);

        $db_connection->insert('checks', [
            'amount' => $amount,
            'table_number' => $table_number,
            'restaurant_location' => $restaurant_location,
            'reference' => $reference,
            'card_type' => $card_type,
            'gratuity' => $gratuity
        ])->shouldBeCalled();

        $this->post($getRequest);
    }

    function its_post_should_return_a_server_error_json_response_if_something_goes_wrong_while_saving_on_the_database(Request $getRequest, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_post_should_return_a_generic_error_message_if_something_goes_wrong_while_saving_on_the_database(Request $getRequest, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError('Oops! Something went wrong. Please try again later')->shouldBeCalled()->willReturn($response);

        $this->post($getRequest)->shouldBe($response);
    }

    function its_get_should_return_a_successfull_json_response(Request $postRequest, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $response_factory->createSuccessfull(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->get($postRequest)->shouldBe($response);
    }

    function its_get_should_retrieve_all_payments_applied_in_the_last_24h_recent_first(Request $postRequest, Connection $db_connection, Statement $db_statement){
        $db_connection->prepare('SELECT * from checks WHERE created_at > :created_since ORDER BY created_at DESC')->shouldBeCalled()->willReturn($db_statement);
        $db_statement->bindValue('created_since', Argument::that(function ($created_since){
            $yesterday = (new \DateTime())->modify('-24 hours');

            return $yesterday == $created_since;
        }), "datetime")->shouldBeCalled();
        $db_statement->execute()->shouldBeCalled();
        $db_statement->fetchAll()->shouldBeCalled();

        $this->get($postRequest);
    }

    function its_get_should_filter_by_restaurant_location_if_provided(Request $postRequest, Connection $db_connection, Statement $db_statement){
        $restaurant_location = 42;
        $postRequest->get('restaurant_location')->willReturn($restaurant_location);
        $db_connection->prepare('SELECT * from checks WHERE created_at > :created_since AND restaurant_location = :restaurant_location ORDER BY created_at DESC')->shouldBeCalled()->willReturn($db_statement);
        $db_statement->bindValue('restaurant_location', $restaurant_location)->shouldBeCalled();
        $db_statement->execute()->shouldBeCalled();
        $db_statement->fetchAll()->shouldBeCalled();

        $this->get($postRequest);
    }

    function its_get_should_not_try_to_bind_restaurant_location_value_if_not_provided(Request $getRequest, Connection $db_connection, Statement $db_statement){
        $getRequest->get('restaurant_location')->willReturn(null);
        $db_statement->bindValue('restaurant_location', Argument::cetera())->shouldNotBeCalled();

        $this->get($getRequest);
    }


    function its_get_should_return_retrieved_payments_in_the_response_data_field(Request $postRequest, Statement $db_statement, JsonResponseFactory $response_factory, JsonResponse $response){
        $data = ['payment_1', 'payment_2'];
        $db_statement->fetchAll()->willReturn($data);

        $response_factory->createSuccessfull(Argument::any(), $data)->shouldBeCalled()->willReturn($response);

        $this->get($postRequest)->shouldBe($response);
    }

    function its_get_should_return_a_server_error_json_response_if_something_goes_wrong_while_preparing_the_db_statement(Request $getRequest, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->prepare(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->get($getRequest)->shouldBe($response);
    }

    function its_get_should_return_a_generic_error_message_if_something_goes_wrong_while_preparing_the_db_statement(Request $getRequest, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->prepare(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError('Oops! Something went wrong. Please try again later')->shouldBeCalled()->willReturn($response);

        $this->get($getRequest)->shouldBe($response);
    }

    function its_get_should_return_a_server_error_json_response_if_something_goes_wrong_while_executing_the_db_statement(Request $getRequest, Statement $db_statement, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_statement->execute()->willReturn(false);
        $response_factory->createServerError(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->get($getRequest)->shouldBe($response);
    }

    function its_get_should_return_a_generic_error_message_if_something_goes_wrong_while_executing_the_db_statement(Request $getRequest, Statement $db_statement, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_statement->execute()->willReturn(false);
        $response_factory->createServerError('Oops! Something went wrong. Please try again later')->shouldBeCalled()->willReturn($response);

        $this->get($getRequest)->shouldBe($response);
    }
}
