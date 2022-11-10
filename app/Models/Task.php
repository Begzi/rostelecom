<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['string', 'repeat', 'frequency', 'algoritm_hash', 'sol'];
    
    public function processTask()
    {
        return $this->hasOne(ProcessExecutionTaskTable::class);
    }
}
