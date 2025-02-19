<?php

namespace App\Models;

use CodeIgniter\Model;

class TreatmentModel extends Model
{
    protected $table = 'treatments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['appointment_id', 'value', 'payment_method', 'notes'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'appointment_id' => 'required|integer',
        'value' => 'required|numeric',
        'payment_method' => 'required|in_list[Dinheiro,Credito,Debito,Convenio]',
    ];

    public function getTreatmentsWithPatientInfo()
    {
        return $this->select('treatments.*, appointments.start_time as appointment_date, patients.name as patient_name')
                    ->join('appointments', 'appointments.id = treatments.appointment_id')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->paginate(10);
    }

    public function getTreatmentWithPatientInfo($id)
    {
        return $this->select('treatments.*, appointments.start_time as appointment_date, patients.name as patient_name')
                    ->join('appointments', 'appointments.id = treatments.appointment_id')
                    ->join('patients', 'patients.id = appointments.patient_id')
                    ->where('treatments.id', $id)
                    ->first();
    }
}
