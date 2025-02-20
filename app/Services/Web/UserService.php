<?php

namespace App\Services\Web;

use App\Mail\OtpMail;
use App\Mail\RegisterMail;
use App\Models\User;
use App\Models\User as ObjdModel;

use App\Services\BaseService;
use App\Services\FirestoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserService extends BaseService
{

    public function __construct(
        protected ObjdModel        $objModel,
        protected Role             $role,
        protected FirestoreService $firestoreService
    )
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        $users = $this->model->all();
        $roles = $this->role->all()->slice(1);
        return view('web.user.index', ['users', $users, 'roles' => $roles]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'national_id' => 'required|regex:/^\d{10}$/|unique:users,national_id',
            'role' => 'required|string|exists:roles,name',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.string' => 'يجب أن يكون الاسم الكامل نصًا',
            'full_name.max' => 'يجب ألا يتجاوز الاسم الكامل 255 حرفًا',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 15 حرفًا',
            'national_id.required' => 'رقم الهوية الوطنية مطلوب',
            'national_id.regex' => 'يجب أن يكون رقم الهوية الوطنية مكونًا من 10 أرقام',
            'national_id.unique' => 'رقم الهوية الوطنية مستخدم بالفعل',
            'role.required' => 'الدور مطلوب',
            'role.string' => 'يجب أن يكون الدور نصًا',
            'role.exists' => 'الدور المحدد غير موجود',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى مراجعة البيانات وإعادة المحاولة',
                'errors' => $validator->errors()
            ], 422);
        }

        $request['password'] = Hash::make('national_id');
        $user = User::create($request->all());
        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'تم إنشاء المستخدم بنجاح'], 200);
    }

    public function edit($id)
    {

        $user = $this->model->with('roles')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'national_id' => $user->national_id,
            'phone' => str_replace('+966', '', $user->phone),
            'email' => $user->email,
            'role' => $user->roles->pluck('name')->first(),
        ]);

    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'national_id' => 'required|regex:/^\d{10}$/|unique:users,national_id,' . $id,
            'role' => 'nullable|string|exists:roles,name',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.string' => 'يجب أن يكون الاسم الكامل نصًا',
            'full_name.max' => 'يجب ألا يتجاوز الاسم الكامل 255 حرفًا',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 15 حرفًا',
            'national_id.required' => 'رقم الهوية الوطنية مطلوب',
            'national_id.regex' => 'يجب أن يكون رقم الهوية الوطنية مكونًا من 10 أرقام',
            'national_id.unique' => 'رقم الهوية الوطنية مستخدم بالفعل',
            'role.required' => 'الدور مطلوب',
            'role.string' => 'يجب أن يكون الدور نصًا',
            'role.exists' => 'الدور المحدد غير موجود',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى مراجعة البيانات وإعادة المحاولة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->update($request->only(['full_name', 'phone', 'national_id']));
//        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'تم تعديل المستخدم بنجاح'], 200);
    }


    public function editStatus(Request $request, $id)
    {
        try {
            $user = $this->model->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'تغذر تعديل هذا المستخدم',
                ], 500);
            }

            $user->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحالة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ ما',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
