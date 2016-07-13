<?php

namespace Mangopixel\Responder\Traits;

use Illuminate\Http\JsonResponse;
use Mangopixel\Responder\Contracts\Responder;

/**
 * A trait you may apply to your base test case to give you some helper methods for
 * testing the API responses generated by the package.
 *
 * @package Laravel Responder
 * @author  Alexander Tømmerås <flugged@gmail.com>
 * @license The MIT License
 */
trait MakesApiRequests
{
    /**
     * Assert that the response is a valid success response.
     *
     * @param  mixed $data
     * @param  int   $status
     * @return $this
     */
    protected function seeSuccess( $data = null, $status = 200 )
    {
        $response = $this->seeSuccessResponse( $data, $status );
        $this->seeSuccessData( $response->getData( true )[ 'data' ] );

        return $this;
    }

    /**
     * Assert that the response is a valid success response.
     *
     * @param  mixed $data
     * @param  int   $status
     * @return $this
     */
    protected function seeSuccessEquals( $data = null, $status = 200 )
    {
        $response = $this->seeSuccessResponsee( $data, $status );
        $this->seeJsonEquals( $response->getData( true ) );

        return $this;
    }

    /**
     * Assert that the response is a valid success response.
     *
     * @param  mixed $data
     * @param  int   $status
     * @return $this
     */
    protected function seeSuccessResponse( $data = null, $status = 200 ):JsonResponse
    {
        $response = app( Responder::class )->success( $data, $status );

        $this->seeStatusCode( $response->getStatusCode() )->seeJson( [
            'success' => true,
            'status' => $response->getStatusCode()
        ] )->seeJsonStructure( [ 'data' ] );

        return $response;
    }

    /**
     * Assert that the response data contains given values.
     *
     * @param  mixed $data
     * @return $this
     */
    protected function seeSuccessData( $data = null )
    {
        collect( $data )->each( function ( $value, $key ) {
            if ( is_array( $value ) ) {
                $this->seeSuccessDataResponse( $value );
            } else {
                $this->seeJson( [ $key => $value ] );
            }
        } );

        return $this;
    }

    /**
     * Decodes JSON response and returns the data.
     *
     * @return array
     */
    protected function getSuccessData()
    {
        return $this->decodeResponseJson()[ 'data' ];
    }

    /**
     * Assert that the response is a valid error response.
     *
     * @param  string   $error
     * @param  int|null $status
     * @return $this
     */
    protected function seeError( string $error, int $status = null )
    {
        if ( ! is_null( $status ) ) {
            $this->seeStatusCode( $status );
        }

        return $this->seeJson( [
            'success' => false,
            'status' => $status
        ] )->seeJsonSubset( [
            'error' => [
                'code' => $error
            ]
        ] );
    }
}