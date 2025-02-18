<?php

namespace App\Controllers;

use App\Models\PatientModel;

class PatientAuth extends BaseController
{
    protected $patientModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
    }

    public function login()
    {
        return view('patient_auth/login');
    }

    public function attemptLogin()  {
        
        $email = $this->request->getPost('email');
        $cpf = $this->request->getPost('cpf');

        $patient = $this->patientModel->where('email', $email)->first();

        if ($patient && $this->validateCPF($cpf, $patient['cpf'])) {
            $this->setPatientSession($patient);
            return redirect()->to('/my')->with('success', 'Login realizado');
        } else {
            return redirect()->back()->withInput()->with('error', 'E-mail ou CPF inválidos');
        }
    }

    public function logout()
    {
        session()->remove(['patient_id', 'patient_name', 'patient_email']);
        return redirect()->to('/portal')->with('success', 'Você deslogou');
    }

    private function setPatientSession($patient)
    {
        $data = [
            'patient_id' => $patient['id'],
            'patient_name' => $patient['name'],
            'patient_email' => $patient['email'],
        ];

        session()->set($data);
    }

    private function validateCPF($inputCPF, $storedCPF)
    {
        // Remove any non-digit characters
        $inputCPF = preg_replace('/[^0-9]/', '', $inputCPF);
        $storedCPF = preg_replace('/[^0-9]/', '', $storedCPF);

        return $inputCPF === $storedCPF;
    }
}
