<?php

namespace App\Http\Controllers;

use App\Subsidiary;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('subsidiary.index');
    }

    public function getSubsidiary(Request $request)
    {
        $query = Subsidiary::query();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('subsidiary_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%");
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
                $buttons = '<button type="button" class="btn btn-sm btn-danger" id="deactivateBtn" title="Deactivate subsidiary">
                                <i class="fa fa-ban"></i>
                            </button>';
            }
            else
            {
                $buttons = '<button type="button" class="btn btn-sm btn-success" id="activateBtn" title="Activate subsidiary">
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
                'name' => $item->subsidiary_name,
                'address' => $item->address,
                'shipping_address' => $item->shipping_address,
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
        $subsidiary = new Subsidiary;
        $subsidiary->subsidiary_name = $request->name;
        $subsidiary->address = $request->address;
        $subsidiary->shipping_address = $request->shipping_address;
        $subsidiary->save();

        return response()->json([
            'msg' => 'Successfully Saved',
            'status' => 201
        ]);
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
        $subsidiary = Subsidiary::findOrFail($request->subsidiary_id);
        $subsidiary->subsidiary_name = $request->name;
        $subsidiary->address = $request->address;
        $subsidiary->shipping_address = $request->shipping_address;
        $subsidiary->save();

        return response()->json([
            'msg' => 'Successfully Updated',
            'status' => 201
        ]);
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

    public function deactivate(Request $request)
    {
        // dd($request->all());
        $user = Subsidiary::findOrFail($request->subsidiary_id);
        $user->status = "Deactivate";
        $user->save();

        return response()->json([
            'message' => 'Successfully Deactivated'
        ]);
    }
    public function activate(Request $request)
    {
        // dd($request->all());
        $user = Subsidiary::findOrFail($request->subsidiary_id);
        $user->status = null;
        $user->save();

        return response()->json([
            'message' => 'Successfully Activated'
        ]);
    }
}
