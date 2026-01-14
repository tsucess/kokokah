@extends('layouts.usertemplate')

@section('content')
<style>
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        border: none;
    }

    .status-pill.active {
        background-color: #198754;
        color: #fff;
    }

    .status-pill.paused {
        background-color: #ffc107;
        color: #000;
    }

    .status-pill.cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .status-pill.expired {
        background-color: #6c757d;
        color: #fff;
    }
</style>
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">My Subscriptions</h1>
            <p class="text-muted">Manage your active subscriptions and learning plans</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="{{ route('subscriptions.plans') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Browse Plans
            </a>
        </div>
    </div>

    <div class="row" id="subscriptionsContainer">
        <div class="col-12 text-center">
            <p>Loading your subscriptions...</p>
        </div>
    </div>
</div>

<script>
    const API_BASE_URL = '/api/subscriptions';

    document.addEventListener('DOMContentLoaded', function() {
        loadMySubscriptions();
    });

    async function loadMySubscriptions() {
        try {
            const response = await fetch(`${API_BASE_URL}/my-subscriptions`);
            const data = await response.json();

            if (data.success) {
                renderSubscriptions(data.data.data || []);
            } else {
                showError('Failed to load subscriptions');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error loading subscriptions');
        }
    }

    function renderSubscriptions(subscriptions) {
        const container = document.getElementById('subscriptionsContainer');
        
        if (subscriptions.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info">
                        <p>You don't have any active subscriptions yet.</p>
                        <a href="/subscriptions/plans" class="btn btn-primary btn-sm">Browse Plans</a>
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = subscriptions.map(sub => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${sub.subscription_plan.title}</h5>
                        <p class="card-text text-muted">${sub.subscription_plan.description || ''}</p>
                        
                        <div class="mb-3">
                            <span class="status-pill ${sub.status}">
                                ${getStatusIcon(sub.status)} ${sub.status.charAt(0).toUpperCase() + sub.status.slice(1)}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <strong>Started:</strong> ${formatDate(sub.started_at)}<br>
                                <strong>Expires:</strong> ${formatDate(sub.expires_at)}<br>
                                <strong>Amount Paid:</strong> ₦${formatPrice(sub.amount_paid)}
                            </small>
                        </div>

                        <div class="progress mb-3">
                            <div class="progress-bar" style="width: ${getProgressPercentage(sub.started_at, sub.expires_at)}%"></div>
                        </div>

                        <div class="d-grid gap-2">
                            ${sub.status === 'active' ? `
                                <button class="btn btn-warning btn-sm" onclick="pauseSubscription(${sub.id})">
                                    <i class="fa-solid fa-pause"></i> Pause
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="cancelSubscription(${sub.id})">
                                    <i class="fa-solid fa-times"></i> Cancel
                                </button>
                            ` : sub.status === 'paused' ? `
                                <button class="btn btn-success btn-sm" onclick="resumeSubscription(${sub.id})">
                                    <i class="fa-solid fa-play"></i> Resume
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async function pauseSubscription(subId) {
        const confirmed = await showConfirmation('Pause Subscription', 'Are you sure you want to pause this subscription?', 'Pause', 'Cancel');
        if (!confirmed) return;
        await updateSubscription(subId, 'pause');
    }

    async function resumeSubscription(subId) {
        const confirmed = await showConfirmation('Resume Subscription', 'Are you sure you want to resume this subscription?', 'Resume', 'Cancel');
        if (!confirmed) return;
        await updateSubscription(subId, 'resume');
    }

    async function cancelSubscription(subId) {
        const confirmed = await showConfirmation('Cancel Subscription', 'Are you sure you want to cancel this subscription? This action cannot be undone.', 'Cancel', 'Keep');
        if (!confirmed) return;
        await updateSubscription(subId, 'cancel');
    }

    async function showConfirmation(title, message, confirmText, cancelText) {
        if (window.confirmationModal) {
            return await confirmationModal.show(title, message, confirmText, cancelText);
        } else {
            return confirm(message);
        }
    }

    async function updateSubscription(subId, action) {
        try {
            const response = await fetch(`${API_BASE_URL}/${subId}/${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                showSuccess(`Subscription ${action}ed successfully`);
                loadMySubscriptions();
            } else {
                showError(data.message || `Failed to ${action} subscription`);
            }
        } catch (error) {
            console.error('Error:', error);
            showError(`Error ${action}ing subscription`);
        }
    }

    function getStatusColor(status) {
        const colors = {
            'active': 'success',
            'paused': 'warning',
            'cancelled': 'danger',
            'expired': 'secondary'
        };
        return colors[status] || 'secondary';
    }

    function getStatusIcon(status) {
        const icons = {
            'active': '✓',
            'paused': '⏸',
            'cancelled': '✕',
            'expired': '⏱'
        };
        return icons[status] || '○';
    }

    function getProgressPercentage(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const now = new Date();
        const total = end - start;
        const elapsed = now - start;
        return Math.min(100, Math.max(0, (elapsed / total) * 100));
    }

    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-NG');
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('en-NG').format(price);
    }

    function showSuccess(msg) {
        if (window.ToastNotification) {
            ToastNotification.success('Success', msg, 3500);
        } else {
            alert(msg);
        }
    }

    function showError(msg) {
        if (window.ToastNotification) {
            ToastNotification.error('Error', msg, 5000);
        } else {
            alert('Error: ' + msg);
        }
    }
</script>
@endsection

