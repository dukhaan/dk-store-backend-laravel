<?php

namespace App\Http\Controllers;

use App\Models\TransactionItem;
use App\Http\Requests\StoreTransactionItemRequest;
use App\Http\Requests\UpdateTransactionItemRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;

class TransactionItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $users_id = $request->input('users_id');
        //$limit = $request->input('limit');
        $products_id = $request->input('products_id');
        $transactions_id = $request->input('transactions_id');
        $quantity = $request->input('quantity');

        if ($id) {
            $transactionItem = TransactionItem::find($id);

            if ($transactionItem) {
                return ResponseFormatter::success(
                    $transactionItem,
                    'Data transaksi item berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi item tidak ada',
                    404
                );
            }
        }

        $transactionItem = TransactionItem::query();

        if ($users_id) {
            $transactionItem->where('users_id', $users_id);
        }

        if ($products_id) {
            $transactionItem->where('products_id', $products_id);
        }

        if ($transactions_id) {
            $transactionItem->where('transactions_id', $transactions_id);
        }

        if ($quantity) {
            $transactionItem->where('quantity', $quantity);
        }

        return ResponseFormatter::success(
            $transactionItem->paginate(10),
            'Data list transaksi item berhasil diambil'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //create transaction item
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
            'transactions_id' => 'required|exists:transactions,id',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                null,
                $validator->errors(),
                404
            );
        }

        $transactionItem = TransactionItem::create([
            'users_id' => $request->users_id,
            'products_id' => $request->products_id,
            'transactions_id' => $request->transactions_id,
            'quantity' => $request->quantity,
        ]);

        if ($transactionItem) {
            return ResponseFormatter::success(
                $transactionItem,
                'Data transaksi item berhasil ditambahkan'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi item gagal ditambahkan',
                404
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionItem $transactionItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionItem $transactionItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //update transaction item

        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
            'transactions_id' => 'required|exists:transactions,id',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                null,
                $validator->errors(),
                404
            );
        }

        $transactionItem = TransactionItem::find($request->id);

        if ($transactionItem) {
            $transactionItem->update([
                'users_id' => $request->users_id,
                'products_id' => $request->products_id,
                'transactions_id' => $request->transactions_id,
                'quantity' => $request->quantity,
            ]);

            return ResponseFormatter::success(
                $transactionItem,
                'Data transaksi item berhasil diupdate'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi item gagal diupdate',
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //delete transaction item
        $transactionItem = TransactionItem::find($request->id);

        if ($transactionItem) {
            $transactionItem->delete();
            return ResponseFormatter::success(
                $transactionItem,
                'Data transaksi item berhasil dihapus'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi item gagal dihapus',
                404
            );
        }
    }
}
