@extends('layouts.dashboardtemp')

@section('content')
    <style>
        p {
            margin-bottom: 0;
        }

        .modal-subtitle {
            color: #004A53;
            font-size: 20px;
            font-family: "Fredoka", sans-serif;
        }

        .primaryBtn {
            background-color: #FDAF22;
            padding: 10px 40px;
            border-radius: 4px;
            color: #000F11;
            font-size: 16px;
            font-weight: 600;
        }

        .cancel-btn {
            background-color: transparent;
            padding: 10px 40px;
            border-radius: 4px;
            color: #004A53;
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #004A53;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, min(100%, 320px)));
            gap: 30px;
        }

        .stats-container {
            border: 1px solid #CCDBDD;
            padding: 30px;
            gap: 30px;
            border-radius: 20px;
        }

        .stats-title {
            color: #004A53;
            font-size: 16px;
            font-family: "Fredoka", sans-serif;
        }

        .stats-value {
            color: #004A53;
            font-size: 32px;
            font-family: "Fredoka", sans-serif;
        }

        .plan-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, min(100%, 310px)));
            gap: 20px;
        }

        .plan-card {
            border: 1px solid #CCDBDD;
            border-radius: 20px;
            padding: 30px;
            background-color: transparent;
            gap: 35px;
        }

        .plan-card.accent {
            background-color: #004A53;
        }

        .badge {
            background-color: #CCDBDD;
            width: 51px;
            height: 51px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #004A53;
            font-size: 32px;
        }

        .plan-card.accent .badge {
            color: #004A53;
            background-color: #fff;
        }

        .plan-card-title {
            color: #004A53;
            font-size: 18px;
            font-weight: 700;
        }

        .plan-card-text {
            color: #004A53;
            font-size: 14px;
            line-height: 1.3;
        }

        .ellipsisBtn {
            color: #000000;
        }

        .plan-card.accent .ellipsisBtn {
            color: #fff;
        }

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
            cursor: default;
        }

        .status-pill.active {
            background-color: #198754;
            color: #fff;
        }

        .status-pill.inactive {
            background-color: #6c757d;
            color: #fff;
        }

        .plan-card-price {
            font-family: "Fredoka", sans-serif;
            color: #004A53;
            font-weight: 500;
        }

        .plan-card-priceL {
            font-size: 32px;
        }

        .plan-card-priceS {
            font-size: 16px;
        }

        .list-divider {
            background-color: #000000;
            height: 1px;
            width: 100%;
        }

        .list-title {
            font-size: 18px;
            color: #000F11;
            font-family: "Fredoka", sans-serif;
            font-weight: 300;
        }

        .list-item {
            font-size: 12px;
            color: #000F11;
            font-family: "Fredoka", sans-serif;
            font-weight: 300;
        }

        .plan-card.accent .plan-card-title,
        .plan-card.accent .plan-card-text,
        .plan-card.accent .plan-card-price,
        .plan-card.accent .list-item,
        .plan-card.accent .list-title {
            color: #fff;
        }

        .plan-card-btn {
            border: 1px solid #CCDBDD;
            padding: 10px 20px;
            border-radius: 52px;
            color: #000000;
            font-size: 12px;
        }

        .plan-card.accent .list-divider,
        .plan-card.accent .plan-card-btn {
            background-color: #fff;
        }
    </style>
    <main>
        {{-- add/edit subscription modal --}}
        <div class="modal fade" id="addSubscription" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column gap-1">
                            <h1 class="modal-title" style="color: #004A53;" id="modalTitle">Subscription</h1>
                            <p class="modal-subtitle">Kokokah Learning Management System Subscription</p>
                        </div>
                        <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-regular fa-circle-xmark"></i>
                        </button>
                    </div>

                    <form class="modal-form-container" id="subscriptionForm">
                        <div class="modal-form">
                            <input type="hidden" id="subscriptionId">
                            <div class="modal-form-input-border">
                                <label for="subscriptionTitle" class="modal-label">Subscription Title</label>
                                <input type="text" class="modal-input" id="subscriptionTitle"
                                    placeholder="e.g., Daily Plan" required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionDescription" class="modal-label">Subscription Description</label>
                                <textarea class="modal-input" id="subscriptionDescription" placeholder="e.g., Type...." required></textarea>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionPrice" class="modal-label">Subscription Price</label>
                                <input type="number" class="modal-input" id="subscriptionPrice" placeholder="e.g., 100"
                                    min="0" step="0.01" required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionDurationValue" class="modal-label">Duration Value</label>
                                <input type="number" class="modal-input" id="subscriptionDurationValue"
                                    placeholder="e.g., 30" min="1" required>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionDurationType" class="modal-label">Duration Type</label>
                                <select class="modal-input" id="subscriptionDurationType" required>
                                    <option value="">Select Duration Type</option>
                                    <option value="free">Free</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="quarterly">Quarterly (3 Months)</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="half_yearly">Half Yearly (6 Months)</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionFeatures" class="modal-label">Features (separate each item with a
                                    comma)</label>
                                <textarea class="modal-input" id="subscriptionFeatures" placeholder="Up to 500 students, Basic reporting....."></textarea>
                            </div>
                            <div class="modal-form-input-border">
                                <label for="subscriptionMaxUsers" class="modal-label">Max Users (Optional)</label>
                                <input type="number" class="modal-input" id="subscriptionMaxUsers" placeholder="e.g., 500"
                                    min="1">
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="subscriptionIsActive"
                                    checked>
                                <label class="modal-label " for="subscriptionIsActive">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn primaryBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-5">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-2">Subscription Plans</h1>

                    <p class="text-muted">Our plans are simple, Start Learning Today To Become The Best In Your Academics.
                    </p>
                </div>
                <div>
                    <button data-bs-toggle="modal" data-bs-target="#addSubscription" class="btn-accent-yellow">
                        <i class="fa-solid fa-plus me-2"></i> Add New Subscription
                    </button>
                </div>
            </header>
            <section class="stats mb-5">
                <div class="stats-container d-flex flex-column">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <h3 class="stats-title">Total Plans</h3>
                            <div><i class="fa-solid fa-chart-line"></i></div>
                        </div>
                        <div class="d-flex gap-1 align-items-center stats-value">
                            <h4 class="stats-value" id="totalPlans">0</h4>
                        </div>
                    </div>
                    <p>↗️ +2% this month</p>
                </div>
                <div class="stats-container d-flex flex-column">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3 justify-content-between">
                            <h3 class="stats-title">Active Subscription</h3>
                            <div><i class="fa-solid fa-naira-sign"></i></div>
                        </div>

                        <h4 class="stats-value" id="activePlans">0</h4>

                    </div>
                    <p>↗️ +2% this month</p>
                </div>
            </section>
            <section class="plan-container" id="plansContainer">
                <!-- Plans will be loaded here dynamically -->
                <article class="plan-card d-flex flex-column" style="display:none;">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">D</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical ellipsisBtn"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
                <article class="plan-card accent d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">W</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
                <article class="plan-card d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge">M</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">Daily Plan</h3>
                                <p class="plan-card-text">Access to class note, anytime, anywhere</p>
                                <div class="d-flex align-items-center gap-1 ">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL"><i
                                            class="fa-solid fa-naira-sign"></i>300</p>
                                    <p class="plan-card-price plan-card-priceS">/per day</p>
                                </div>
                            </div>
                        </div>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What’s Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Valid
                                for 24hrs</li>
                            <li class="list-item d-flex align-items-center gap-2"><i class="fa-solid fa-children"></i>
                                Access to the subject note</li>
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <button class="plan-card-btn align-self-center">Revenue: ₦100,000/mo </button>
                </article>
            </section>

        </div>
    </main>

    <script>
        // Use local variable to avoid conflict with global API_BASE_URL
        const SUBSCRIPTION_API_URL = '/api/subscriptions';
        let allPlans = [];
        let editingPlanId = null;

        // Get authentication token from localStorage
        function getAuthToken() {
            const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
            if (!token) {
                console.warn('No authentication token found. User may not be logged in.');
            }
            return token || '';
        }

        // Show confirmation modal
        async function showConfirmation(title, message, confirmText, cancelText) {
            if (window.confirmationModal) {
                return await confirmationModal.show(title, message, confirmText, cancelText);
            } else {
                return confirm(message);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add missing IDs to stats elements if they don't exist
            const paragraphs = document.querySelectorAll('.stats-container p');
            if (paragraphs.length >= 1 && !paragraphs[0].id) {
                paragraphs[0].id = 'plansChange';
            }
            if (paragraphs.length >= 2 && !paragraphs[1].id) {
                paragraphs[1].id = 'activePlansChange';
            }

            loadSubscriptionPlans();
            setupFormHandlers();
            setupModalHandlers();
        });

        // Load all subscription plans
        async function loadSubscriptionPlans() {
            try {
                const response = await fetch(`${SUBSCRIPTION_API_URL}/plans`, {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    allPlans = data.data.data || [];
                    renderPlans();
                    updateStats();
                } else {
                    showError('Failed to load subscription plans');
                }
                // Hide loader after loading plans
                if (window.kokokahLoader) {
                    window.kokokahLoader.hide();
                }
            } catch (error) {
                console.error('Error loading plans:', error);
                showError('Error loading subscription plans');
                // Hide loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.hide();
                }
            }
        }

        // Render subscription plans
        function renderPlans() {
            const container = document.getElementById('plansContainer');

            if (allPlans.length === 0) {
                container.innerHTML =
                    '<div class="text-center w-100"><p>No subscription plans found. Create one to get started!</p></div>';
                return;
            }

            container.innerHTML = allPlans.map((plan, index) => `
                <article class="plan-card ${index === 0 ? 'accent' : ''} d-flex flex-column">
                    <div class="d-flex gap-2 align-items-start justify-content-between">
                        <div class="d-flex align-items-start gap-2">
                            <div class="badge">${plan.title.charAt(0).toUpperCase()}</div>
                            <div class="d-flex flex-column gap-3">
                                <h3 class="plan-card-title">${plan.title}</h3>
                                <p class="plan-card-text">${plan.description || 'No description'}</p>
                                <div class="d-flex align-items-center gap-1">
                                    <p class="d-flex align-items-center plan-card-price plan-card-priceL">
                                        <i class="fa-solid fa-naira-sign"></i>${formatPrice(plan.price)}
                                    </p>
                                    <p class="plan-card-price plan-card-priceS">/${plan.duration_type}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical ellipsisBtn"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editPlan(${plan.id}); return false;">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deletePlan(${plan.id}); return false;">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <p class="list-title">What's Included?</p>
                        <ul class="d-flex flex-column gap-2 ps-0" style="list-style: none;">
                            ${renderFeatures(plan.features)}
                        </ul>
                        <div class="list-divider"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="status-pill ${plan.is_active ? 'active' : 'inactive'}">
                            ${plan.is_active ? '✓ Active' : '○ Inactive'}
                        </span>
                    </div>
                </article>
            `).join('');
        }

        // Render features list
        function renderFeatures(features) {
            if (!features || features.length === 0) {
                return '<li class="list-item">No features listed</li>';
            }

            const featureArray = Array.isArray(features) ? features : JSON.parse(features);
            return featureArray.map(feature => `
                <li class="list-item d-flex align-items-center gap-2">
                    <i class="fa-solid fa-check"></i> ${feature}
                </li>
            `).join('');
        }

        // Update statistics
        function updateStats() {
            const totalPlans = allPlans.length;
            const activePlans = allPlans.filter(p => p.is_active).length;

            // Update total plans
            const totalPlansEl = document.getElementById('totalPlans');
            if (totalPlansEl) {
                totalPlansEl.textContent = totalPlans;
            }

            // Update active plans
            const activePlansEl = document.getElementById('activePlans');
            if (activePlansEl) {
                activePlansEl.textContent = activePlans;
            }

            // Update plans change text
            const plansChangeEl = document.getElementById('plansChange');
            if (plansChangeEl) {
                plansChangeEl.textContent = `✓ ${activePlans} active`;
            }

            // Update active plans change text
            const activePlansChangeEl = document.getElementById('activePlansChange');
            if (activePlansChangeEl) {
                activePlansChangeEl.textContent =
                    `✓ ${totalPlans > 0 ? Math.round((activePlans/totalPlans)*100) : 0}% active`;
            }
        }

        // Setup form handlers
        function setupFormHandlers() {
            const form = document.getElementById('subscriptionForm');
            form.addEventListener('submit', handleFormSubmit);
        }

        // Setup modal handlers
        function setupModalHandlers() {
            const modal = document.getElementById('addSubscription');
            modal.addEventListener('hidden.bs.modal', resetForm);
        }

        // Handle form submission
        async function handleFormSubmit(e) {
            e.preventDefault();

            const formData = {
                title: document.getElementById('subscriptionTitle').value,
                description: document.getElementById('subscriptionDescription').value,
                price: parseFloat(document.getElementById('subscriptionPrice').value),
                duration: parseInt(document.getElementById('subscriptionDurationValue').value),
                duration_type: document.getElementById('subscriptionDurationType').value,
                features: document.getElementById('subscriptionFeatures').value
                    .split(',')
                    .map(f => f.trim())
                    .filter(f => f),
                is_active: document.getElementById('subscriptionIsActive').checked,
                max_users: document.getElementById('subscriptionMaxUsers').value ?
                    parseInt(document.getElementById('subscriptionMaxUsers').value) : null
            };

            try {
                // Show loader
                if (window.kokokahLoader) {
                    window.kokokahLoader.show();
                }

                let response;
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${getAuthToken()}`
                };

                if (editingPlanId) {
                    // Update existing plan
                    response = await fetch(`${SUBSCRIPTION_API_URL}/plans/${editingPlanId}`, {
                        method: 'PUT',
                        headers: headers,
                        body: JSON.stringify(formData)
                    });
                } else {
                    // Create new plan
                    response = await fetch(`${SUBSCRIPTION_API_URL}/plans`, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(formData)
                    });
                }

                if (!response.ok) {
                    const errorData = await response.json();
                    showError(errorData.message || `Error: ${response.status} ${response.statusText}`);
                    console.error('API Error:', response.status, errorData);
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    showSuccess(editingPlanId ? 'Plan updated successfully' : 'Plan created successfully');
                    resetForm();
                    bootstrap.Modal.getInstance(document.getElementById('addSubscription')).hide();
                    // Hide loader before reloading plans
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                    loadSubscriptionPlans();
                } else {
                    showError(data.message || 'Failed to save plan');
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                }
            } catch (error) {
                console.error('Error saving plan:', error);
                showError('Error saving subscription plan: ' + error.message);
                // Hide loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.hide();
                }
            }
        }

        // Edit plan
        async function editPlan(planId) {
            try {
                // Show loader
                if (window.kokokahLoader) {
                    window.kokokahLoader.show();
                }

                const response = await fetch(`${SUBSCRIPTION_API_URL}/plans/${planId}`, {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    showError(errorData.message || `Error: ${response.status} ${response.statusText}`);
                    console.error('API Error:', response.status, errorData);
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    const plan = data.data;
                    editingPlanId = planId;

                    // Populate form
                    document.getElementById('subscriptionId').value = plan.id;
                    document.getElementById('subscriptionTitle').value = plan.title;
                    document.getElementById('subscriptionDescription').value = plan.description || '';
                    document.getElementById('subscriptionPrice').value = plan.price;
                    document.getElementById('subscriptionDurationValue').value = plan.duration;
                    document.getElementById('subscriptionDurationType').value = plan.duration_type;
                    document.getElementById('subscriptionFeatures').value =
                        Array.isArray(plan.features) ? plan.features.join(', ') : plan.features || '';
                    document.getElementById('subscriptionMaxUsers').value = plan.max_users || '';
                    document.getElementById('subscriptionIsActive').checked = plan.is_active;

                    // Update modal title
                    document.getElementById('modalTitle').textContent = 'Edit Subscription Plan';

                    // Hide loader before showing modal
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }

                    // Show modal
                    new bootstrap.Modal(document.getElementById('addSubscription')).show();
                } else {
                    showError('Failed to load plan details');
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                }
            } catch (error) {
                console.error('Error loading plan:', error);
                showError('Error loading plan details');
                // Hide loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.hide();
                }
            }
        }

        // Delete plan
        async function deletePlan(planId) {
            const confirmed = await showConfirmation('Delete Plan',
                'Are you sure you want to delete this subscription plan? This action cannot be undone.', 'Delete',
                'Cancel');
            if (!confirmed) {
                return;
            }

            try {
                // Show loader
                if (window.kokokahLoader) {
                    window.kokokahLoader.show();
                }

                const response = await fetch(`${SUBSCRIPTION_API_URL}/plans/${planId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': `Bearer ${getAuthToken()}`
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    showError(errorData.message || `Error: ${response.status} ${response.statusText}`);
                    console.error('API Error:', response.status, errorData);
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    showSuccess('Plan deleted successfully');
                    // Hide loader before reloading plans
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                    loadSubscriptionPlans();
                } else {
                    showError(data.message || 'Failed to delete plan');
                    // Hide loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.hide();
                    }
                }
            } catch (error) {
                console.error('Error deleting plan:', error);
                showError('Error deleting subscription plan: ' + error.message);
                // Hide loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.hide();
                }
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('subscriptionForm').reset();
            document.getElementById('subscriptionId').value = '';
            editingPlanId = null;
            document.getElementById('modalTitle').textContent = 'Subscription';
        }

        // Format price
        function formatPrice(price) {
            return new Intl.NumberFormat('en-NG', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price);
        }

        // Show success message
        function showSuccess(message) {
            if (window.ToastNotification) {
                ToastNotification.success('Success', message, 3500);
            } else {
                alert(message);
            }
        }

        // Show error message
        function showError(message) {
            if (window.ToastNotification) {
                ToastNotification.error('Error', message, 5000);
            } else {
                alert('Error: ' + message);
            }
        }
    </script>
@endsection
