<?php

namespace App\Http\Controllers;

use App\PurchaseRequest;
use App\PurchaseRequestItem;
use Illuminate\Http\Request;

class ForApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('for_approval.for_approval');
    }

    public function getForApprovalPr(Request $request)
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
                    ->where('status', 'Pending')
                    ->where('department_id', auth()->user()->department_id)
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
                'total_cost' => number_format($total_cost,2)
            ];
        });
        
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $purchase_request->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function getForApprovalItem(Request $request)
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

    
    public function returnPurchaseRequest(Request $request)
    {
        $purchase_request = PurchaseRequest::findOrFail($request->id);
        $purchase_request->status = 'Returned';
        $purchase_request->save();

        return response()->json([
            'status' => '201',
            'msg' => 'Successfully Returned'
        ]);
    }
}
