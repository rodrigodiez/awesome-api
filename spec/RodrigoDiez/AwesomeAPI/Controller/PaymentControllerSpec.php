<?php

namespace spec\RodrigoDiez\AwesomeAPI\Controller;

use RodrigoDiez\AwesomeAPI\Controller\PaymentController;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use RodrigoDiez\AwesomeAPI\Response\Factory\JsonResponseFactory;

class PaymentControllerSpec extends ObjectBehavior
{
    function let(Request $request, Connection $db_connection, JsonResponseFactory $response_factory)
    {
        $request->get('amount')->willReturn(42);
        $request->get('table_number')->willReturn(101);
        $request->get('restaurant_location')->willReturn(2);
        $request->get('reference')->willReturn('foo');
        $request->get('card_type')->willReturn('visa');
        $request->get('gratuity')->willReturn(5);

        $this->beConstructedWith($db_connection, $response_factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PaymentController::class);
    }

    function its_post_should_return_a_successfull_json_response_if_check_was_successfully_processed(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $response_factory->createSuccessfull(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_success_message_if_check_was_successfully_processed(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $response_factory->createSuccessfull('Payment check processed successfully')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_amount_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('amount')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_amount_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('amount')->willReturn(null);
        $response_factory->createBadRequest('amount field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_table_number_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('table_number')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_table_number_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('table_number')->willReturn(null);
        $response_factory->createBadRequest('table_number field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_restaurant_location_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('restaurant_location')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_restaurant_location_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('restaurant_location')->willReturn(null);
        $response_factory->createBadRequest('restaurant_location field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_reference_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('reference')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_reference_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('reference')->willReturn(null);
        $response_factory->createBadRequest('reference field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_bad_request_json_response_if_card_type_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('card_type')->willReturn(null);
        $response_factory->createBadRequest(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_meaninful_error_message_if_card_type_is_missing(Request $request, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $request->get('card_type')->willReturn(null);
        $response_factory->createBadRequest('card_type field is mandatory')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_save_payment_data_on_the_database(Request $request, Connection $db_connection)
    {
        $amount = 42;
        $table_number = 101;
        $restaurant_location = 2;
        $reference = 'foo';
        $card_type = 'visa';
        $gratuity = 5;

        $request->get('amount')->willReturn($amount);
        $request->get('table_number')->willReturn($table_number);
        $request->get('restaurant_location')->willReturn($restaurant_location);
        $request->get('reference')->willReturn($reference);
        $request->get('card_type')->willReturn($card_type);
        $request->get('gratuity')->willReturn($gratuity);

        $db_connection->insert('checks', [
            'amount' => $amount,
            'table_number' => $table_number,
            'restaurant_location' => $restaurant_location,
            'reference' => $reference,
            'card_type' => $card_type,
            'gratuity' => $gratuity
        ])->shouldBeCalled();

        $this->post($request);
    }

    function its_post_should_return_a_server_error_json_response_if_something_goes_wrong_while_saving_on_the_database(Request $request, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError(Argument::cetera())->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    function its_post_should_return_a_mostly_useless_error_message_if_something_goes_wrong_while_saving_on_the_database(Request $request, Connection $db_connection, JsonResponseFactory $response_factory, JsonResponse $response)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');
        $response_factory->createServerError('Oops! Something went wrong. Please try again later')->shouldBeCalled()->willReturn($response);

        $this->post($request)->shouldBe($response);
    }

    public function getMatchers()
    {
        return [
            'beJsonMessage' => function($response, $message) {

                $response_data = json_decode($response->getContent(), true);

                return $response_data['message'] == $message;
            }
        ];
    }
}
