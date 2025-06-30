<?php

namespace App\Http\Controllers;

use App\Classification;
use App\Department;
use App\Inventory;
use App\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase-request.index');
    }
    
    public function getPurchaseRequest(Request $request)
    {
        $query = PurchaseRequest::with('user');

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subsidiary', function ($q) use ($search) {
                        $q->where('subsidiary_name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = $query->count();

        $purchase_request = $query->offset($request->start)
                    ->limit($request->length)
                    ->get();
        
        $data = $purchase_request->map(function($item) {
            return [
                'action' => '
                    <button type="button" class="btn btn-sm btn-info" id="viewBtn">
                        <i class="fa fa-eye"></i>
                    </button>
                ',
                'id' => $item->id,
                'request_date' => date('M d Y', strtotime($item->created_at)),
                'pr_number' => "PR-".str_pad($item->id,3,"0", STR_PAD_LEFT),
                'due_date' => date('M d Y', strtotime($item->due_date)),
                'requestor' => $item->user->name,
                'department' => $item->department->name,
                'item_description' => "",
                'subsidiary' => $item->subsidiary->subsidiary_name,
                'status' => $item->status
            ];
        });
        
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $purchase_request->count(),
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
        $classifications = Classification::whereNull('status')->get();
        $departments = Department::whereNull('status')->get();
        $inventories = Inventory::whereNull('status')->get();
        
        return view('purchase-request.create', compact('classifications','departments','inventories'));
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
