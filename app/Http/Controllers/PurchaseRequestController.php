<?php

namespace App\Http\Controllers;

use App\Classification;
use App\Department;
use App\Inventory;
use App\PurchaseRequest;
use App\PurchaseRequestFile;
use App\PurchaseRequestItem;
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
        $query = PurchaseRequest::with('user','classification','department','subsidiary','purchaseRequestFile','purchaseItem.inventory');

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
        
        $total_cost = 0;        
        $data = $purchase_request->map(function($item)use($total_cost) {
            foreach($item->purchaseItem as $purchaseItem)
            {
                $total_cost += $purchaseItem->inventory->cost;
            }
            
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
                'status' => $item->status,
                'class' => $item->classification->name,
                'remarks' => nl2br(e($item->remarks)),
                'attachments' => $item->purchaseRequestFile,
                'total_cost' => $total_cost
            ];
        });
        
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $purchase_request->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function getPurchaseRequestItem(Request $request)
    {
        $query = PurchaseRequestItem::with('purchaseRequest')->where('purchase_request_id', $request->purchase_request_id);

        if ($search = $request->input('search.value')) {
            // $query->where(function ($q) use ($search) {
            //     $q->where('id', 'like', "%{$search}%")
            //         ->orWhereHas('user', function ($q) use ($search) {
            //             $q->where('name', 'like', "%{$search}%");
            //         })
            //         ->orWhereHas('department', function ($q) use ($search) {
            //             $q->where('name', 'like', "%{$search}%");
            //         })
            //         ->orWhereHas('subsidiary', function ($q) use ($search) {
            //             $q->where('subsidiary_name', 'like', "%{$search}%");
            //         });
            // });
        }
        
        $recordsFiltered = $query->count();

        $purchase_request_item = $query->offset($request->start)
                    ->limit($request->length)
                    ->get();
        
        $data = $purchase_request_item->map(function($item) {
            return [
                'id' => $item->id,
                'item_code' => $item->inventory->item_code,
                'item_description' => $item->inventory->item_code,
                'category' => "",
                'subcategory' => "",
                'qty' => $item->inventory->qty,
                'amount' => $item->inventory->cost
            ];
        });
        
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $purchase_request_item->count(),
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
        $purchase_request = new PurchaseRequest;
        $purchase_request->user_id = $request->user_id;
        $purchase_request->due_date = $request->due_date;
        $purchase_request->remarks = $request->remarks;
        $purchase_request->subsidiary_id = $request->subsidiary;
        $purchase_request->department_id = $request->department;
        $purchase_request->classification_id = $request->classification;
        $purchase_request->estimated_amount = $request->estimated_amount;
        $purchase_request->status = 'Pending';
        $purchase_request->save();

        foreach($request->inventoryItem as $inventoryItem)
        {
            $inventory_item = new PurchaseRequestItem;
            $inventory_item->purchase_request_id = $purchase_request->id;
            $inventory_item->inventory_id = $inventoryItem;
            $inventory_item->save();
        }

        $attachments = $request->file('attachments');
        foreach($attachments as $attachment)
        {
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path('purchase_request_files'),$name);
            $file_name = '/purchase_request_files/' . $name;

            $files = new PurchaseRequestFile;
            $files->purchase_request_id = $purchase_request->id;
            $files->file = $file_name;
            $files->save();
        }
        
        return response()->json([
            'url' => url('purchase-request'),
            'status' => 200,
            'msg' => 'Successfully Saved'
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
