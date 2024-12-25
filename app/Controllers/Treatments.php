<?php

namespace App\Controllers;

use App\Models\TreatmentModel;
use App\Models\TreatmentItemModel;
use App\Models\AppointmentModel;
use App\Models\InventoryModel;
use App\Models\TreatmentFileModel;

#[\AllowDynamicProperties]

class Treatments extends BaseController
{
    protected $treatmentModel;
    protected $treatmentItemModel;
    protected $appointmentModel;
    protected $inventoryModel;
    protected $treatmentFileModel;

    public function __construct()
    {
        $this->treatmentModel = new TreatmentModel();
        $this->treatmentItemModel = new TreatmentItemModel();
        $this->appointmentModel = new AppointmentModel();
        $this->inventoryModel = new InventoryModel();
        $this->treatmentFileModel = new TreatmentFileModel();
        $this->databaseConnection = \Config\Database::connect();
    }

    public function index()
    {
        $data['treatments'] = $this->treatmentModel->getTreatmentsWithPatientInfo();
        $data['pager'] = $this->treatmentModel->pager;
        return view('treatments/index', $data);
    }

    public function new()
    {
        $data['appointments'] = $this->appointmentModel->getUpcomingAppointments();
        $data['inventory_items'] = $this->inventoryModel->findAll();
        return view('treatments/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        $this->databaseConnection->transStart();
        
        if ($treatmentId = $this->treatmentModel->insert($data)) {
            $inventoryItems = $this->request->getPost('inventory');
            foreach ($inventoryItems as $itemId => $quantity) {
                if ($quantity > 0) {
                    $this->treatmentItemModel->insert([
                        'treatment_id' => $treatmentId,
                        'inventory_id' => $itemId,
                        'quantity' => $quantity
                    ]);
                    
                    // Update inventory
                    $inventoryItem = $this->inventoryModel->find($itemId);
                    $this->inventoryModel->update($itemId, [
                        'quantity' => $inventoryItem['quantity'] - $quantity
                    ]);
                }
            }
            
            // Handle file uploads
            $files = $this->request->getFiles();
            foreach ($files['treatment_files'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/treatments', $newName);
                    
                    $this->treatmentFileModel->insert([
                        'treatment_id' => $treatmentId,
                        'file_name' => $newName,
                        'original_name' => $file->getClientName()
                    ]);
                }
            }

            // Change Appointment Status
            $this->appointmentModel->update($data['appointment_id'], ['status' => 'Completo']);
            
            $this->databaseConnection->transComplete();
            
            if ($this->databaseConnection->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Falha ao adicionar tratamento. Tente novamente.');
            }
            
            return redirect()->to('/treatments')->with('success', 'Tratamento criado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->treatmentModel->errors());
        }
    }

    public function edit($id = null)
    {
        $data['treatment'] = $this->treatmentModel->find($id);
        $data['appointments'] = $this->appointmentModel->getAppointmentsWithPatientInfo();
        $data['inventory_items'] = $this->inventoryModel->findAll();
        $data['treatment_items'] = $this->treatmentItemModel->getTreatmentItems($id);
        $data['treatment_files'] = $this->treatmentFileModel->where('treatment_id', $id)->findAll();
        return view('treatments/edit', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        $this->databaseConnection->transStart();
        
        if ($this->treatmentModel->update($id, $data)) {
            // Update treatment items
            $this->treatmentItemModel->where('treatment_id', $id)->delete();
            $inventoryItems = $this->request->getPost('inventory');
            foreach ($inventoryItems as $itemId => $quantity) {
                if ($quantity > 0) {
                    $this->treatmentItemModel->insert([
                        'treatment_id' => $id,
                        'inventory_id' => $itemId,
                        'quantity' => $quantity
                    ]);
                    
                    // Update inventory
                    $inventoryItem = $this->inventoryModel->find($itemId);
                    $this->inventoryModel->update($itemId, [
                        'quantity' => $inventoryItem['quantity'] - $quantity
                    ]);
                }
            }
            
            // Handle file uploads
            $files = $this->request->getFiles();
            foreach ($files['treatment_files'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/treatments', $newName);
                    
                    $this->treatmentFileModel->insert([
                        'treatment_id' => $id,
                        'file_name' => $newName,
                        'original_name' => $file->getClientName()
                    ]);
                }
            }
            
            $this->databaseConnection->transComplete();
            
            if ($this->databaseConnection->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Falha ao atualizar tratamento. Tente novamente.');
            }
            
            return redirect()->to('/treatments')->with('success', 'Tratamento Atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->treatmentModel->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->treatmentModel->delete($id)) {
            return redirect()->to('/treatments')->with('success', 'Tratamento excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir tratamento');
        }
    }

    public function view($id = null)
    {
        $data['treatment'] = $this->treatmentModel->getTreatmentWithPatientInfo($id);
        $data['treatment_items'] = $this->treatmentItemModel->getTreatmentItems($id);
        $data['treatment_files'] = $this->treatmentFileModel->where('treatment_id', $id)->findAll();
        return view('treatments/view', $data);
    }

    public function deleteFile($id = null)
    {
        $file = $this->treatmentFileModel->find($id);
        if ($file) {
            $filePath = ROOTPATH . 'public/uploads/treatments/' . $file['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->treatmentFileModel->delete($id);
            return redirect()->back()->with('success', 'Arquivo excluído');
        }
        return redirect()->back()->with('error', 'Arquivo não encontrado');
    }
}
