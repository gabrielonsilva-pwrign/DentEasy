<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\TreatmentModel;
use App\Models\AppointmentModel;

class Patients extends BaseController
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
        $keyword = $this->request->getGet('search') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $orderBy = $this->request->getGet('order_by') ?? 'id';
        $orderDir = $this->request->getGet('order_dir') ?? 'asc';

        $data['patients'] = $this->patientModel->search($keyword, $perPage, $page, $orderBy, $orderDir);
        $data['pager'] = $this->patientModel->pager;

        return view('patients/index', $data);
    }

    public function new()
    {
        return view('patients/create');
    }

    public function create()
    {
        $data = $this->request->getPost();

        if ($this->patientModel->insert($data)) {
            return redirect()->to('/patients')->with('success', 'Paciente adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function edit($id = null)
    {
        $data['patient'] = $this->patientModel->find($id);
        return view('patients/edit', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if ($this->patientModel->update($id, $data)) {
            return redirect()->to('/patients')->with('success', 'Paciente atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->patientModel->delete($id)) {
            return redirect()->to('/patients')->with('success', 'Patient excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir paciente');
        }
    }

    public function view($id = null)
    {
        $patient = $this->patientModel->find($id);
        $data['patient'] = $patient;
        $data['age'] = $this->patientModel->getAge($id);
        $data['patient']['cpf'] = $this->formatCPF($patient['cpf']);
        $data['patient']['mobile_phone'] = $this->formatMobilePhone($patient['mobile_phone']);
        return view('patients/view', $data);
    }

    public function treatmentHistory($id = null)
    {
        $data['patient'] = $this->patientModel->find($id);
        #$data['treatments'] = $this->treatmentModel->where('treatments.treatment_id', $id)->join('appointments','appointments.id = treatments.appointment_id', 'left')
        #    ->orderBy('treatments.created_at', 'DESC')
        #    ->findAll();
        $data['treatments'] = $this->appointmentModel->where('appointments.patient_id', $id)
            ->join('treatments','treatments.appointment_id = appointments.id','right')
            ->orderBy('treatments.created_at','DESC')
            ->findAll();
        
        if(count($data['treatments']) > 0)  {
            return view('patients/treatment_history', $data);
        } else  {
            return view('patients/treatment_history_none',$data);
        }
    }

    private function formatCPF($cpf)
    {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    private function formatMobilePhone($phone)
    {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 1) . ' ' . substr($phone, 3, 4) . '-' . substr($phone, 7, 4);
    }
}
