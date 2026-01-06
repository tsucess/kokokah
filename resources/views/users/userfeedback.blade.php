@extends('layouts.usertemplate')

@section('content')
<style>
        .header-title {
            font-size: 32px;
            color: #004A53;
            font-family: "Fredoka", sans-serif;
            font-weight: 600;
        }

        .header-subtitle {
            font-size: 18px;
            color: #1C1D1D;
        }

        .feature-container {
            max-width: 632px;
        }

        .feature-item-container {
            background-color: #F5F6F8;
            height: 200px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .feature-title {
            color: #1C1D1D;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }

        .feature-text {
            padding: 10px;
            color: #1C1D1D;
            font-size: 16px;
            text-align: center;
        }

        .share-feedback-container {
            border-radius: 20px;
            border: 1px solid #80A4A9;
            gap: 34px;
            padding: 20px;
            max-width: 977px;
        }

        .share-feedback-btn {
            background-color: #FDAF22;
            color: #000F11;
            font-size: 16px;
            width: 341px;
            height: 60px;
            border: none;
            font-weight: 600;
        }

        .share-feedback-title {
            font-size: 20px;
            font-weight: 600;
            color: #004A53;
            font-family: "Fredoka", sans-serif;
        }

        .input-area {
            border: 1.5px solid #004A53;
            border-radius: 10px;
            height: 62px;
            padding: 0px 24px 27px;
            margin-top: 12px;
        }

        .label {
            color: #004A53;
            font-size: 14px;
            position: relative;
            top: -12px;
            background-color: #F5F6F8;
            align-self: flex-start;
            font-weight: 500;
            padding-inline: 2px;
        }

        .input {
            border: none;
            outline: none;
            color: #8E8E93;
            font-size: 14px;
            background-color: transparent;
        }

        select {
            font-size: 14px;
            border: none;
            outline: none;
            color: #AEBACA;
            background-color: transparent;
        }

        .rate-title {
            color: #004A53;
            font-size: 14px;
        }

        .textarea-area {
            border: 1.5px solid #004A53;
            border-radius: 10px;
            height: 167px;
            padding: 0px 24px 27px;
        }

        .star {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star.active {
            color: #FDAF22;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            color: #28a745;
            font-size: 14px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }

        .loading-spinner.show {
            display: block;
        }
    </style>


<main>
    <section class="container-fluid d-flex flex-column gap-4 px-4 py-5">
        <header class="d-flex flex-column gap-2">
            <h3 class="header-title">We Value Your Feedback</h3>
            <p class="header-subtitle">Your input helps us build products and experiences. Whether you’ve found a bug, have a feature request, or just want to share your thoughts, we’re here to listen.</p>
        </header>
        <section class="container feature-container">
            <div class="row gx-5 gy-4">
                <div class="col col-12 col-lg-6  ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-solid fa-bug fa-xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">Report Bugs</h4>
                            <p class="feature-text">Found something broken? Let us know so we can fix it quickly.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-lightbulb fa-xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">Request Features</h4>
                            <p class="feature-text">Have an idea for improvement? Share your suggestions with us.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-comment fa-xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">General Feedback</h4>
                            <p class="feature-text">Share your thoughts on how we can make things better for you.</p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 ">
                    <div class="feature-item-container d-flex flex-column gap-4 align-items-center justify-content-center">
                        <div><i class="fa-regular fa-heart fa-xl" style="color: #000000;"></i></div>
                        <div class="d-flex flex-column gap-1">
                            <h4 class="feature-title">We Listen</h4>
                            <p class="feature-text">Every piece of feedback help us create a better product and experience for you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid d-flex flex-column share-feedback-container">
            <div class="d-flex flex-column gap-4">
                <header class="d-flex flex-column gap-1">
                    <h5 class="share-feedback-title">Share Your Feedback</h5>
                    <p class="header-subtitle">Help us improve by sharing your thoughts, reporting bugs, or suggesting new features.</p>
                </header>

                <div id="successMessage" class="success-message" style="display: none;"></div>

                <form id="feedbackForm" class="d-flex flex-column gap-4">
                    @csrf

                    <div class="d-flex flex-column input-area">
                        <label for="firstName" class="label">Enter Full Name *</label>
                        <input type="text" name="first_name" id="firstName" placeholder="Winner" class="input" required>
                        <span class="error-message" id="firstNameError"></span>
                    </div>

                    <div class="d-flex flex-column input-area">
                        <label for="lastName" class="label">Enter Email Address *</label>
                        <input type="text" name="last_name" id="lastName" placeholder="Effiong" class="input" required>
                        <span class="error-message" id="lastNameError"></span>
                    </div>

                    <div class="d-flex flex-column input-area">
                        <label for="feedbackType" class="label">Select Feedback Type *</label>
                        <select name="feedback_type" id="feedbackType" required>
                            <option value="">-- Select Type --</option>
                            <option value="bug">Report Bugs</option>
                            <option value="feature_request">Request Features</option>
                            <option value="general">General Feedback</option>
                            <option value="other">Other</option>
                        </select>
                        <span class="error-message" id="feedbackTypeError"></span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <h6 class="rate-title">How would you rate your overall experience?</h6>
                        <div class="d-flex align-items-center gap-2" id="ratingStars">
                            <i class="fa-solid fa-star fa-lg star" data-rating="1" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg star" data-rating="2" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg star" data-rating="3" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg star" data-rating="4" style="color: #E5E6E7;"></i>
                            <i class="fa-solid fa-star fa-lg star" data-rating="5" style="color: #E5E6E7;"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="">
                    </div>

                    <div class="d-flex flex-column input-area">
                        <label for="subject" class="label">Subject</label>
                        <input type="text" name="subject" id="subject" placeholder="Brief summary of your feedback" class="input">
                        <span class="error-message" id="subjectError"></span>
                    </div>

                    <div class="d-flex flex-column textarea-area">
                        <label for="message" class="label">Message *</label>
                        <textarea name="message" id="message" class="input" placeholder="Please provide detailed information about your feedback......" required></textarea>
                        <span class="error-message" id="messageError"></span>
                    </div>
                </form>
            </div>

            <div class="loading-spinner" id="loadingSpinner">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Submitting your feedback...</p>
            </div>

            <button type="submit" form="feedbackForm" class="align-self-center share-feedback-btn" id="submitBtn">Submit Feedback</button>
        </section>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedbackForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const successMessage = document.getElementById('successMessage');
    const ratingStars = document.querySelectorAll('#ratingStars .star');
    const ratingInput = document.getElementById('ratingInput');

    // Star rating functionality
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;

            // Update star colors
            ratingStars.forEach(s => {
                if (s.getAttribute('data-rating') <= rating) {
                    s.classList.add('active');
                    s.style.color = '#FDAF22';
                } else {
                    s.classList.remove('active');
                    s.style.color = '#E5E6E7';
                }
            });
        });

        // Hover effect
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            ratingStars.forEach(s => {
                if (s.getAttribute('data-rating') <= rating) {
                    s.style.color = '#FDAF22';
                } else {
                    s.style.color = '#E5E6E7';
                }
            });
        });
    });

    // Reset hover effect
    document.getElementById('ratingStars').addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        ratingStars.forEach(s => {
            if (currentRating && s.getAttribute('data-rating') <= currentRating) {
                s.style.color = '#FDAF22';
            } else {
                s.style.color = '#E5E6E7';
            }
        });
    });

    // Form submission
    feedbackForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        successMessage.style.display = 'none';

        // Show loading spinner
        loadingSpinner.classList.add('show');
        submitBtn.disabled = true;

        try {
            const formData = new FormData(feedbackForm);
            const data = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                feedback_type: formData.get('feedback_type'),
                rating: formData.get('rating') || null,
                subject: formData.get('subject'),
                message: formData.get('message'),
            };

            const response = await fetch('/api/feedback/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Show success message
                successMessage.textContent = result.message;
                successMessage.style.display = 'block';

                // Reset form
                feedbackForm.reset();
                ratingInput.value = '';
                ratingStars.forEach(s => {
                    s.classList.remove('active');
                    s.style.color = '#E5E6E7';
                });

                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth' });

                // Hide success message after 5 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            } else {
                // Handle validation errors
                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        const errorElement = document.getElementById(field + 'Error');
                        if (errorElement) {
                            errorElement.textContent = result.errors[field][0];
                        }
                    });
                } else {
                    alert(result.message || 'An error occurred while submitting your feedback.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while submitting your feedback. Please try again.');
        } finally {
            // Hide loading spinner
            loadingSpinner.classList.remove('show');
            submitBtn.disabled = false;
        }
    });
});
</script>
@endsection
