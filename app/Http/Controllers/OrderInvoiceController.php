<?php


namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderInvoiceController extends Controller
{
    public function __invoke(Order $order)
    {
        Gate::authorize(PermissionType::OrderView);
    }

}
