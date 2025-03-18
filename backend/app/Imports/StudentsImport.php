<?php

namespace App\Imports;

use App\Models\EnrolledStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    private $successCount = 0;
    private $duplicateCount = 0;
    private $students = [];

    public function model(array $row)
    {
        // Check if the student already exists (assuming 'name' is unique)
        $existingStudent = EnrolledStudent::where('id_number', $row['id_number'])
        ->first();

        if ($existingStudent) {
            $this->duplicateCount++;
            return null; // Skip duplicate
        }

        // Create a new student record
        $student = new EnrolledStudent([
            'name'       => $row['name'],
            'course'     => $row['course'],
            'id_number'=> $row['id_number'],
            'gender'=> $row['sex']??'',
        ]);

        $this->successCount++;
        $this->students[] = $student; // Store for response

        return $student;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getDuplicateCount()
    {
        return $this->duplicateCount;
    }

    public function getStudents()
    {
        return $this->students;
    }
}
