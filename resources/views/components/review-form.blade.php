<div class="review-form-container">
    <h3 class="review-form-title">Share Your Review</h3>
    <form id="reviewForm" class="d-flex flex-column gap-3" data-ajax data-no-loader>
        @csrf
        
        <!-- Rating Selection -->
        <div class="form-group">
            <label class="form-label">Rating</label>
            <div class="rating-selector d-flex gap-2">
                @for($i = 1; $i <= 5; $i++)
                    <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" class="rating-input" required>
                    <label for="rating{{ $i }}" class="rating-label">
                        <i class="fa-solid fa-star"></i>
                    </label>
                @endfor
            </div>
        </div>

        <!-- Review Title -->
        <div class="form-group">
            <label for="reviewTitle" class="form-label">Review Title</label>
            <input type="text" id="reviewTitle" name="title" class="form-control" placeholder="Summarize your experience" maxlength="255" required>
        </div>

        <!-- Review Comment -->
        <div class="form-group">
            <label for="reviewComment" class="form-label">Your Review</label>
            <textarea id="reviewComment" name="comment" class="form-control" placeholder="Share your detailed thoughts about this course..." rows="5" maxlength="1000" required></textarea>
            <small class="form-text text-muted">Max 1000 characters</small>
        </div>

        <!-- Pros (Optional) -->
        <div class="form-group">
            <label class="form-label">What did you like? (Optional)</label>
            <div id="prosList" class="d-flex flex-column gap-2">
                <input type="text" name="pros[]" class="form-control" placeholder="Add a positive aspect...">
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addProField()">+ Add Another</button>
        </div>

        <!-- Cons (Optional) -->
        <div class="form-group">
            <label class="form-label">What could be improved? (Optional)</label>
            <div id="consList" class="d-flex flex-column gap-2">
                <input type="text" name="cons[]" class="form-control" placeholder="Add an area for improvement...">
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addConField()">+ Add Another</button>
        </div>

        <!-- Submit Button -->
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary flex-fill">Submit Review</button>
            <button type="reset" class="btn btn-outline-secondary flex-fill">Clear</button>
        </div>
    </form>
</div>

<style>
    .review-form-container {
        background: #f9f9f9;
        padding: 24px;
        border-radius: 10px;
        max-width: 600px;
    }

    .review-form-title {
        font-size: 20px;
        font-weight: 600;
        color: #004A53;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .rating-selector {
        display: flex;
        gap: 12px;
    }

    .rating-input {
        display: none;
    }

    .rating-label {
        cursor: pointer;
        font-size: 24px;
        color: #e5e6e7;
        transition: color 0.2s;
    }

    .rating-input:checked ~ .rating-label,
    .rating-label:hover {
        color: #fdaf22;
    }

    .form-control {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #004A53;
        box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
    }

    .form-text {
        font-size: 12px;
        color: #999;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #004A53;
        color: white;
    }

    .btn-primary:hover {
        background-color: #003a41;
    }

    .btn-outline-secondary {
        border: 1px solid #ddd;
        color: #666;
    }

    .btn-outline-secondary:hover {
        background-color: #f5f5f5;
    }

    .btn-outline-primary {
        border: 1px solid #004A53;
        color: #004A53;
        background: white;
    }

    .btn-outline-primary:hover {
        background-color: #f0f8f9;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
</style>

<script>
    function addProField() {
        const prosList = document.getElementById('prosList');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'pros[]';
        input.className = 'form-control';
        input.placeholder = 'Add a positive aspect...';
        prosList.appendChild(input);
    }

    function addConField() {
        const consList = document.getElementById('consList');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'cons[]';
        input.className = 'form-control';
        input.placeholder = 'Add an area for improvement...';
        consList.appendChild(input);
    }

    document.getElementById('reviewForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const courseId = document.querySelector('[data-course-id]')?.getAttribute('data-course-id');
        if (!courseId) {
            showError('Course ID not found');
            return;
        }

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        const formData = new FormData(e.target);
        const data = {
            rating: formData.get('rating'),
            title: formData.get('title'),
            comment: formData.get('comment'),
            pros: formData.getAll('pros[]').filter(p => p.trim()),
            cons: formData.getAll('cons[]').filter(c => c.trim())
        };

        try {
            const response = await fetch(`/api/courses/${courseId}/reviews`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                showSuccess('Review submitted successfully! It will be reviewed before appearing.');
                e.target.reset();

                // Force hide the loader since this is an AJAX request
                if (window.kokokahLoader) {
                    window.kokokahLoader.forceHide();
                }

                // Dispatch custom event to reload reviews
                window.dispatchEvent(new Event('reviewSubmitted'));

                // Reload reviews after a short delay
                setTimeout(() => {
                    if (typeof loadCourseReviews === 'function') {
                        loadCourseReviews();
                    }
                }, 1000);
            } else {
                showError(result.message || 'Failed to submit review');

                // Force hide the loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.forceHide();
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Failed to submit review');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
</script>

