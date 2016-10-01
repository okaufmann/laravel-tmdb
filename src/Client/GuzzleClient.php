<?php
/**
 * GuzzleClient.php, mighty-movies-web-app-2
 *
 * This File belongs to to Project mighty-movies-web-app-2
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 * @package YOUREOACKAGE
 */

namespace Okaufmann\LaravelTmdb\Client;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Okaufmann\LaravelTmdb\Common\ParameterBag;
use Okaufmann\LaravelTmdb\Exception\NullResponseException;
use Okaufmann\LaravelTmdb\Exception\TmdbApiException;

class GuzzleClient
{
    public function __construct()
    {
        $this->guzzle = new Guzzle([
            'base_uri' => HttpClient::TMDB_URI,
        ]);
    }

    /**
     * Format the request for Guzzle
     *
     * @param  Request $request
     * @return array
     */
    public function getConfiguration(Request $request)
    {
        $this->request = $request;

        return [
            'headers' => $request->getHeaders()->all(),
            'query'   => $request->getParameters()->all(),
        ];
    }

    /**
     * Create the response object
     *
     * @param Response $guzzleResponse
     * @return \Okaufmann\LaravelTmdb\Client\Response
     */
    private function createResponse(GuzzleResponse $guzzleResponse = null)
    {
        $response = new Response();

        if ($guzzleResponse !== null) {
            $response->setCode($guzzleResponse->getStatusCode());
            $response->setHeaders(new ParameterBag($guzzleResponse->getHeaders()));
            $response->setBody((string) $guzzleResponse->getBody());
        }

        return $response;
    }

    /**
     * Create the unified exception to throw
     *
     * @param  Request  $request
     * @param  Response $response
     * @return TmdbApiException
     */
    protected function createApiException(Request $request, Response $response)
    {
        $errors = json_decode((string) $response->getBody());

        return new TmdbApiException(
            $errors->status_code,
            $errors->status_message,
            $request,
            $response
        );
    }

    /**
     * Create the request exception
     *
     * @param  Request               $request
     * @param  RequestException|null $previousException
     * @throws \Okaufmann\LaravelTmdb\Exception\TmdbApiException
     */
    protected function handleRequestException(Request $request, RequestException $previousException)
    {
        if (null !== $previousException && null == $previousException->getResponse()) {
            throw new NullResponseException($this->request, $previousException);
        }

        // TODO: cleanup previousException. Seems not to be used anywhere.
        throw $this->createApiException(
            $request,
            $this->createResponse($previousException->getResponse())// ,
        // $previousException
        );
    }


    /**
     * {@inheritDoc}
     */
    public function get(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->get(
                $request->getPath(),
                $this->getConfiguration($request)
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function post(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->post(
                $request->getPath(),
                array_merge(
                    ['body' => $request->getBody()],
                    $this->getConfiguration($request)
                )
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function put(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->put(
                $request->getPath(),
                array_merge(
                    ['body' => $request->getBody()],
                    $this->getConfiguration($request)
                )
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function patch(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->patch(
                $request->getPath(),
                array_merge(
                    ['body' => $request->getBody()],
                    $this->getConfiguration($request)
                )
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->delete(
                $request->getPath(),
                array_merge(
                    ['body' => $request->getBody()],
                    $this->getConfiguration($request)
                )
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function head(Request $request)
    {
        $response = null;

        try {
            $response = $this->guzzle->head(
                $request->getPath(),
                $this->getConfiguration($request)
            );
        } catch (RequestException $e) {
            $this->handleRequestException($request, $e);
        }

        return $this->createResponse($response);
    }

    /**
     * Retrieve the Guzzle Client
     *
     * @return Guzzle
     */
    public function getGuzzle()
    {
        return $this->guzzle;
    }
}