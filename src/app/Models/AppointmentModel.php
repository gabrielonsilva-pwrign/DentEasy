<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['patient_id', 'title', 'start_time', 'end_time', 'description', 'status'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'patient_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'start_time' => 'required|valid_date',
        'end_time' => 'required|valid_date',
        'status' => 'required|in_list[Agendado,Completo,Cancelado]'
    ];

    public function getAppointmentsForCalendar()
    {
        return $this->select('appointments.id, appointments.title, appointments.start_time as start, appointments.end_time as end, patients.name as patient_name')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->findAll();
    }

    public function getAppointmentsWithPatients()
    {
        return $this->select('appointments.*, patients.name as patient_name')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->orderBy('start_time', 'ASC')
                    ->findAll();
    }

    public function getAppointmentWithPatient($id)
    {
        return $this->select('appointments.*, patients.name as patient_name, patients.mobile_phone as mobile_phone')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->find($id);
    }

    public function getUpcomingAppointments()
    {
        $currentDate = date('Y-m-d H:i:s');
        return $this->select('appointments.*, patients.name as patient_name')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->where('appointments.start_time >=', $currentDate)
                    ->where('appointments.status', 'Agendado')
                    ->orderBy('appointments.start_time', 'ASC')
                    ->findAll();
    }

    public function getAppointmentsWithPatientInfo()
    {
        return $this->select('appointments.*, patients.name as patient_name, patients.email, patients.mobile_phone')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->orderBy('appointments.start_time', 'ASC')
                    ->findAll();
    }
}
