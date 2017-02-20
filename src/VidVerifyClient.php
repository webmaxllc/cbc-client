<?php

namespace Webmax\VidVerifyClient;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;
use Webmax\VidVerifyClient\Model\VidVerifyResponse;
use Webmax\VidVerifyClient\Exception\GenericException;

/**
 * VidVerify API client
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 * @todo Fully unit test.
 */
class VidVerifyClient
{
    /**
     * Guzzle HTTP client
     *
     * @var GuzzleClient
     */
    private $client;

    /**
     * Are we in debug mode?
     *
     * @var boolean
     */
    private $debug;

    /**
     * JMS Serializer
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * JMS Serializer cache directory
     *
     * @var string
     */
    private $serializerCacheDirectory;

    private $apiKey;


    /**
     * Constructor
     *
     * @param array $config
     * @param string|null $serializerCacheDirectory
     * @param boolean $debug
     */
    public function __construct(
        $endpoint,
        $apiKey,
        $config = array(),
        $serializerCacheDirectory = null,
        $debug = false
    ) {
        $config = array_merge_recursive($this->getDefaultConfig($endpoint, $apiKey), $config);

        if ($debug) {
            $config['debug'] = true;
        }

        $this->apiKey = $apiKey;
        $this->debug = $debug;
        $this->serializerCacheDirectory = $serializerCacheDirectory ?: sys_get_temp_dir();
        $this->client = new GuzzleClient($config);
    }

    /**
     * Get base Guzzle client
     *
     * @return ClientInterface
     */
    public function getGuzzleClient()
    {
        return $this->client;
    }

    /**
     * Get or create JMS serializer
     *
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        if (null === $this->serializer) {
            $serializer = SerializerBuilder::create()
                ->addMetadataDir(realpath(__DIR__.'/../metadata'), 'Webmax\\VidVerifyClient\\Model')
                ->setDebug($this->debug);

            // Only cache when not debugging.
            if (!$this->debug) {
                $serializer->setCacheDir($this->serializerCacheDirectory);
            }

            $this->serializer = $serializer->build();
        }

        return $this->serializer;
    }

    /**
     * Get default client configuration
     *
     * @param string $endpoint
     * @param string $apiKey
     * @return array
     */
    protected function getDefaultConfig($endpoint, $apiKey)
    {
        return array(
            'base_uri' => sprintf('https://%s.vidverify.com/vbank/', $endpoint),
            'connect_timeout' => 3,
            'timeout' => 5,
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
            ),
            'query' => array(
                'API_key' => $apiKey,
            )
        );
    }

    /**
     * Get invoice report.
     *
     * @param integer $year
     * @param integer|null $month
     *
     * @return InvoiceRecord[]|null
     */
    public function getInvoiceReport($year, $month = null)
    {
        $response = $this->client->request('GET', 'invoice_report', array(
            'query' => array(
                'API_key' => $this->apiKey,
                'Year' => $year,
                'Month' => $month,
            )
        ));

        $serializer = $this->getSerializer();
        $body = (string) $response->getBody();

        if ("null" === $body) {
            return;
        }

        return $serializer->deserialize($body, 'array<Webmax\VidVerifyClient\Model\InvoiceRecord>', 'json');
    }

    /**
     * Get all activity report.
     *
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return Activity[]|null
     */
    public function getAllActivities(DateTime $from, DateTime $to)
    {
        $response = $this->client->request('GET', '/vbank_api/ReportAllActivity', array(
            'query' => array(
                'API_key' => $this->apiKey,
                'From' => $from->format('Y-n-j h:i:s'),
                'To' => $to->format('Y-n-j h:i:s'),
            )
        ));

        $serializer = $this->getSerializer();
        $body = (string) $response->getBody();

        if ("null" === $body) {
            return;
        }

        return $serializer->deserialize($body, 'array<Webmax\VidVerifyClient\Model\Activity>', 'json');
    }

    /**
     * Get borrower activities.
     *
     * @param integer $borrowerId
     *
     * @return BorrowerActivity[]|null
     */
    public function getBorrowerActivities($borrowerId)
    {
        $response = $this->client->request('GET', '/vbank_api/ReportBorrowerActivity', array(
            'query' => array(
                'API_key' => $this->apiKey,
                'BorrowerNumber' => $borrowerId,
            )
        ));

        $serializer = $this->getSerializer();
        $body = (string) $response->getBody();

        if ("null" === $body) {
            return;
        }

        return $serializer->deserialize($body, 'array<Webmax\VidVerifyClient\Model\BorrowerActivity>', 'json');
    }
}
