@extends('admin.layouts.app')

@section('content')
    <h3 class="text-dark mb-1">Dashboard</h3>

    <div class="card">
        <h4 class="card-header text-center">ARAHE{{ $form->session->year }}</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-secondary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-secondary fw-bold text-xs mb-1"><span>Pending
                                            Registration</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0">
                                        <span>{{ $form->registrations->where('status_code', 'WR')->count() }}
                                            Registrations</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-secondary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-secondary fw-bold text-xs mb-1"><span>Pending
                                            Paper</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $form->getPaperPending() }}
                                            Papers</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Payment Done
                                            (USD$)</span><span class="float-end">Payment Done (RM)</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0">
                                        <span>USD${{ number_format($totalPayment['I'], 2) }}</span><span
                                            class="float-end">RM{{ number_format($totalPayment['L'], 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Paper
                                            Submission</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $form->getTotalPaper() }} Papers</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-3 pb-3">
                <div class="col-lg-6">
                    <div class="card m-3">
                        <h5 class="card-header text-center">Registrations Statistic</h5>
                        <div class="card-body">
                            <canvas id="registrationStatistic"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card m-3">
                        <h5 class="card-header text-center">Submissions Statistic</h5>
                        <div class="card-body">
                            <canvas id="submissionStatistic"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 pb-3">
                <div class="card m-3">
                    <h5 class="card-header text-center">Payment Statistic</h5>
                    <div class="card-body">
                        <canvas id="paymentStatistic"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const submissionStatistic = document.getElementById('submissionStatistic');
        const registrationStatistic = document.getElementById('registrationStatistic');
        const paymentStatistic = document.getElementById('paymentStatistic');

        axios.post("{{ route('admin.dashboard.statistic') }}", {
            form_id: {{ $form->id }}
        }).then(function(response) {
            const data = response.data;

            const dataRegistrationStatistic = {
                labels: Object.keys(response.data.registrationStatistic),
                datasets: [{
                    data: Object.values(response.data.registrationStatistic),
                    backgroundColor: generateDynamicColorArrayHSLA(Object.values(response.data
                        .registrationStatistic).length),
                    hoverOffset: 4
                }]
            }

            new Chart(registrationStatistic, {
                type: 'pie',
                data: dataRegistrationStatistic
            });

            const dataSubmissionStatistic = {
                labels: Object.keys(response.data.submissionStatistic),
                datasets: [{
                    data: Object.values(response.data.submissionStatistic),
                    backgroundColor: generateDynamicColorArrayHSLA(Object.entries(response.data
                        .submissionStatistic).length),
                    hoverOffset: 4
                }]
            }

            new Chart(submissionStatistic, {
                type: 'pie',
                data: dataSubmissionStatistic
            });

            let datasetsPaymentStatistic = [];
            for (const [locality, totalByMonth] of Object.entries(data.paymentStatistic)) {
                datasetsPaymentStatistic.push({
                    label: locality,
                    data: Object.values(totalByMonth),
                    borderColor: generateRandomRGBA(),
                    tension: 0.1
                });
            }

            const dataPaymentStatistic = {
                labels: data.sessionMonths,
                datasets: datasetsPaymentStatistic
            };

            new Chart(paymentStatistic, {
                type: 'line',
                data: dataPaymentStatistic,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }).catch(function(error) {
            apiError();
        });
    </script>
@endsection
