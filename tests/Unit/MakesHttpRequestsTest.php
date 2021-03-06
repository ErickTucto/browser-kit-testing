<?php

namespace Laravel\BrowserKitTesting\Tests\Unit;

use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests;
use Laravel\BrowserKitTesting\Tests\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

class MakesHttpRequestsTest extends TestCase
{
    use MakesHttpRequests;

    /**
     * @test
     * @dataProvider dataUrls
     */
    public function prepareUrlForRequest_method_return_all_url($url, $expectedUrl)
    {
        $this->baseUrl = 'http://localhost';
        $this->assertSame(
            $this->prepareUrlForRequest($url),
            $expectedUrl
        );
    }

    public function dataUrls()
    {
        return [
            ['', 'http://localhost'],
            ['/', 'http://localhost'],
            ['users', 'http://localhost/users'],
            ['/users', 'http://localhost/users'],
            ['users/', 'http://localhost/users'],
            ['/users/', 'http://localhost/users'],
        ];
    }

    /**
     * @test
     */
    public function seeStatusCode_check_status_code()
    {
        $this->response = new class {
            public function getStatusCode()
            {
                return 200;
            }
        };
        $this->seeStatusCode(200);
    }

    /**
     * @test
     */
    public function assertResponseOk_check_that_the_status_page_should_be_200()
    {
        $this->response = new class {
            public function getStatusCode()
            {
                return 200;
            }

            public function isOk()
            {
                return true;
            }
        };
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function assertResponseOk_throw_exception_when_the_status_page_is_not_200()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected status code 200, got 404.');

        $this->response = new class {
            public function getStatusCode()
            {
                return 404;
            }

            public function isOK()
            {
                return false;
            }
        };
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function assertResponseStatus_check_the_response_status_is_equal_to_passed_by_parameter()
    {
        $this->response = new class {
            public function getStatusCode()
            {
                return 200;
            }
        };
        $this->assertResponseStatus(200);
    }

    /**
     * @test
     */
    public function assertResponseStatus_throw_exception_when_the_response_status_is_not_equal_to_passed_by_parameter()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected status code 404, got 200.');

        $this->response = new class {
            public function getStatusCode()
            {
                return 200;
            }
        };
        $this->assertResponseStatus(404);
    }
}
