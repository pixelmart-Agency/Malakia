<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirestoreService;

class FcmController
{
    protected $firestoreService;

    public function __construct(FirestoreService $firestoreService)
    {
        $this->firestoreService = $firestoreService;
    }

    public function addUsersToFirestore()
    {
        // جلب جميع المستخدمين من قاعدة البيانات
        $users = User::all();

        // تحويل البيانات إلى تنسيق مناسب لـ Firestore
        $usersData = $users->map(function ($user) {
            return [
                'id' => $user->id, // الاحتفاظ بالمعرف إذا كنت بحاجة له
                'name' => $user->full_name,
                'email' => $user->email,
                'national_id' => $user->national_id,
                'created_at' => $user->created_at->toDateTimeString(),
                'updated_at' => $user->updated_at->toDateTimeString(),
                'lat' => '21.3891',
                'long' => '39.8579',
            ];
        })->toArray();

        // حفظ جميع المستخدمين في Firestore
        $result = [];
        foreach ($usersData as $user) {
            $this->firestoreService->addUsers($user);
            $result[] = $user;
        }

        // إرسال جميع المستخدمين إلى Firestore

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'All users successfully stored in Firestore',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store users in Firestore',
            ]);
        }
    }


    public function addMessage(Request $request)
    {
        // حفظ الرسالة في Firestore عبر REST API
        return $this->firestoreService->addMessage($request);
    }
}
