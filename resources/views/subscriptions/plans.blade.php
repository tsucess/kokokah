@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">Choose Your Learning Plan</h1>
            <p class="text-muted">Select the perfect plan to enhance your learning experience</p>
        </div>
    </div>

    <div class="row" id="subscriptionPlansContainer">
        <div class="col-12 text-center">
            <p>Loading subscription plans...</p>
        </div>
    </div>
</div>

<!-- Subscribe Modal -->
<div class="modal fade" id="subscribeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscribe to Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="subscribeForm">
                <div class="modal-body">
                    <input type="hidden" id="planId">
                    <div class="mb-3">
                        <label class="form-label">Plan: <strong id="planName"></strong></label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price: <strong id="planPrice"></strong></label>
                    </div>
                    <div class="mb-3">
                        <label for="amountPaid" class="form-label">Amount to Pay</label>
                        <input type="number" class="form-control" id="amountPaid" placeholder="Enter amount" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentReference" class="form-label">Payment Reference (Optional)</label>
                        <input type="text" class="form-control" id="paymentReference" placeholder="e.g., TXN123456">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Subscribe Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const API_BASE_URL = '/api/subscriptions';

    document.addEventListener('DOMContentLoaded', function() {
        loadPlans();
        setupFormHandlers();
    });

    async function loadPlans() {
        try {
            const response = await fetch(`${API_BASE_URL}/plans`);
            const data = await response.json();

            if (data.success) {
                renderPlans(data.data.data || []);
            } else {
                showError('Failed to load plans');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error loading plans');
        }
    }

    function renderPlans(plans) {
        const container = document.getElementById('subscriptionPlansContainer');
        
        if (plans.length === 0) {
            container.innerHTML = '<div class="col-12 text-center"><p>No subscription plans available</p></div>';
            return;
        }

        container.innerHTML = plans.map(plan => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${plan.title}</h5>
                        <p class="card-text text-muted">${plan.description || ''}</p>
                        <div class="mb-3">
                            <span class="h4">₦${formatPrice(plan.price)}</span>
                            <span class="text-muted">/${plan.duration_type}</span>
                        </div>
                        <ul class="list-unstyled mb-3">
                            ${renderFeatures(plan.features)}
                        </ul>
                        <button class="btn btn-primary w-100" onclick="openSubscribeModal(${plan.id}, '${plan.title}', ${plan.price})">
                            Subscribe Now
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderFeatures(features) {
        if (!features || features.length === 0) return '';
        const featureArray = Array.isArray(features) ? features : JSON.parse(features);
        return featureArray.map(f => `<li><i class="fa-solid fa-check text-success"></i> ${f}</li>`).join('');
    }

    function openSubscribeModal(planId, planName, price) {
        document.getElementById('planId').value = planId;
        document.getElementById('planName').textContent = planName;
        document.getElementById('planPrice').textContent = `₦${formatPrice(price)}`;
        document.getElementById('amountPaid').value = price;
        new bootstrap.Modal(document.getElementById('subscribeModal')).show();
    }

    function setupFormHandlers() {
        document.getElementById('subscribeForm').addEventListener('submit', handleSubscribe);
    }

    async function handleSubscribe(e) {
        e.preventDefault();

        const formData = {
            subscription_plan_id: parseInt(document.getElementById('planId').value),
            amount_paid: parseFloat(document.getElementById('amountPaid').value),
            payment_reference: document.getElementById('paymentReference').value || null
        };

        try {
            const response = await fetch(`${API_BASE_URL}/subscribe`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                showSuccess('Successfully subscribed to plan!');
                bootstrap.Modal.getInstance(document.getElementById('subscribeModal')).hide();
                document.getElementById('subscribeForm').reset();
            } else {
                showError(data.message || 'Failed to subscribe');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error subscribing to plan');
        }
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('en-NG').format(price);
    }

    function showSuccess(msg) { alert(msg); }
    function showError(msg) { alert('Error: ' + msg); }
</script>
@endsection

