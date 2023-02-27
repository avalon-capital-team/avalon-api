<?php

namespace App\Http\Resources\User;

use App\Helpers\FileUploadHelper;
use App\Http\Resources\ExternalApis\KycaidApiResource;
use App\Models\User;
use App\Models\User\UserCompliance;
use App\Notifications\User\Document\ComplianceApprovedNotification;
use App\Notifications\User\Document\ComplianceDeclineNotification;

class UserComplianceResource
{
    /**
     * Find User Document by userId
     *
     * @param  int $id
     * @return \App\Models\User\UserCompliance
     */
    public function findByUserId(int $id)
    {
        return UserCompliance::where('user_id', $id)->first();
    }

    /**
     * Store or update documentation
     *
     * @param  \App\Models\User $user
     * @param  void $doc_front
     * @param  void $doc_back
     * @param  void $proof_address
     * @return bool
     * @throws \Exception
     */
    public function storeOrUpdate(User $user, $doc_front, $doc_back, $proof_address)
    {
        $document = $this->findByUserId($user->id);
        if ($document) {
            if ($document->status_id == 1) {
                throw new \Exception('Você já enviou os documentos, estão aguardando a validação.');
            }

            if ($document->status_id == 2) {
                throw new \Exception('Você já enviou os documento e estão ok!');
            }

            $document->status_id = 1;
            $document->message = '';
        } else {
            $document = new UserCompliance();
            $document->user_id = $user->id;
            $document->status_id = 1;
        }

        $data = [
            'front_doc' => (new FileUploadHelper())->storeFile($doc_front, 'users/documents'),
            'back_doc' => (new FileUploadHelper())->storeFile($doc_back, 'users/documents'),
            'proof_address' => (new FileUploadHelper())->storeFile($proof_address, 'users/documents'),
        ];

        $document->documents = $data;

        if ($document->save()) {
            return true;
        }

        throw new \Exception('Não foi possível enviar seus documentos!');
    }

    /**
     * Get compliance form
     *
     * @param  \App\Models\User $user
     * @return \App\Models\User\UserCompliance
     * @throws \Exception
     */
    public function getComplianceForm(User $user)
    {
        if ($user->settings->compliance) {
            throw new \Exception('Os documentos já foram validados.');
        }

        if ($user->compliance->where('status_id', 1)->first()) {
            throw new \Exception('Já existe uma validação em andamento.');
        }

        $type = ($user->document_type == 'CPF') ? 'Person' : 'Company';
        $type = "startCompliance{$type}";

        return $this->{$type}($user);
    }

    /**
     * Validate user data for Person
     *
     * @param  User $user
     * @return void
     * @throws \Exception
     */
    public function validateUserDataForPerson(User $user)
    {
        if (!$user->data->birth_date) {
            throw new \Exception('Para continuar você precisa informar sua data de aniversário em Meus Dados.');
        }

        $userName = $user->splitName();

        if (empty($userName['firstname'])) {
            throw new \Exception('Para continuar você precisa informar seu primeiro nome em Meus Dados.');
        }

        if (empty($userName['lastname'])) {
            throw new \Exception('Para continuar você precisa informar seu sobrenome em Meus Dados.');
        }
    }

    /**
     * Start compliance Person
     *
     * @param  \App\Models\User $user
     * @param  array $files
     * @return \App\Models\User\UserCompliance
     * @throws \Exception
     */
    public function startCompliancePerson(User $user, array $files)
    {
        $this->validateUserDataForPerson($user);

        $data = [
            'first_name' => $user->splitName()['firstname'],
            'last_name' => $user->splitName()['lastname'],
            'birth_date' => $user->data->birth_date->format('Y-m-d'),
            'country_code' => 'BR',
            'email' => $user->email,
            'user_id' => $user->id,
        ];

        foreach ($files as $file) {
            $data['files'][] = [
                'name' => $file['name'],
                'url' => (new FileUploadHelper())->storeFile($file['file'], 'users/documents'),
                'url_back' => ($file['file_back']) ? (new FileUploadHelper())->storeFile($file['file_back'], 'users/documents') : null,
            ];
        }

        // $kyc = (new KycaidApiResource())->startCompliancePerson($data);

        // if ($kyc) {
        $userCompliance = new UserCompliance();
        $userCompliance->user_id = $user->id;
        $userCompliance->status_id = 1;
        $userCompliance->type = 'manual';
        $userCompliance->documents = json_encode($data['files']);
        // $userCompliance->applicant_id = $kyc['applicant']['applicant_id'];
        // $userCompliance->verification_id = $kyc['verification']['verification_id'];
        $userCompliance->save();

        return $userCompliance;
        // }
    }

    /**
     * Start compliance Company
     *
     * @param  \App\Models\User $user
     * @param  array $files
     * @return \App\Models\User\UserCompliance
     * @throws \Exception
     */
    public function startComplianceCompany(User $user, array $files)
    {
        if (!$user->data->business_activity_id) {
            throw new \Exception('Para continuar você precisa informar so ramo de atividade em Meus Dados.');
        }

        if (!$user->responsible) {
            throw new \Exception('Para continuar você precisa informar os dados do responsável em Meus Dados.');
        }

        if (!$user->address) {
            throw new \Exception('Para continuar você precisa informar o endereço em Meus Endereço.');
        }

        $data = [
            'business_activity_id' => $user->data->businessActivity->code,
            'company_name' => $user->name,
            'registration_country' => 'BR',
            'email' => $user->email,
            'user_id' => $user->id,
            'full_address' => $user->address->full(),
            'phone' => '+' . preg_replace('/[^0-9]+/', '', $user->phone)
        ];

        if (!$user->responsible->splitName()['lastname']) {
            throw new \Exception('O nome do representante esta incompleto. Informe o sobrenome.');
        }

        $data['responsible'] = [
            'type' => 'AUTHORISED',
            'first_name' => $user->responsible->splitName()['firstname'],
            'last_name' => $user->responsible->splitName()['lastname'],
            'birth_date' => $user->data->birth_date->format('Y-m-d'),
            'title' => 'CEO',
            'residence_country' => 'BR',
            'email' => $user->email,
            'share' => $user->responsible->share
        ];

        foreach ($files as $file) {
            $data['files'][] = [
                'name' => $file['name'],
                'url' => (new FileUploadHelper())->storeFile($file['file'], 'users/documents'),
            ];
        }

        // $kyc = (new KycaidApiResource())->startComplianceCompany($data);

        // if ($kyc) {
        $userCompliance = new UserCompliance();
        $userCompliance->user_id = $user->id;
        $userCompliance->status_id = 1;
        $userCompliance->type = 'manual';
        $userCompliance->documents = json_encode($data['files']);
        // $userCompliance->applicant_id = $kyc['applicant']['applicant_id'];
        // $userCompliance->verification_id = $kyc['verification']['verification_id'];
        $userCompliance->save();

        return $userCompliance;
        // }
    }

    /**
     * Notify Approve documents
     *
     * @param  \App\Models\User\UserCompliance $userCompliance
     */
    public function notifyApproveDocuments(UserCompliance $userCompliance)
    {
        if (config('app.env') != 'testing') {
            $userCompliance->user->notify(new ComplianceApprovedNotification($userCompliance));
        }
    }

    /**
     * Notify Decline documents
     *
     * @param  \App\Models\User\UserCompliance $userCompliance
     */
    public function notifyDeclineDocuments(UserCompliance $userCompliance)
    {
        if (config('app.env') != 'testing') {
            $userCompliance->user->notify(new ComplianceDeclineNotification($userCompliance));
        }
    }
}