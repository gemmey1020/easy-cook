<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class AssessmentNote extends Model
    {
        use HasFactory;

        protected $table = 'assessment_notes';

        protected $fillable = [
            'assessment_id',
            'note',
        ];

        public function assessment()
        {
            return $this->belongsTo(\App\Models\Assessment::class);
        }
    }
