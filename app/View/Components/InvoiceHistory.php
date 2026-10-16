<?php

namespace App\View\Components;

use App\Models\Invoice;
use Illuminate\View\Component;

class InvoiceHistory extends Component
{
    public function __construct(
        public Invoice $invoice
    ) {}

    public function render()
    {
        $history = $this->invoice->editHistory()
            ->with('editor')
            ->get();

        return view('components.invoice-history', [
            'history' => $history,
        ]);
    }
}
