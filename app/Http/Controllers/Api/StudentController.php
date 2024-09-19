<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Events\UserRegisteredEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\StudentResource;
use App\Http\Requests\Student\SearchStudentRequest;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('viewAny', Teacher::class);
        $students = Student::paginate(10);
        return StudentResource::collection($students);
    }

    public function store(StoreStudentRequest $request)
    {

        DB::beginTransaction();

        try {
            // Create the user first
            $userData = $request->only(['name', 'email', 'password', 'role']);
            $userData['role'] = 'student';
            $user = User::create($userData);

            // Create the student
            $studentData = $request->only(['name', 'photo']);
            $student = Student::create($studentData);

            // Associate the user with the student
            $student->user()->save($user);

            // Attach the student to selected schools
            $student->schools()->attach($request->school_ids);

            // Dispatch UserRegisteredEvent
            event(new UserRegisteredEvent($user, $userData['password']));

            DB::commit();

            return StudentResource::make($student);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Error creating student', 'errors' => $e->getMessage()], 500);
        }
    }

    public function search(SearchStudentRequest $request)
    {
        $term = $request->query('search');
        $students = Student::search($term)->paginate(10)->appends(['search' => $term]);

        return StudentResource::collection($students);
    }
}
