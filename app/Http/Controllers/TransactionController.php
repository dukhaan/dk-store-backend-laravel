<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');

        if ($id) {
            $transaction = Transaction::find($id);

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $transactions = Transaction::with(['items.product'])->where('users_id', Auth::user()->id);

        if ($status) {
            $transactions->where('status', $status);
        }

        return ResponseFormatter::success(
            $transactions->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:products,id', // Validate that each product_id exists in the products table
            'total_price' => 'required',
            'shipping_price' => 'required', // Validate that each quantity is an integer and at least 1
            'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED', // Validate that each quantity is an integer and at least 1
        ]);

        $user = Auth::user();
        $transaction = Transaction::create([
            'users_id' => $user->id,
            'address' => $request->address,
            'total_price' => $request->total_price, // Placeholder, will be updated below
            'shipping_price' => $request->shipping_price, // Placeholder, will be updated below
            'status' => $request->status, // You can set the initial status here, or adjust it based on your needs
        ]);

        // Attach items to the transaction
        foreach ($request->items as $product) {
            // Create transaction item
            TransactionItem::create([
                'users_id' => $user->id,
                'products_id' => $product['id'],
                'transactions_id' => $transaction['id'],
                'quantity' => $product['quantity']
            ]);
        }

        return ResponseFormatter::success($transaction->load('items.product'), 'Transaksi Berhasil');
    }

    public function store(Request $request){
        $transaction = Transaction::create([
            'users_id' => $request->users_id,
            'address' => $request->address,
            'total_price' => $request->total_price, 
            'shipping_price' => $request->shipping_price, 
        ]);

        if ($transaction) {
            return ResponseFormatter::success(
                $transaction,
                'Data transaksi berhasil ditambahkan'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi gagal ditambahkan',
                404
            );
        }
    }

    public function destroy(Request $request){
        $transaction = Transaction::find($request->id);

        if ($transaction) {
            $transaction->delete();
            return ResponseFormatter::success(
                $transaction,
                'Data transaksi berhasil dihapus'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi gagal dihapus',
                404
            );
        }
    }

    public function update(Request $request){
        $transaction = Transaction::find($request->id);

        if ($transaction) {
            $transaction->update([
                'users_id' => $request->users_id,
                'address' => $request->address,
                'total_price' => $request->total_price, 
                'shipping_price' => $request->shipping_price, 
            ]);
            return ResponseFormatter::success(
                $transaction,
                'Data transaksi berhasil diupdate'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi gagal diupdate',
                404
            );
        }
    }
}
