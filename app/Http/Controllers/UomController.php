<?php

namespace App\Http\Controllers;

use App\Uom;
use Illuminate\Http\Request;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('uom.index');
    }

    public function getUom(Request $request)
    {
        $query = Uom::query();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('measurement', 'like', "%{$search}%");
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
                $buttons = '<button type="button" class="btn btn-sm btn-danger" id="deactivateBtn" title="Deactivate">
                                <i class="fa fa-ban"></i>
                            </button>';
            }
            else
            {
                $buttons = '<button type="button" class="btn btn-sm btn-success" id="activateBtn" title="Activate">
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
                'measurement' => $item->measurement,
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
        $uom = new Uom;
        $uom->measurement = $request->uom;
        $uom->save();

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
        $uom = Uom::findOrFail($request->uom_id);
        $uom->measurement = $request->uom;
        $uom->save();

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
        $uom = Uom::findOrFail($request->uom_id);
        $uom->status = "Deactivate";
        $uom->save();

        return response()->json([
            'msg' => 'Successfully Deactivated'
        ]);
    }
    public function activate(Request $request)
    {
        // dd($request->all());
        $uom = Uom::findOrFail($request->uom_id);
        $uom->status = null;
        $uom->save();

        return response()->json([
            'msg' => 'Successfully Activated'
        ]);
    }
}
