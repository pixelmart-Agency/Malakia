<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirestoreService extends BaseService
{
    protected Client $client;
    protected mixed $projectId;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/databases/(default)/documents/',
        ]);

        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    public function addUser($userData)
    {
        $documentName = "users/" . $userData['national_id']; // Unique ID

        try {
            $response = $this->client->patch($documentName, [ // Use PATCH for updates
                'json' => [
                    'fields' => [
                        'name' => ['stringValue' => $userData['name']],
                        'email' => ['stringValue' => $userData['email']],
                        'national_id' => ['stringValue' => $userData['national_id']],
                        'created_at' => ['timestampValue' => now()->toIso8601String()],
                        'lat' => ['doubleValue' => (float)$userData['lat']],
                        'long' => ['doubleValue' => (float)$userData['long']],
                        'updated_at' => ['timestampValue' => now()->toIso8601String()]
                    ]
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Firestore Error: ' . $e->getMessage());
            return null;
        }
    }

    public function addUsers($userData)
    {
        $documentName = "users/" . $userData['national_id']; // Unique ID

        try {
            $response = $this->client->patch($documentName, [ // Use PATCH for updates
                'json' => [
                    'fields' => [
                        'name' => ['stringValue' => $userData['name']],
                        'email' => ['stringValue' => $userData['email']],
                        'national_id' => ['stringValue' => $userData['national_id']],
                        'created_at' => ['timestampValue' => now()->toIso8601String()],
                        'lat' => ['doubleValue' => (float)$userData['lat']],
                        'long' => ['doubleValue' => (float)$userData['long']],
                        'updated_at' => ['timestampValue' => now()->toIso8601String()]
                    ]
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Firestore Error: ' . $e->getMessage());
            return null;
        }
    }

    public function addMessage($request)
    {

        $fileUrl = '';
        if ($request->has('file')){
            $file = $this->handleFile($request->file('file'),'messages');
            $fileUrl = getFile($file);
        }

        $documentName = "messages/"; // تحديد الـ document ID بناءً على معرف المستخدم

        try {
            $response = $this->client->post($documentName, [
                'json' => [
                    'fields' => [
                        'senderId' => ['stringValue' => $request->sender_id],
                        'receiverId' => ['stringValue' => $request->receiver_id],
                        'bodyMessage' => ['stringValue' => $request->message],
                        'chatId' => ['stringValue' => $request->chat_id],
                        'fileUrl' => ['stringValue' => $fileUrl],
                        'seen' => ['booleanValue' => false],
                        'time' => ['timestampValue' => now()->toIso8601String()],
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
            }

            return $this->responseMsg('تم ارسال الرسالة بنجاح للفايربيز', $result);
        } catch (\Exception $e) {
            return $this->responseMsg('هناك مشكلة ما لا يمكن تحديدها في الوقت الحالي');
        }
    }
}
