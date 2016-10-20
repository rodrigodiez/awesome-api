<?php

namespace spec\RodrigoDiez\AwesomeAPI\Response\Factory;

use RodrigoDiez\AwesomeAPI\Response\Factory\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonResponseFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(JsonResponseFactory::class);
    }

    function its_createSuccessfull_returns_a_JsonResponse()
    {
        $this->createSuccessfull(null)->shouldHaveType(JsonResponse::class);
    }

    function its_createSuccessfull_returns_a_200_status_code_response()
    {
        $this->createSuccessfull(null)->getStatusCode()->shouldBe(200);
    }

    function its_createSuccessfull_includes_provided_message_as_first_level_key_with_string_value()
    {
        $message = 'You fight like a dairy farmer.';

        $this->createSuccessfull($message)->shouldContainJsonMessage($message);
    }

    function its_createSuccessfull_includes_default_message_if_not_provided()
    {
        $this->createSuccessfull()->shouldContainJsonMessage('Success');
    }

    function its_createSuccessfull_includes_provided_data_as_first_level_key_with_array_value()
    {
        $data = [1, 2, 3];

        $this->createSuccessfull(null, $data)->shouldContainJsonData($data);
    }

    function its_createSuccessfull_sets_null_as_data_if_not_provided()
    {
        $this->createSuccessfull(null)->shouldContainJsonData([]);
    }

    function its_createServerError_returns_a_JsonResponse()
    {
        $this->createServerError(null)->shouldHaveType(JsonResponse::class);
    }

    function its_createServerError_returns_a_500_status_code_response()
    {
        $this->createServerError(null)->getStatusCode()->shouldBe(500);
    }

    function its_createServerError_includes_provided_message_as_first_level_key_with_string_value()
    {
        $message = 'You fight like a dairy farmer.';

        $this->createServerError($message)->shouldContainJsonMessage($message);
    }

    function its_createServerError_includes_default_message_if_not_provided()
    {
        $this->createServerError()->shouldContainJsonMessage('Internal server error');
    }

    function its_createServerError_sets_null_as_data()
    {
        $this->createServerError(null)->shouldContainJsonData([]);
    }

    function its_createUnauthorised_returns_a_JsonResponse()
    {
        $this->createUnauthorised(null)->shouldHaveType(JsonResponse::class);
    }

    function its_createUnauthorised_returns_a_403_status_code_response()
    {
        $this->createUnauthorised(null)->getStatusCode()->shouldBe(403);
    }

    function its_createUnauthorised_includes_provided_message_as_first_level_key_with_string_value()
    {
        $message = 'You fight like a dairy farmer.';

        $this->createUnauthorised($message)->shouldContainJsonMessage($message);
    }

    function its_createUnauthorised_includes_default_message_if_not_provided()
    {
        $this->createUnauthorised()->shouldContainJsonMessage('Unauthorised');
    }

    function its_createUnauthorised_sets_null_as_data()
    {
        $this->createUnauthorised(null)->shouldContainJsonData([]);
    }

    function its_createBadRequest_returns_a_JsonResponse()
    {
        $this->createBadRequest(null)->shouldHaveType(JsonResponse::class);
    }

    function its_createBadRequest_returns_a_400_status_code_response()
    {
        $this->createBadRequest(null)->getStatusCode()->shouldBe(400);
    }

    function its_createBadRequest_includes_provided_message_as_first_level_key_with_string_value()
    {
        $message = 'You fight like a dairy farmer.';

        $this->createBadRequest($message)->shouldContainJsonMessage($message);
    }

    function its_createBadRequest_includes_default_message_if_not_provided()
    {
        $this->createBadRequest()->shouldContainJsonMessage('Bad request');
    }

    function its_createBadRequest_sets_null_as_data()
    {
        $this->createBadRequest(null)->shouldContainJsonData([]);
    }

    public function getMatchers()
    {
        return [
            'containJsonMessage' => function($response, $message) {

                $response_data = json_decode($response->getContent(), true);

                return $response_data['message'] == $message;
            },

            'containJsonData' => function($response, $data) {

                $response_data = json_decode($response->getContent(), true);

                return $response_data['data'] == $data;
            }
        ];
    }
}
