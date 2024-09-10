<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $invoices = Invoice::where('user_id', $user_id)->with('customer')->get();
            return $this->sendSuccess("Invoices fetched successfully", $invoices, 200);
        } catch (Exception $e) {
            return $this->sendError("Failed to fetch Invoices", 200, $e->getMessage());
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $invoice = Invoice::create([
                'total' => 0,
                'discount' => $request->discount,
                'vat' => $request->vat,
                'payable' => 0,
                'user_id' => $user_id,
                'customer_id' => $request->customer_id,
            ]);

            $totalPrice = 0;
            foreach ($request->products as $product) {
                $productPrice = Product::where('id', $product['product_id'])->first()->price;
                InvoiceProduct::create([
                    'quantity' => $product['quantity'],
                    'sale_price' => $product['quantity'] * $productPrice,
                    'product_id' => $product['product_id'],
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                ]);
                $totalPrice += $product['quantity'] * $productPrice;
            }

            $invoice->total = $totalPrice;
            $invoice->payable = ($totalPrice - $request->discount) + $request->vat;
            $invoice->save();

            return $this->sendSuccess("Invoice created successfully", $invoice, 201);

        } catch (Exception $e) {
            return $this->sendError("Failed to create Invoice", 200, $e->getMessage());
        }
    }
    public function show($invoice, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $invoice = Invoice::where('user_id', $user_id)->with(['customer', 'invoiceProducts.product'])->where('id', $invoice)->first();
            return $this->sendSuccess("Invoice fetched successfully", $invoice, 200);
        } catch (Exception $e) {
            return $this->sendError("Failed to fetch Invoice", 200, $e->getMessage());
        }
    }

    public function edit(Invoice $invoice)
    {
        //
    }

    public function destroy($invoice, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            InvoiceProduct::where('invoice_id', $invoice)->delete();
            Invoice::where('user_id', $user_id)->where('id', $invoice)->delete();
            return $this->sendSuccess("Invoice deleted successfully", null, 200);
        } catch (Exception $th) {
            return $this->sendError("Failed to delete Invoice", 200, $th->getMessage());
        }
    }
}