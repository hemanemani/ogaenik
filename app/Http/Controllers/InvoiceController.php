<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use TCPDF;
use Auth;

class InvoiceController extends Controller
{
    //downloads customer invoice
    
        public function customer_invoice_download($id)
        {
            require_once(base_path('vendor/tcpdf/tcpdf.php'));
            $pdf = new TCPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Orgenik');
            $pdf->SetTitle('Customer Invoice');
            $pdf->SetSubject('Invoice for Order');
            $pdf->AddPage();
    
            $order = Order::findOrFail($id);
            $html = view('backend.invoices.customer_invoice', compact('order'))->render();
            $pdf->writeHTML($html, true, false, true, false, '');
            return $pdf->Output('order-' . $order->code . '.pdf', 'D');
        }

    
    
    // public function customer_invoice_download($id)
    // {
    //     $order = Order::findOrFail($id);
    //     $pdf = PDF::setOptions([
    //                     'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
    //                     'logOutputFile' => storage_path('logs/log.htm'),
    //                     'tempDir' => storage_path('logs/')
    //                 ])->loadView('backend.invoices.customer_invoice', compact('order'));
    //     return $pdf->download('order-'.$order->code.'.pdf');
    // }

    //downloads seller invoice
    public function seller_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('backend.invoices.seller_invoice', compact('order'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads admin invoice
    public function admin_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('backend.invoices.admin_invoice', compact('order'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }
}
