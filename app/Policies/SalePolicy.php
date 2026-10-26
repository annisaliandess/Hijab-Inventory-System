<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    /**
     * Menentukan hak akses untuk melihat detail nota transaksi.
     * Admin & Staff dapat melihat semua pesanan.
     * Pelanggan/Customer HANYA dapat melihat pesanan miliknya sendiri.
     */
    public function view(User $user, Sale $sale): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        return (int) $sale->customer_id === (int) $user->id;
    }
}
