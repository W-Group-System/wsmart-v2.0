<?php

namespace App\Http\Controllers;

use App\Department;
use App\Role;
use App\Subsidiary;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::whereNull('status')->get();
        $roles = Role::whereNull('status')->get();
        $subsidiaries = Subsidiary::whereNull('status')->get();
        
        return view('user.user', compact('departments','roles','subsidiaries'));
    }

    public function getUsers(Request $request)
    {
        // dd($request->all());
        $query = User::with('subsidiary', 'department', 'role');

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subsidiary', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('role', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = $query->count();

        $users = $query->offset($request->start)
                    ->limit($request->length)
                    ->get();
        
        $data = $users->map(function($item) {
            return [
                'action' => '
                    <button type="button" class="btn btn-sm btn-warning" id="editUserBtn" data-department="'.$item->department_id.'" data-subsidiary="'.$item->subsidiary_id.'" data-role="'.$item->role_id.'">
                        <i class="fa fa-pencil-square-o"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger">
                        <i class="fa fa-ban"></i>
                    </button>
                ',
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'department' => optional($item->department)->name,
                'position' => $item->position,
                'role' => optional($item->role)->name,
                'subsidiary' => $item->subsidiary->subsidiary_name,
                'status' => $item->status == null ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>"
            ];
        });
        
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $users->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => 500
            ]);
        }
        else
        {
            $user = new User;
            $user->department_id = $request->department;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->position = $request->position;
            $user->role_id = $request->role;
            $user->status = $request->status;
            $user->password = bcrypt('abc123');
            $user->subsidiary_id = $request->subsidiary;
            $user->save();

            return response()->json([
                'status' => 201,
                'message' => 'Successfully Created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,'.$request->user_id
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => 500
            ]);
        }
        else
        {
            $user = User::findOrFail($request->user_id);
            $user->department_id = $request->department;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->position = $request->position;
            $user->role_id = $request->role;
            $user->status = $request->status;
            $user->password = bcrypt('abc123');
            $user->subsidiary_id = $request->subsidiary;
            $user->save();

            return response()->json([
                'status' => 201,
                'message' => 'Successfully Updated'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
