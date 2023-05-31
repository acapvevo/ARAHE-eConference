<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Form extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Session associated with the Form.
     */
    public function session()
    {
        return $this->hasOne(Session::class);
    }

    /**
     * Get the Rubrics for the Form.
     */
    public function rubrics()
    {
        return $this->hasMany(Rubric::class);
    }

    /**
     * Get the Registrations that use the Form.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the Categories associated with the Form.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the Durations associated with the Form.
     */
    public function durations()
    {
        return $this->hasMany(Duration::class);
    }

    /**
     * Get the Extras associated with the Form.
     */
    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    /**
     * Get the Hotels associated with the Form.
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Get the Occupancies associated with the Form.
     */
    public function occupancies()
    {
        return $this->hasMany(Occupancy::class);
    }

    public function getPackages()
    {
        return Package::whereIn('category_id', $this->categories->pluck('id'))->get();
    }

    public function getRates()
    {
        return Rate::whereIn('hotel_id', $this->hotels->pluck('id'))->get();
    }

    public function calculateFullMark()
    {
        $countScale = DB::table('scale')->get()->count();
        return $this->rubrics->count() * $countScale;
    }

    public function getDurationBasedCurrentDate($locality_code)
    {
        $currentDate = Carbon::now();

        return $this->durations->where('locality', $locality_code)->first(function ($duration) use ($currentDate) {
            return $currentDate->between($duration->start, $duration->end->addDay());
        });
    }

    public function getOccupanciesByLocality($locality_code)
    {
        return $this->occupancies->where('locality', $locality_code);
    }

    public function getHotelsByLocality($locality_code)
    {
        return $this->hotels->where('locality', $locality_code);
    }

    public function updateDurationsInStripe()
    {
        foreach ($this->durations as $duration) {
            $duration->updateFeesInStripe();
        }
    }

    public function updateCategoiesInStripe()
    {
        foreach ($this->getPackages() as $package) {
            $package->updateFeesInStripe();
        }
    }

    public function updateHotelsInStripe()
    {
        foreach ($this->hotels as $hotel) {
            $hotel->updateRatesInStripe();
        }
    }

    public function updateOccupansiesInStripe()
    {
        foreach ($this->occupancies as $occupancy) {
            $occupancy->updateRatesInStripe();
        }
    }

    public function getTotalPaymentDone()
    {
        $localities = DB::table('locality')->get();

        $totalPayment = [];
        foreach ($localities as $locality) {
            $totalPayment[$locality->code] = 0;
        }

        foreach ($this->registrations as $regisration) {
            if ($regisration->summary && ($regisration->status_code === 'AR' || $regisration->status_code === 'PW')) {
                $totalPayment[$regisration->summary->locality] += $regisration->summary->total;
            }
        }

        return $totalPayment;
    }

    public function getPaperPending()
    {
        return Submission::where('status_code', 'P')->whereIn('registration_id', $this->registrations->pluck('id'))->get()->count();
    }

    public function getTotalPaper()
    {
        return Submission::whereIn('registration_id', $this->registrations->pluck('id'))->get()->count();
    }

    public function getRegistrationStatistic()
    {
        $registrationsByStatus = $this->registrations->mapToGroups(function ($registration) {
            return [$registration->getStatusLabel() => $registration];
        });

        $registrationsByStatusCount = [];
        foreach ($registrationsByStatus as $statusLabel => $registrations) {
            $registrationsByStatusCount[$statusLabel] = $registrations->count();
        }

        return $registrationsByStatusCount;
    }

    public function getSubmissionStatistic()
    {
        $submissionsByStatus = Submission::whereIn('registration_id', $this->registrations->pluck('id'))->get()->mapToGroups(function ($submission) {
            return [$submission->getStatusLabel() => $submission];
        });

        $submissionsByStatusCount = [];
        foreach ($submissionsByStatus as $statusLabel => $submissions) {
            $submissionsByStatusCount[$statusLabel] = $submissions->count();
        }

        return $submissionsByStatusCount;
    }

    public function getPaymentStatistic()
    {
        $localities = DB::table('locality')->get();
        $bills = Bill::whereBetween('pay_confirm_at', [$this->session->returnDateObj('registration', 'start'), $this->session->returnDateObj('registration', 'end')])->where('status', 1)->get();
        $months = CarbonPeriod::create($this->session->returnDateObj('registration', 'start'), '1 month', $this->session->returnDateObj('registration', 'end'));

        foreach($localities as $locality){
            $totalPaymentByLocalityByMonth[$locality->name . ' (' . $locality->currency . ')'] = [];

            foreach($months as $month){
                $totalPaymentByLocalityByMonth[$locality->name . ' (' . $locality->currency . ')'][$month->translatedFormat('F')] = 0;

                foreach ($bills as $bill) {
                    if($locality->name == $bill->summary->getLocality()->name && $month->translatedFormat('F') == $bill->getPayConfirmAtMonth()){
                        $totalPaymentByLocalityByMonth[$locality->name . ' (' . $locality->currency . ')'][$month->translatedFormat('F')] += $bill->summary->total;
                    }
                }
            }
        }


        return $totalPaymentByLocalityByMonth;
    }

    public function getMonthsSession()
    {
        $months = CarbonPeriod::create($this->session->returnDateObj('registration', 'start'), '1 month', $this->session->returnDateObj('registration', 'end'));

        $monthsName = collect();
        foreach($months as $month){
            $monthsName->push($month->translatedFormat('F'));
        }

        return $monthsName;
    }
}
