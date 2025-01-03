<?php

namespace App\Models;

use CodeIgniter\Model;

class TreatmentFileModel extends Model
{
    protected $table = 'treatment_files';
    protected $primaryKey = 'id';
    protected $allowedFields = ['treatment_id', 'file_name', 'original_name'];
    protected $useTimestamps = true;
}
