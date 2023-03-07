<?php

namespace App\Models;

use App\Models\Summary;
use App\Plugins\Stripes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'summary_id',
        'checkoutSession_id',
        'payment_intent_id',
        'pay_attempt_at',
        'pay_complete_at',
        'pay_confirm_at',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Summary that owns the Bill.
     */
    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function getPayAttemptAt()
    {
        return Carbon::parse($this->pay_attempt_at)->translatedFormat('j F Y h:m:s A');
    }

    public function getPayCompleteAt()
    {
        return Carbon::parse($this->pay_complete_at)->translatedFormat('j F Y h:m:s A');
    }

    public function getPayConfirmAt()
    {
        return Carbon::parse($this->pay_confirm_at)->translatedFormat('j F Y h:m:s A');
    }

    public function getTransactionDate()
    {
        return Carbon::parse($this->pay_complete_at)->translatedFormat('j F Y');
    }

    public function getReceiptDate()
    {
        return Carbon::now()->translatedFormat('j F Y');
    }

    public function getReceiptGenerateDate()
    {
        return Carbon::now()->translatedFormat('d/m/Y');
    }

    public function getStatusPayment()
    {
        return DB::table('status_payment')->where('code', $this->status)->first();
    }

    public function getPaymentMethod()
    {
        $checkoutSession = Stripes::getCheckoutSession($this->checkoutSession_id);

        return strtoupper($checkoutSession->payment_intent->payment_method->type);
    }

    public function setPaymentIntent()
    {
        $checkout_session = Stripes::getCheckoutSession($this->checkoutSession_id);
        $this->payment_intent_id = $checkout_session->payment_intent->id;
    }

    public function getReceiptFilepath()
    {
        if (!$this->receipt) {
            $this->receipt = '[RECEIPT]_' . str_replace('-', '_', $this->summary->registration->code) . '.pdf';
        }

        return 'ARAHE' . $this->summary->registration->form->session->year . '/payment/' . $this->receipt;
    }

    public function downloadReceipt()
    {
        $filePath = 'ARAHE' . $this->summary->registration->form->session->year . '/payment/' . $this->receipt;

        return Storage::response($filePath);
    }
}
