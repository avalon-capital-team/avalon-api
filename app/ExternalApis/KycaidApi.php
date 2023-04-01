<?php

namespace App\ExternalApis;

use Illuminate\Support\Facades\Http;

class KycaidApi
{
    /**
     * @var string
     */
    private $api_url;

    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string
     */
    private $form_id_person;

    /**
     * @var string
     */
    private $form_id_company;

    /**
     * Create a new ExternalApis instance.
     *
     * @return void
     */
    public function __construct()
    {
        $kycaidConfig = config('external_apis.kycaid_api');
        $this->api_url = $kycaidConfig['endpoint'];
        $this->api_key = $kycaidConfig['key'];
        $this->form_id_person = $kycaidConfig['form_id_person'];
        $this->form_id_company = $kycaidConfig['form_id_company'];
    }

    /**
     * Create Applicant Person
     *
     * @param  string $type
     * @param  string $first_name
     * @param  string $last_name
     * @param  string $birthday
     * @param  string $residence_country
     * @param  string $email
     * @param  int $user_id
     * @return \Illuminate\Http\Client\Response
     */
    public function createApplicantPerson(string $type, string $first_name, string $last_name, string $birthday, string $residence_country, string $email, int $user_id)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/applicants", [
            'type' => $type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'dob' => $birthday,
            'residence_country' => $residence_country,
            'email' => $email,
            'external_applicant_id' => $user_id
        ]);

        return $response->json();
    }

    /**
     * Create Applicant Company
     *
     * @param  string $type
     * @param  string $business_activity_id
     * @param  string $company_name
     * @param  string $email
     * @param  string $phone
     * @param  string $registration_country
     * @return \Illuminate\Http\Client\Response
     */
    public function createApplicantCompany(string $type, string $business_activity_id, string $company_name, string $email, string $phone, string $registration_country)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/applicants", [
            'type' => $type,
            'business_activity_id' => $business_activity_id,
            'company_name' => $company_name,
            'email' => $email,
            'phone' => $phone,
            'registration_country' => $registration_country
        ]);

        return $response->json();
    }

    /**
     * Create file
     *
     * @param  string $file_url
     * @return \Illuminate\Http\Client\Response
     */
    public function createFile(string $file_url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url."/files",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file'=> new \CURLFILE($file_url)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token '.$this->api_key,
                'Content-Type: multipart/form-data'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        return $response;
    }

    /**
     * Create Applicant Document
     *
     * @param  string $applicant_id
     * @param  string $type
     * @param  string $front_side_id
     * @param  string|null $back_side_id
     * @return \Illuminate\Http\Client\Response
     */
    public function createApplicantDocument(string $applicant_id, string $type, string $front_side_id, string $back_side_id = null)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/documents", [
            'applicant_id' => $applicant_id,
            'type' => $type,
            'front_side_id' => $front_side_id,
            'back_side_id' => $back_side_id
        ]);

        return $response->json();
    }

    /**
     * Create Applicant Address
     *
     * @param  string $applicant_id
     * @param  string $type
     * @param  string $full_address
     * @return \Illuminate\Http\Client\Response
     */
    public function createApplicantAddress(string $applicant_id, string $type, string $full_address)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/addresses", [
            'applicant_id' => $applicant_id,
            'type' => $type,
            'full_address' => $full_address
        ]);

        return $response->json();
    }

    /**
     * Create Applicant Affiliated Person
     *
     * @param  string $applicant_id
     * @param  string $type
     * @param  string $first_name
     * @param  string $last_name
     * @param  string $title
     * @param  string $dob
     * @param  string $residence_country
     * @param  string $email
     * @param  float|null $share
     * @return \Illuminate\Http\Client\Response
     */
    public function createApplicantAffiliatedPerson(string $applicant_id, string $type, string $first_name, string $last_name, string $title, string $dob, string $residence_country, string $email, float $share = null)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/affiliated-persons", [
            'type' => $type,
            'applicant_id' => $applicant_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'title' => $title,
            'dob' => $dob,
            'residence_country' => $residence_country,
            'email' => $email,
            'share' => $share
        ]);

        return $response->json();
    }

    /**
     * Create Verification
     *
     * @param  string $applicant_id
     * @param  string $type
     * @return \Illuminate\Http\Client\Response
     */
    public function createVerification(string $applicant_id, string $type)
    {
        $form_id = "form_id_{$type}";
        $form_id = $this->{$form_id};

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/verifications", [
            'applicant_id' => $applicant_id,
            'form_id' => $form_id,
        ]);

        return $response->json();
    }

    /**
     * Get Verification
     *
     * @param  string $verification_id
     * @return \Illuminate\Http\Client\Response
     */
    public function getVerification(string $verification_id)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->get($this->api_url."/verifications/{$verification_id}");

        return $response->json();
    }

    /**
     * Get Business Activities
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function getBusinessActivities()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$this->api_key
        ])->get($this->api_url."/business-activities");

        return $response->json();
    }

    /**
     * Get Countries
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function getCountries()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$this->api_key
        ])->get($this->api_url."/countries");

        return $response->json();
    }

    /**
     * Create Form URL
     *
     * @param  string $type
     * @param  string $applicant_id
     * @param  string $external_applicant_id
     * @param  string $redirect_url
     * @return \Illuminate\Http\Client\Response
     */
    public function createFormUrl(string $type, string $applicant_id, string $external_applicant_id, string $redirect_url)
    {
        $form_id = "form_id_{$type}";
        $form_id = $this->{$form_id};

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token '.$this->api_key
        ])->post($this->api_url."/forms/{$form_id}/urls", [
            'applicant_id' => $applicant_id,
            'external_applicant_id' => $external_applicant_id,
            'redirect_url' => $redirect_url
        ]);

        return $response->json();
    }
}
