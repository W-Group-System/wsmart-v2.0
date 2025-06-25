<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('department.department');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDepartment(Request $request)
    {
        $query = Department::query();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $users = $query->offset($request->start)
                    ->limit($request->length)
                    ->get();
        
        $data = $users->map(function($item) {
            $buttons = "";
            if ($item->status == null)
            {
                $buttons = '<button type="button" class="btn btn-sm btn-danger" id="deactivateBtn" title="Deactivate department">
                                <i class="fa fa-ban"></i>
                            </button>';
            }
            else
            {
                $buttons = '<button type="button" class="btn btn-sm btn-success" id="activateBtn" title="Activate department">
                                <i class="fa fa-check"></i>
                            </button>';
            }

            return [
                'action' => '
                    <button type="button" class="btn btn-sm btn-warning" id="editBtn">
                        <i class="fa fa-pencil-square-o"></i>
                    </button>
                    '.$buttons.'
                ',
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
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
        //
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
    public function update(Request $request, $id)
    {
        //
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
