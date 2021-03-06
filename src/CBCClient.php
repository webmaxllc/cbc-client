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
        $config = array_merge_recursive($this->getDefaultConfig($env), $config);
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
    protected function getDefaultConfig($env)
    {
        return array(
            'base_uri' => sprintf('https://%s.creditbureaureports.com', $env),
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
     * @param object $submitter
     *
     * @return \SimpleXMLElement|null
     */
    public function getCreditReport($json)
    {
        $xml = $this->getXMLFromJSON($json);

        $response = $this->client->request('POST', sprintf('/servlet/gnbank?logid=%s&command=%s&options=%s',$this->loginId,'apiordretpost',"ORD%3dIN+PA%3dXM+TEXT%3dN+PS%3dA+REVL%3dY+REVF%3dX4+SOFTWARE%3dZZ+MOPT%3d+-opt+newxmlerr"), array(
            'body'=>$xml
        ));

        $body = (string) $response->getBody();

        if ("null" === $body) {
            return null;
        }

        return new \SimpleXMLElement($body);
    }

    protected function getXMLFromJSON($json) {

        $data = json_decode($json);

        if (is_array($data->borrowers)) {
            $applicant = $data->borrowers[0];
            if (count($data->borrowers) > 1) {
                $co_applicant = $data->borrowers[1];
            }
        } else {
            $applicant = $data->borrowers;
        }

        try {

            $requestGroup = new \SimpleXMLElement("<REQUEST_GROUP></REQUEST_GROUP>");
            $requestGroup->addAttribute("MISMOVersionID", "2.3.1");

            $requestingParty = $requestGroup->addChild("REQUESTING_PARTY");
            $requestingParty->addAttribute("_Name", $data->requester->name);
            $requestingParty->addAttribute("_StreetAddress", $data->requester->streetAddress);
            $requestingParty->addAttribute("_City", $data->requester->city);
            $requestingParty->addAttribute("_State", $data->requester->state);
            $requestingParty->addAttribute("_PostalCode", $data->requester->zip);

            $receivingParty = $requestGroup->addChild("RECEIVING_PARTY");
            $receivingParty->addAttribute("_Name", self::RECEIVING_NAME);
            $receivingParty->addAttribute("_StreetAddress", self::RECEIVING_STREET_ADDRESS);
            $receivingParty->addAttribute("_City", self::RECEIVING_CITY);
            $receivingParty->addAttribute("_State", self::RECEIVING_STATE);
            $receivingParty->addAttribute("_PostalCode", self::RECEIVING_POSTAL_CODE);

            $submittingParty = $requestGroup->addChild("RECEIVING_PARTY");
            $submittingParty->addAttribute("_Name", $data->submitter->name);
            $submittingParty->addAttribute("_StreetAddress", $data->submitter->streetAddress);
            $submittingParty->addAttribute("_City", $data->submitter->city);
            $submittingParty->addAttribute("_State", $data->submitter->state);
            $submittingParty->addAttribute("_PostalCode", $data->submitter->zip);

            $request = $requestGroup->addChild("REQUEST");
            $request->addAttribute("LoginAccountIdentifier",$this->loginId);
            $request->addAttribute("LoginAccountPassword",$this->password);
            $request->addAttribute("InternalAccountIdentifier","");

            $requestData = $request->addChild("REQUEST_DATA");

            $creditRequest = $requestData->addChild("CREDIT_REQUEST");
            $creditRequest->addAttribute("MISMOVersionID","2.3.1");
            $creditRequest->addAttribute("LenderCaseIdentifier",$data->loanNumber);
            $creditRequest->addAttribute("RequestingPartyRequestedByName",$data->loanOfficer->name);

            $creditRequestData = $creditRequest->addChild("CREDIT_REQUEST_DATA");
            $creditRequestData->addAttribute("BorrowerID",isset($co_applicant)?$applicant->id.' '.$co_applicant->id:$applicant->id);
            $creditRequestData->addAttribute("CreditReportRequestActionType","Submit");
            $creditRequestData->addAttribute("CreditReportType","Merge");
            $creditRequestData->addAttribute("CreditRequestType",isset($co_applicant)?'Joint':'Individual');

            $creditRepositoryIncluded = $creditRequestData->addChild("CREDIT_REPOSITORY_INCLUDED");
            $creditRepositoryIncluded->addAttribute("_EquifaxIndicator","Y");
            $creditRepositoryIncluded->addAttribute("_ExperianIndicator","Y");
            $creditRepositoryIncluded->addAttribute("_TransUnionIndicator","Y");

            $loanApplication = $creditRequest->addChild("LOAN_APPLICATION");

            $borrower = $loanApplication->addChild("BORROWER");
            $borrower->addAttribute("BorrowerID",$applicant->id);
            $borrower->addAttribute("_FirstName",$applicant->firstName);
            $borrower->addAttribute("_MiddleName",$applicant->middleInitial);
            $borrower->addAttribute("_LastName",$applicant->lastName);
            $borrower->addAttribute("_NameSuffix",$applicant->suffix);
            $borrower->addAttribute("_SSN",$applicant->ssn);
            $borrower->addAttribute("_HomeTelephoneNumber",$applicant->homePhone);
            $borrower->addAttribute("_BirthDate",$applicant->dob);

            foreach ($applicant->residences as $key => $house) {
                $residence = $borrower->addChild("_RESIDENCE");
                $residence->addAttribute("_StreetAddress",$house->streetAddress);
                $residence->addAttribute("_City",$house->city);
                $residence->addAttribute("_State",$house->state);
                $residence->addAttribute("_PostalCode",$house->zip);
                $residence->addAttribute("_BorrowerResidencyType",$key===0?"Current":"Previous");
            }

            if (isset($co_applicant)) {
                $co_borrower = $loanApplication->addChild("BORROWER");
                $co_borrower->addAttribute("BorrowerID",$co_applicant->id);
                $co_borrower->addAttribute("_FirstName",$co_applicant->firstName);
                $co_borrower->addAttribute("_MiddleName",$co_applicant->middleInitial);
                $co_borrower->addAttribute("_LastName",$co_applicant->lastName);
                $co_borrower->addAttribute("_NameSuffix",$co_applicant->suffix);
                $co_borrower->addAttribute("_SSN",$co_applicant->ssn);
                $co_borrower->addAttribute("_HomeTelephoneNumber",$co_applicant->homePhone);
                $co_borrower->addAttribute("_BirthDate",$co_applicant->dob);

                foreach ($co_applicant->residences as $key => $house) {
                    $co_residence = $co_borrower->addChild("_RESIDENCE");
                    $co_residence->addAttribute("_StreetAddress",$house->streetAddress);
                    $co_residence->addAttribute("_City",$house->city);
                    $co_residence->addAttribute("_State",$house->state);
                    $co_residence->addAttribute("_PostalCode",$house->zip);
                    $co_residence->addAttribute("_BorrowerResidencyType",$key===0?"Current":"Previous");
                }
            }


        } catch (\Exception $e) {

            throw $e;

        }

        if ($this->debug) {
            die($requestGroup->asXML());
        }

        return $requestGroup->asXML();
    }

}
