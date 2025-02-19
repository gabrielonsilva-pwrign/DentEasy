<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\TreatmentModel;
use App\Models\AppointmentModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PatientPortal extends BaseController
{
    protected $patientModel;
    protected $treatmentModel;
    protected $appointmentModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->treatmentModel = new TreatmentModel();
        $this->appointmentModel = new AppointmentModel();
    }

    public function index()
    {
        $patientId = session()->get('patient_id');
        $data['patient'] = $this->patientModel->find($patientId);
        $data['appointments'] = $this->appointmentModel->where('patient_id', $patientId)
                                                       ->where('start_time >=', date('Y-m-d H:i:s'))
                                                       ->where('status', 'Agendado')
                                                       ->orderBy('start_time', 'ASC')
                                                       ->findAll();

        if (!$data['patient']) {
            throw new PageNotFoundException('Patient not found');
        }

        return view('patient_portal/dashboard', $data);
    }

    public function personalData()
    {
        $patientId = session()->get('patient_id');
        $data['patient'] = $this->patientModel->find($patientId);

        if (!$data['patient']) {
            throw new PageNotFoundException('Patient not found');
        }

        return view('patient_portal/personal_data', $data);
    }

    public function treatmentHistory()
    {
        $patientId = session()->get('patient_id');
        
        if (!$this->patientModel->find($patientId)) {
            throw new PageNotFoundException('Patient not found');
        }

        $data['treatments'] = $this->treatmentModel->join('appointments', 'appointments.id = treatments.appointment_id')
                                                   ->join('patients', 'patients.id = appointments.patient_id')
                                                   ->where('patients.id', $patientId)
                                                   ->orderBy('appointments.start_time', 'DESC')
                                                   ->findAll();


        

        return view('patient_portal/treatment_history', $data);
    }

    public function appointments()
    {
        $patientId = session()->get('patient_id');
        
        if (!$this->patientModel->find($patientId)) {
            throw new PageNotFoundException('Patient not found');
        }

        $data['appointments'] = $this->appointmentModel->where('patient_id', $patientId)
                                                       ->where('start_time >=', date('Y-m-d H:i:s'))
                                                       ->where('status', 'Agendado')
                                                       ->orderBy('start_time', 'ASC')
                                                       ->findAll();

        return view('patient_portal/appointments', $data);
    }
}
