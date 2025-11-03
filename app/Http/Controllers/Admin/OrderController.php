<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::query();

            if ($request->has('status') && in_array($request->status, ['pending','processing','completed','cancelled'])) {
                $query->where('status', $request->status);
            }

            $query->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function($row) {
                    $statuses = ['pending','processing','completed','cancelled'];
                    $colors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger'
                    ];

                    $html = '<select class="form-select form-select-sm change-status" data-id="'.$row->id.'">';
                    foreach($statuses as $status) {
                        $selected = $row->status === $status ? 'selected' : '';
                        $html .= '<option value="'.$status.'" '.$selected.'>'.$status.'</option>';
                    }
                    $html .= '</select>';

                    return $html;
                })
                ->addColumn('invoice', function($row){
                    return '<a href="'.route('order.invoice', $row->id).'" class="fw-medium link-primary" target="_blank">#'.$row->order_number.'</a>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at ? $row->created_at->format('d F Y') : '';
                })
                ->addColumn('action', function($row){
                    return '<a href="'.route('orders.details', $row->id).'" class="btn btn-sm btn-info" target="_blank">Details</a>';
                })
                ->rawColumns(['status','action','invoice'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function changeStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.'
        ]);
    }

    public function show(Order $order)
    {
        $order->load('orderDetails.product', 'orderDetails.size', 'orderDetails.color', 'orderDetails.orderCustomisations');

        return view('admin.orders.details', compact('order'));
    }

}