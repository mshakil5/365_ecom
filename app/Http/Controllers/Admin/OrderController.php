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
              ->editColumn('status', function ($row) {
                  $statuses = ['pending','processing','completed','cancelled'];
                  $html = '<select class="form-select form-select-sm change-status" data-id="'.$row->id.'">';
                  foreach ($statuses as $status) {
                      $selected = $row->status === $status ? 'selected' : '';
                      $html .= '<option value="'.$status.'" '.$selected.'>'.ucfirst($status).'</option>';
                  }
                  $html .= '</select>';
                  return $html;
              })
              ->addColumn('invoice', function ($row) {
                  return '<a href="'.route('order.invoice', $row->id).'" class="fw-medium link-primary" target="_blank">#'.$row->order_number.'</a>';
              })
              ->editColumn('full_name', function ($row) {
                  return $row->full_name ?? $row->billing_full_name ?? '';
              })
              ->editColumn('created_at', function ($row) {
                  return $row->created_at ? $row->created_at->format('d F Y') : '';
              })
              ->addColumn('action', function ($row) {
                  $modalId = 'quickModal'.$row->id;

                  // Fallbacks
                  $name = $row->full_name ?? $row->billing_full_name ?? '';
                  $email = $row->email ?? $row->billing_email ?? '';
                  $phone = $row->phone ?? $row->billing_phone ?? '';

                  $quickViewBtn = '<button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#'.$modalId.'">Quick</button>';
                  $detailsBtn = '<a href="'.route('orders.details', $row->id).'" class="btn btn-sm btn-info">Details</a>';

                  $modalHtml = '
                  <div class="modal fade" id="'.$modalId.'" tabindex="-1" aria-labelledby="'.$modalId.'Label" aria-hidden="true">
                      <div class="modal-dialog modal-md">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="'.$modalId.'Label">Order #'.$row->order_number.' - '.ucfirst($row->status).'</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <p><strong>Customer:</strong> '.$name.' | '.$email.' | '.$phone.'</p>
                                  <p><strong>Total Amount:</strong> $'.number_format($row->total_amount,2).'</p>
                                  <p><strong>Payment Method:</strong> '.ucfirst(str_replace('_',' ',$row->payment_method)).'</p>
                                  <p><strong>Date:</strong> '.$row->created_at->format('d F Y').'</p>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                  <a href="'.route('orders.details', $row->id).'" class="btn btn-primary">Full Details</a>
                              </div>
                          </div>
                      </div>
                  </div>';

                  return $quickViewBtn.$detailsBtn.$modalHtml;
              })
              ->rawColumns(['status','invoice','action'])
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