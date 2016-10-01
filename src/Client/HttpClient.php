<?php
/**
 * HttpClient.php, mighty-movies-web-app-2
 *
 * This File belongs to to Project mighty-movies-web-app-2
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 * @package YOUREOACKAGE
 */

namespace Okaufmann\LaravelTmdb\Client;

use Okaufmann\LaravelTmdb\Common\ParameterBag;
use Okaufmann\LaravelTmdb\Exception\RuntimeException;

class HttpClient
{
    /** Base API URI */
    const TMDB_URI = 'https://api.themoviedb.org/3/';

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $apiKey;


    /**
     * HttpClient constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $this->guzzleClient = new GuzzleClient();
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'GET', $parameters, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'POST', $parameters, $headers, $body);
    }

    /**
     * {@inheritDoc}
     */
    public function head($path, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'HEAD', $parameters, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body = null, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'PUT', $parameters, $headers, $body);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'PATCH', $parameters, $headers, $body);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $parameters = [], array $headers = [])
    {
        return $this->send($path, 'DELETE', $parameters, $headers, $body);
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzleClient(): GuzzleClient
    {
        return $this->guzzleClient;
    }

    /**
     * @deprecated Use send instead!
     * @param        $url
     * @param array  $data
     * @param string $language
     * @return mixed
     */
    private function makeRequest($url, $data = [], $language = 'en')
    {
        $apiKey = $this->apiKey;

        // TODO: Allow to set from external
        $appendToResponse = [
            //'alternative_titles',
            //'content_ratings',
            //'keywords',
            //'changes',
            //'videos',
            //'translations',s
            'images',
            'external_ids',
            //'credits',
        ];

        $fullData = array_merge($data, [
            'api_key'            => $apiKey,
            'language'           => $language,
            'append_to_response' => implode(',', $appendToResponse),

        ]);

        $query = http_build_query($fullData);

        $fullUrl = $url . '?' . $query;

        $data = $this->client->get($fullUrl);
        $jsonData = json_decode((string) $data->getBody());

        return $jsonData;
    }

    /**
     * Create the request object and send it out to listening events.
     *
     * @param        $path
     * @param        $method
     * @param  array $parameters
     * @param  array $headers
     * @param  null  $body
     * @return string
     */
    private function send($path, $method, array $parameters = [], array $headers = [], $body = null)
    {
        $request = $this->createRequest($path, $method, $parameters, $headers, $body);

        switch ($request->getMethod()) {
            case 'GET':
                $response = $this->getGuzzleClient()->get($request);
                break;
            case 'HEAD':
                $response = $this->getGuzzleClient()->head($request);
                break;
            case 'POST':
                $response = $this->getGuzzleClient()->post($request);
                break;
            case 'PUT':
                $response = $this->getGuzzleClient()->put($request);
                break;
            case 'PATCH':
                $response = $this->getGuzzleClient()->patch($request);
                break;
            case 'DELETE':
                $response = $this->getGuzzleClient()->delete($request);
                break;
            default:
                throw new RuntimeException(sprintf('Unkown request method "%s".', $request->getMethod()));
        }

        return $response;
    }

    /**
     * Create the request object
     *
     * @param        $path
     * @param        $method
     * @param  array $parameters
     * @param  array $headers
     * @param        $body
     * @return Request
     */
    private function createRequest($path, $method, $parameters = [], $headers = [], $body)
    {
        $request = new Request();

        $pBag = new ParameterBag((array) $parameters);
        $pBag->set('api_key', $this->apiKey);

        $request
            ->setPath($path)
            ->setMethod($method)
            ->setParameters($pBag)
            ->setHeaders(new ParameterBag((array) $headers))
            ->setBody($body)//->setOptions(new ParameterBag((array) $this->options))
        ;

        return $this->lastRequest = $request;
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
}