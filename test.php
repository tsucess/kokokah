    {{-- <script>
            // Sample data
            const terms = [{
                    id: 1,
                    name: 'First Term'
                },
                {
                    id: 2,
                    name: 'Second Term'
                },
                {
                    id: 3,
                    name: 'Third Term'
                }
            ];

            let currentEditId = null;
            let currentDeleteId = null;

            // Format date for display
            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Render terms
            function renderTerms() {
                const grid = document.getElementById('termsGrid');
                grid.innerHTML = terms.map(term => `
                <div class="term-card">
                    <div class="term-card-header">
                        <h3 class="term-card-title">${term.name}</h3>
                        <div class="term-card-actions">
                            <button class="action-btn" onclick="editTerm(${term.id})" title="Edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteTerm(${term.id})" title="Delete">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </div>

                </div>
            `).join('');
            }

            // Add/Edit term
            document.getElementById('termForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const name = document.getElementById('termName').value;

                if (currentEditId) {
                    const term = terms.find(t => t.id === currentEditId);
                    if (term) {
                        term.name = name;
                    }
                    currentEditId = null;
                } else {
                    terms.push({
                        id: Math.max(...terms.map(t => t.id), 0) + 1,
                        name,
                    });
                }

                renderTerms();
                document.getElementById('termForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addTermModal')).hide();
            });

            // Edit term
            function editTerm(id) {
                const term = terms.find(t => t.id === id);
                if (term) {
                    currentEditId = id;
                    document.getElementById('termName').value = term.name;
                    document.getElementById('modalTitle').textContent = 'Edit Term';
                    new bootstrap.Modal(document.getElementById('addTermModal')).show();
                }
            }

            // Delete term
            function deleteTerm(id) {
                currentDeleteId = id;
                new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
            }

            document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                const index = terms.findIndex(t => t.id === currentDeleteId);
                if (index > -1) {
                    terms.splice(index, 1);
                    renderTerms();
                }
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            });

            // Reset modal on close
            document.getElementById('addTermModal').addEventListener('hidden.bs.modal', () => {
                currentEditId = null;
                document.getElementById('modalTitle').textContent = 'Add Term';
                document.getElementById('termForm').reset();
            });

            // Initial render
            renderTerms();
        </script> --}}

        {{-- <script>
            let terms = [];
            const API_URL = "/api/term";

            // Get auth token from localStorage
            const token = localStorage.getItem('auth_token');

            let currentEditId = null;
            let currentDeleteId = null;

            // Load all terms from API (GET)
            async function loadTerms() {
                try {
                    const res = await fetch(API_URL, {
                        method: "GET",
                        headers: {
                            "Accept": "application/json",
                            "Authorization": `Bearer ${token}`
                        }
                    });

                    if (!res.ok) throw new Error("Failed to fetch terms");

                    terms = await res.json();
                    renderTerms();

                } catch (error) {
                    console.error("Failed to load terms:", error);
                }
            }

            // Render terms in UI
            function renderTerms() {
                const grid = document.getElementById('termsGrid');
                grid.innerHTML = terms.map(term => `
            <div class="term-card">
                <div class="term-card-header">
                    <h3 class="term-card-title">${term.name}</h3>
                    <div class="term-card-actions">
                        <button class="action-btn" onclick="editTerm(${term.id})" title="Edit">
                            <i class="fa-solid fa-pen fa-xs"></i>
                        </button>
                        <button class="action-btn delete" onclick="openDeleteModal(${term.id})" title="Delete">
                            <i class="fa-solid fa-trash fa-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            `).join('');
            }

            // Create new term (POST)
            async function createTerm(name) {
                const res = await fetch(API_URL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        name
                    })
                });

                if (res.ok) {
                    const newTerm = await res.json();
                    terms.push(newTerm);
                    renderTerms();
                } else {
                    console.error("Failed to create term");
                }
            }

            // Update term (PUT)
            async function updateTerm(id, name) {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        name
                    })
                });

                if (res.ok) {
                    const updated = await res.json();
                    const index = terms.findIndex(t => t.id === id);
                    terms[index] = updated;
                    renderTerms();
                } else {
                    console.error("Failed to update term");
                }
            }

            // Delete term (DELETE)
            async function deleteTerm(id) {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Accept": "application/json",
                        "Authorization": `Bearer ${token}`
                    }
                });

                if (res.ok) {
                    terms = terms.filter(t => t.id !== id);
                    renderTerms();
                } else {
                    console.error("Failed to delete term");
                }
            }

            // Handle form submit (Add/Edit)
            document.getElementById('termForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const name = document.getElementById('termName').value;

                if (currentEditId) {
                    await updateTerm(currentEditId, name);
                    currentEditId = null;
                } else {
                    await createTerm(name);
                }

                document.getElementById('termForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addTermModal')).hide();
            });

            // Edit term modal
            function editTerm(id) {
                const term = terms.find(t => t.id === id);
                if (term) {
                    currentEditId = id;
                    document.getElementById('termName').value = term.name;
                    document.getElementById('modalTitle').textContent = 'Edit Term';
                    new bootstrap.Modal(document.getElementById('addTermModal')).show();
                }
            }

            // Open delete modal
            function openDeleteModal(id) {
                currentDeleteId = id;
                new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
            }

            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
                if (currentDeleteId) {
                    await deleteTerm(currentDeleteId);
                    currentDeleteId = null;
                }
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
            });

            // Reset modal when closed
            document.getElementById('addTermModal').addEventListener('hidden.bs.modal', () => {
                currentEditId = null;
                document.getElementById('modalTitle').textContent = 'Add Term';
                document.getElementById('termForm').reset();
            });

            // Initial load of terms
            loadTerms();
        </script> --}}