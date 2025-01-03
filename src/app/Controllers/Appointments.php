<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Controllers\Api;

class Appointments extends BaseController
{
    protected $appointmentModel;
    protected $patientModel;
    protected $apiController;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->patientModel = new PatientModel();
        $this->apiController = new Api();
    }

    public function index()
    {
        $viewMode = $this->request->getGet('view') ?? 'calendar';
        $data['viewMode'] = $viewMode;

        if ($viewMode === 'list') {
            $data['appointments'] = $this->appointmentModel->getAppointmentsWithPatients();
        }

        return view('appointments/index', $data);
    }

    public function getAppointments()
    {
        $appointments = $this->appointmentModel->getAppointmentsForCalendar();
        return $this->response->setJSON($appointments);
    }

    public function new()
    {
        $data['patients'] = $this->patientModel->findAll();
        return view('appointments/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();

        if ($appointmentId = $this->appointmentModel->insert($data)) {
            // Fetch the created appointment with patient details
            $appointment = $this->appointmentModel->getAppointmentWithPatient($appointmentId);

            // Prepare webhook data
            $webhookData = [
                'appointment_title' => $appointment['title'],
                'patient_name' => $appointment['patient_name'],
                'appointment_datetime' => $appointment['start_time'],
                'mobile_phone' => $appointment['mobile_phone']
            ];

            // Trigger webhook
            $this->apiController->sendWebhook($webhookData);

            return redirect()->to('/appointments')->with('success', 'Agendamento Adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->appointmentModel->errors());
        }
    }

    public function edit($id = null)
    {
        $data['appointment'] = $this->appointmentModel->find($id);
        $data['patients'] = $this->patientModel->findAll();
        return view('appointments/edit', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if ($this->appointmentModel->update($id, $data)) {
            // Fetch the created appointment with patient details
            $appointment = $this->appointmentModel->getAppointmentWithPatient($id);

            // Prepare webhook data
            $webhookData = [
                'appointment_title' => $appointment['title'],
                'patient_name' => $appointment['patient_name'],
                'appointment_datetime' => $appointment['start_time'],
                'mobile_phone' => $appointment['mobile_phone']
            ];

            // Trigger webhook
            $this->apiController->sendWebhook($webhookData);

            return redirect()->to('/appointments')->with('success', 'Agendamento atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->appointmentModel->errors());
        }
    }

    public function updateAjax($id = null)
    {
        $data = $this->request->getPost();

        if ($this->appointmentModel->update($id, $data)) {
            // Fetch the created appointment with patient details
            $appointment = $this->appointmentModel->getAppointmentWithPatient($id);

            // Prepare webhook data
            $webhookData = [
                'appointment_title' => $appointment['title'],
                'patient_name' => $appointment['patient_name'],
                'appointment_datetime' => $appointment['start_time'],
                'mobile_phone' => $appointment['mobile_phone']
            ];

            // Trigger webhook
            $this->apiController->sendWebhook($webhookData);


            return json_encode(true);
        } else {
            return json_encode(redirect()->back()->withInput()->with('errors', $this->appointmentModel->errors()));
        }
    }

    public function delete($id = null)
    {
        if ($this->appointmentModel->delete($id)) {
            return redirect()->to('/appointments')->with('success', 'Agendamento excluído');
        } else {
            return redirect()->back()->with('error', 'Failed ao excluir agendamento');
        }
    }
}
