<?php

namespace App\Models;

use App\Models\Summary;
use App\Plugins\Stripes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'status',
    ];

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
        'proof',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pay_attempt_at' => 'datetime',
        'pay_complete_at' => 'datetime',
        'pay_confirm_at' => 'datetime',
    ];

    /**
     * Get the Summary that owns the Bill.
     */
    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function getCurrency()
    {
        return $this->summary->getLocality()->currency;
    }

    public function getPayAttemptAt()
    {
        return $this->pay_attempt_at ? Timezone::convertToLocal($this->pay_attempt_at) : '';
    }

    public function getPayCompleteAt()
    {
        return $this->pay_complete_at ? Timezone::convertToLocal($this->pay_complete_at) : '';
    }

    public function getPayConfirmAt()
    {
        return $this->pay_confirm_at ? Timezone::convertToLocal($this->pay_confirm_at) : '';
    }

    public function getPayExpiredAt()
    {
        return Carbon::parse($this->pay_attempt_at)->addHours(3)->toRfc2822String();
    }

    public function getPayConfirmAtMonth()
    {
        return Carbon::parse($this->pay_confirm_at)->translatedFormat('F');
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
        if ($this->checkoutSession_id) {
            $checkoutSession = Stripes::getCheckoutSession($this->checkoutSession_id);

            return strtoupper($checkoutSession->payment_intent->payment_method->type ?? '');
        } else {
            return "MANUAL";
        }
    }

    public function setPaymentIntent()
    {
        $checkout_session = Stripes::getCheckoutSession($this->checkoutSession_id);
        $this->payment_intent_id = $checkout_session->payment_intent->id;
    }

    public function saveProof($proofFile)
    {
        $fileName = str_replace('-', '_', '[PROOF]_' . $this->summary->registration->code . '.' . $proofFile->getClientOriginalExtension());
        $proofFile->storeAs('ARAHE' . $this->summary->registration->form->session->year . '/proof', $fileName);

        $this->proof = $fileName;
    }

    public function downloadProof()
    {
        $filePath = 'ARAHE' . $this->summary->registration->form->session->year . '/proof/' . $this->proof;

        return Storage::response($filePath);
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
