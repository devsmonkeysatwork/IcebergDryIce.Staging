<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    const PENDING = 'pending';


    protected $fillable = [
        'invoice_number',
        'invoice_type',
        'invoiceable_id',
        'invoiceable_type',
        'total_amount',
        'payment_status',
        'parent_invoice_id',
        'recurring_sequence',
        'invoice_date'
    ];

    protected $casts = [
        'invoice_date' => 'date'
    ];

    // Polymorphic relationship - can belong to Order or RecurringOrder
    public function invoiceable()
    {
        return $this->morphTo();
    }

    // Parent invoice (for recurring invoices)
    public function parentInvoice()
    {
        return $this->belongsTo(Invoice::class, 'parent_invoice_id');
    }

    // Child invoices (all recurring instances)
    public function childInvoices()
    {
        return $this->hasMany(Invoice::class, 'parent_invoice_id')->orderBy('recurring_sequence');
    }

    // Get next invoice in sequence
    public function nextInvoice()
    {
        return $this->hasOne(Invoice::class, 'parent_invoice_id')
            ->where('recurring_sequence', $this->recurring_sequence + 1);
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -6) + 1 : 1;
        return 'INV-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    // Check if this is a recurring invoice
    public function isRecurring(): bool
    {
        return $this->invoice_type === 'recurring';
    }

    // Check if this is the original invoice
    public function isOriginal(): bool
    {
        return $this->recurring_sequence === 1 && is_null($this->parent_invoice_id);
    }
}
