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
        $this->checkPermission('patients','view');
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
        $this->checkPermission('patients','add');
        return view('patients/create');
    }

    public function create()
    {
        $this->checkPermission('patients','add');
        $data = $this->request->getPost();

        if (isset($data['odontogram_data'])) {
            $data['odontogram_data'] = json_decode($data['odontogram_data'], true);
            $data['odontogram_data'] = json_encode($data['odontogram_data']);
        }

        if ($this->patientModel->insert($data)) {
            return redirect()->to('/patients')->with('success', 'Paciente adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function edit($id = null)
    {
        $this->checkPermission('patients','edit');
        $data['patient'] = $this->patientModel->find($id);
        return view('patients/edit', $data);
    }

    public function update($id = null)
    {
        $this->checkPermission('patients','edit');
        $data = $this->request->getPost();
        $data['id'] = $id;

        if (isset($data['odontogram_data'])) {
            $data['odontogram_data'] = json_decode($data['odontogram_data'], true);
            $data['odontogram_data'] = json_encode($data['odontogram_data']);
        }

        if ($this->patientModel->update($id, $data)) {
            return redirect()->to('/patients')->with('success', 'Paciente atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->patientModel->errors());
        }
    }

    public function delete($id = null)
    {
        $this->checkPermission('patients','delete');
        if ($this->patientModel->delete($id)) {
            return redirect()->to('/patients')->with('success', 'Patient excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir paciente');
        }
    }

    public function view($id = null)
    {
        $this->checkPermission('patients','view');
        $patient = $this->patientModel->find($id);
        $data['patient'] = $patient;
        $data['age'] = $this->patientModel->getAge($id);
        $data['patient']['cpf'] = $this->formatCPF($patient['cpf']);
        $data['patient']['mobile_phone'] = $this->formatMobilePhone($patient['mobile_phone']);
        return view('patients/view', $data);
    }

    public function treatmentHistory($id = null)
    {
        $this->checkPermission('patients','view');
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
