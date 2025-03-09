<?php

namespace App\Http\Controllers;

use App\Models\Nrc;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Frontend\StudentApiRequest;
use App\Http\Resources\Frontend\StudentApiResource;
use App\Http\Requests\Frontend\StudentApiUpdateRequest;

class StudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->student) {
            return [$user, new StudentApiResource($user->student)];
        }

        return [$user];

    }

    public function create()
    {
        return Nrc::all();
    }

    public function store(StudentApiRequest $request)
    {

        $validatedData = $request->validated();

        if ($request->hasFile('student_avatar')) {
            $avatarPath = $request->file('student_avatar')->store('images/student_avatars');
            $validatedData['student_avatar'] = $avatarPath;
        }

        if ($request->hasFile('approval_document')) {
            $approvalDocuments = [];
            foreach ($request->file('approval_document') as $file) {
                $path = $file->store('images/approval_documents');

                $approvalDocuments[] = $path;
            }
            $validatedData['approval_document'] = $approvalDocuments;
        }

        DB::transaction(function () use ($validatedData) {

            $student = Student::create($validatedData);

            if (isset($validatedData['nrcnos'])) {
                $dd = Nrc::find($validatedData['nrcnos']['nrcs_id'])->nrc_code;
                $type = $validatedData['nrcnos']['type'];
                $num = $validatedData['nrcnos']['nrc_num'];
                $ss = Nrc::find($validatedData['nrcnos']['nrcs_id'])->name_en;
                $zz = $dd.'/'.$ss.'('.$type.')'.$num;

                $student->update([
                    'student_nrc' => $zz,
                    'student_slug' => Str::slug($student->student_name),
                    'user_id' => auth()->id(),
                    'student_admission_date' => Carbon::now(),
                    'register_no' => $student->generateUniqueStudentId(),
                ]);
            }
            if (isset($validatedData['family_members'])) {
                foreach ($validatedData['family_members'] as $member) {
                    $familyMember = new FamilyMember($member);
                    $student->familyMembers()->save($familyMember);
                }
            }
        });

        return response()->json(['message' => 'Student registered successfully'], 201);
    }

    public function edit(Student $student)
    {
        return [Nrc::all(), 'student' => new StudentApiResource($student)];
    }

    public function update(StudentApiUpdateRequest $request)
    {
        $student = auth()->user()->student;

        if ($request->hasFile('student_avatar')) {
            $avatarPath = $request->file('student_avatar')->store('images/students_avatars');
            $request['student_avatar'] = $avatarPath;
        }

        DB::transaction(function () use ($request, $student) {
            $data = $request->all();

            $student->update($data);

            // auth()->user()->update(['email' => $request->student_mail]);

            // $student->user()->update(['email' => $request->email]);

            if (isset($data['student_avatar'])) {
                $student->update(['student_avatar' => $data['student_avatar']]);
            }
        });

        return response()->json(['message' => 'Student updated successfully'], 200);
    }

    // public function updatePhoto(
    //     Request $request)
    // {

    //     $student = Student::where('user_id', auth()->id())->firstOrFail();
    //     $photo = $request['student_avatar'];

    //     $photoPath = $photo->store('images/student_avatars', 'public');

    //     // Delete the old photo if it exists
    //     if ($student->student_avatar) {
    //         Storage::disk('public')->delete($student->student_avatar);
    //     }

    //     $student->student_avatar = $photoPath;
    //     $student->save();

    //     return response()->json(['message' => 'Profile photo updated successfully.', 'photo_url' => Storage::url($photoPath)], 200);
    // }

    public function updatePhoto(Request $request)
    {

        $user = User::where('id', auth()->id())->firstOrFail();
        $photo = $request['student_avatar'];

        $photoPath = $photo->store('images/user_avatars', 'public');

        // Delete the old photo if it exists
        if ($user->user_avatar) {
            Storage::disk('public')->delete($user->user_avatar);
        }

        $user->user_avatar = $photoPath;
        $user->save();

        return response()->json(['message' => 'Profile photo updated successfully.', 'photo_url' => Storage::url($photoPath)], 200);
    }
}