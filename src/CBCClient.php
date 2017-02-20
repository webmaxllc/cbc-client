<?php

namespace Webmax\CBCClient;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;
use Mockery\CountValidator\Exception;
use Webmax\CBCClient\Model\CBCResponse;
use Webmax\CBCClient\Exception\GenericException;

/**
 * CBC API client
 *
 * @author Zach Rosenberg <zgr024@gmail.com>
 * @todo Fully unit test.
 */
class CBCClient
{

    const RECEIVING_NAME = "CBCInnovis";
    const RECEIVING_STREET_ADDRESS = "250 E Town ST";
    const RECEIVING_CITY = "Columbus";
    const RECEIVING_STATE = "OH";
    const RECEIVING_POSTAL_CODE = "43215";

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

    private $loginId;

    private $password;


    /**
     * Constructor
     *
     * @param array $config
     * @param string|null $serializerCacheDirectory
     * @param boolean $debug
     */
    public function __construct($env, $loginId, $password, $config = array(), $debug = false) {
        $config = array_merge_recursive($this->getDefaultConfig($env, $loginId), $config);
        if ($debug) {
            $config['debug'] = true;
        }
        $this->loginId = $loginId;
        $this->password = $password;
        $this->debug = $debug;
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
     * Get default client configuration
     *
     * @param string $endpoint
     * @param string $apiKey
     * @return array
     */
    protected function getDefaultConfig($env, $loginId)
    {
        return array(
            'base_uri' => sprintf('https://%s.creditbureaureports.com', $env, $loginId, "apiordretpost","ORD%3dIN+PA%3dXM+TEXT%3dN+PS%3dA+REVL%3dY+REVF%3dX4+SOFTWARE%3dZZ+MOPT%3d+-opt+newxmlerr"),
            'connect_timeout' => 3,
            'timeout' => 5,
            'headers' => array(
                'Content-Type' => 'application/xml',
                'Accepts' => 'application/xml',
            )
        );
    }

    /**
     * Get credit report.
     *
     * @param string $loanNumber
     * @param string $loanOfficer
     * @param object $applicant
     * @param object $requesor
     * @param object $submittor
     *
     * @return \SimpleXMLElement|null
     */
    public function getCreditReport($loanNumber, $loanOfficer, $applicant, $requestor, $submittor)
    {
        $xml = $this->getXMLFromData($loanNumber, $loanOfficer, $applicant, $requestor, $submittor);

        $response = $this->client->request('POST', sprintf('/servlet/gnbank?logid=%s&command=%s&options=%s',$this->loginId,'apiordretpost',"ORD%3dIN+PA%3dXM+TEXT%3dN+PS%3dA+REVL%3dY+REVF%3dX4+SOFTWARE%3dZZ+MOPT%3d+-opt+newxmlerr"), array(
            'body'=>$xml
        ));

        $body = (string) $response->getBody();

        if ("null" === $body) {
            return null;
        }

        return new \SimpleXMLElement($body);
    }

    protected function getXMLFromData($loanNumber,$loanOfficer,$applicant,$requestor, $submittor) {

        $mismo = new \SimpleXMLElement("<root></root>");

        try {

            $requestGroup = $mismo->addChild("REQUEST_GROUP");
            $requestGroup->addAttribute("MISMOVersionID", "2.3.1");

            $requestingParty = $requestGroup->addChild("REQUESTING_PARTY");
            $requestingParty->addAttribute("_Name", $requestor->name);
            $requestingParty->addAttribute("_StreetAddress", $requestor->streetAddress);
            $requestingParty->addAttribute("_City", $requestor->city);
            $requestingParty->addAttribute("_State", $requestor->state);
            $requestingParty->addAttribute("_PostalCode", $requestor->zip);

            $receivingParty = $requestGroup->addChild("RECEIVING_PARTY");
            $receivingParty->addAttribute("_Name", self::RECEIVING_NAME);
            $receivingParty->addAttribute("_StreetAddress", self::RECEIVING_STREET_ADDRESS);
            $receivingParty->addAttribute("_City", self::RECEIVING_CITY);
            $receivingParty->addAttribute("_State", self::RECEIVING_STATE);
            $receivingParty->addAttribute("_PostalCode", self::RECEIVING_POSTAL_CODE);

            $submittingParty = $requestGroup->addChild("RECEIVING_PARTY");
            $submittingParty->addAttribute("_Name", $submittor->name);
            $submittingParty->addAttribute("_StreetAddress", $submittor->streetAddress);
            $submittingParty->addAttribute("_City", $submittor->city);
            $submittingParty->addAttribute("_State", $submittor->state);
            $submittingParty->addAttribute("_PostalCode", $submittor->zip);

            $request = $requestGroup->addChild("REQUEST");
            $request->addAttribute("LoginAccountIdentifier",$this->loginId);
            $request->addAttribute("LoginAccountPassword",$this->password);
            $request->addAttribute("InternalAccountIdentifier","");

            $requestData = $request->addChild("REQUEST_DATA");

            $creditRequest = $requestData->addChild("CREDIT_REQUEST");
            $creditRequest->addAttribute("MISMOVersionID","2.3.1");
            $creditRequest->addAttribute("LenderCaseIdentifier",$loanNumber);
            $creditRequest->addAttribute("RequestingPartyRequestedByName",$loanOfficer);

            $creditRequestData = $creditRequest->addChild("CREDIT_REQUEST_DATA");
            $creditRequestData->addAttribute("BorrowerID","Borrower");
            $creditRequestData->addAttribute("CreditReportRequestActionType","Submit");
            $creditRequestData->addAttribute("CreditReportType","Merge");
            $creditRequestData->addAttribute("CreditRequestType",!empty($co_applicant)?'Joint':'Individual');

            $creditRepositoryIncluded = $creditRequestData->addChild("CREDIT_REPOSITORY_INCLUDED");
            $creditRepositoryIncluded->addAttribute("_EquifaxIndicator","Y");
            $creditRepositoryIncluded->addAttribute("_ExperianIndicator","Y");
            $creditRepositoryIncluded->addAttribute("_TransUnionIndicator","Y");

            $loanApplication = $creditRequest->addChild("LOAN_APPLICATION");

            $borrower = $loanApplication->addChild("BORROWER");
            $borrower->addAttribute("BorrowerID","Borrower");
            $borrower->addAttribute("_FirstName",$applicant->firstName);
            $borrower->addAttribute("_MiddleName",$applicant->middleInitial);
            $borrower->addAttribute("_LastName",$applicant->lastName);
            $borrower->addAttribute("_NameSuffix",$applicant->suffix);
            $borrower->addAttribute("_HomeTelephoneNumber",$applicant->homePhone);
            $borrower->addAttribute("_BirthDate",$applicant->dob);

            foreach ($applicant->residence as $key => $house) {
                $residence = $borrower->addChild("_RESIDENCE");
                $residence->addAttribute("_StreetAddress",$house->streetAddress);
                $residence->addAttribute("_City",$house->city);
                $residence->addAttribute("_State",$house->state);
                $residence->addAttribute("_PostalCode",$house->zip);
                $residence->addAttribute("_BorrowerResidencyType",$key===0?"Current":"Previous");
            }


        } catch (\Exception $e) {

            throw $e;

        }

        return $mismo->asXML();
    }

}
