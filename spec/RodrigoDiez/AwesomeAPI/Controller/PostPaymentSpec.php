<?php

namespace spec\RodrigoDiez\AwesomeAPI\Controller;

use RodrigoDiez\AwesomeAPI\Controller\PostPayment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class PostPaymentSpec extends ObjectBehavior
{
    function let(Request $request, Connection $db_connection)
    {
        $request->get('amount')->willReturn(42);
        $request->get('table_number')->willReturn(101);
        $request->get('restaurant_location')->willReturn(2);
        $request->get('reference')->willReturn('foo');
        $request->get('card_type')->willReturn('visa');

        $this->beConstructedWith($db_connection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PostPayment::class);
    }

    function its_index_should_return_a_JsonResponse(Request $request)
    {
        $this->index($request)->shouldHaveType(JsonResponse::class);
    }

    function its_index_should_return_a_meaninful_success_message_if_check_was_successfully_processed(Request $request)
    {
        $this->index($request)->shouldBeJsonMessage('Payment check processed successfully');
    }

    function its_index_should_return_a_400_BadRequest_as_status_code_if_amount_is_missing(Request $request)
    {
        $request->get('amount')->willReturn(null);

        $this->index($request)->getStatusCode()->shouldBe(400);
    }

    function its_index_should_return_a_meaninful_error_message_if_amount_is_missing(Request $request)
    {
        $request->get('amount')->willReturn(null);

        $this->index($request)->shouldBeJsonError('amount field is mandatory');
    }

    function its_index_should_return_a_400_BadRequest_as_status_code_if_table_number_is_missing(Request $request)
    {
        $request->get('table_number')->willReturn(null);

        $this->index($request)->getStatusCode()->shouldBe(400);
    }

    function its_index_should_return_a_meaninful_error_message_if_table_number_is_missing(Request $request)
    {
        $request->get('table_number')->willReturn(null);

        $this->index($request)->shouldBeJsonError('table_number field is mandatory');
    }

    function its_index_should_return_a_400_BadRequest_as_status_code_if_restaurant_location_is_missing(Request $request)
    {
        $request->get('restaurant_location')->willReturn(null);

        $this->index($request)->getStatusCode()->shouldBe(400);
    }

    function its_index_should_return_a_meaninful_error_message_if_restaurant_location_is_missing(Request $request)
    {
        $request->get('restaurant_location')->willReturn(null);

        $this->index($request)->shouldBeJsonError('restaurant_location field is mandatory');
    }

    function its_index_should_return_a_400_BadRequest_as_status_code_if_reference_is_missing(Request $request)
    {
        $request->get('reference')->willReturn(null);

        $this->index($request)->getStatusCode()->shouldBe(400);
    }

    function its_index_should_return_a_meaninful_error_message_if_reference_is_missing(Request $request)
    {
        $request->get('reference')->willReturn(null);

        $this->index($request)->shouldBeJsonError('reference field is mandatory');
    }

    function its_index_should_return_a_400_BadRequest_as_status_code_if_card_type_is_missing(Request $request)
    {
        $request->get('card_type')->willReturn(null);

        $this->index($request)->getStatusCode()->shouldBe(400);
    }

    function its_index_should_save_payment_data_on_the_database(Request $request, Connection $db_connection)
    {
        $amount = 42;
        $table_number = 101;
        $restaurant_location = 2;
        $reference = 'foo';
        $card_type = 'visa';

        $request->get('amount')->willReturn($amount);
        $request->get('table_number')->willReturn($table_number);
        $request->get('restaurant_location')->willReturn($restaurant_location);
        $request->get('reference')->willReturn($reference);
        $request->get('card_type')->willReturn($card_type);

        $db_connection->insert('checks', [
            'amount' => $amount,
            'table_number' => $table_number,
            'restaurant_location' => $restaurant_location,
            'reference' => $reference,
            'card_type' => $card_type
        ])->shouldBeCalled();

        $this->index($request);
    }

    function its_index_should_return_a_500_InternalServerError_if_something_goes_wrong_while_saving_on_the_database(Request $request, Connection $db_connection)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');

        $this->index($request)->getStatusCode()->shouldBe(500);
    }

    function its_index_should_return_a_mostly_useless_error_message_if_something_goes_wrong_while_saving_on_the_database(Request $request, Connection $db_connection)
    {
        $db_connection->insert(Argument::cetera())->willThrow('\Exception');

        $this->index($request)->shouldBeJsonError('Oops! Something went wrong. Please try again later');
    }

    public function getMatchers()
    {
        return [
            'beJsonError' => function($response, $error) {

                $response_data = json_decode($response->getContent(), true);

                return $response_data['error'] == $error;
            },

            'beJsonMessage' => function($response, $message) {

                $response_data = json_decode($response->getContent(), true);

                return $response_data['message'] == $message;
            }
        ];
    }
}
