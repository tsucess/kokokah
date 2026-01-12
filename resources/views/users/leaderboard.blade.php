@extends('layouts.usertemplate')

@section('content')

<script>
    // Global variables
    let allLeaderboardData = [];
    let currentPage = 1;
    let totalPages = 1;
    let currentPeriod = 'all_time';
    let itemsPerPage = 10;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadLeaderboard();
        setupEventListeners();
    });

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Period filter
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.addEventListener('change', function() {
                currentPeriod = this.value;
                currentPage = 1;
                loadLeaderboard();
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterLeaderboard(this.value);
            });
        }

        // Pagination buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    displayLeaderboard();
                }
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayLeaderboard();
                }
            });
        }
    }

    /**
     * Load leaderboard data from API
     */
    async function loadLeaderboard() {
        try {
            console.log('Loading leaderboard for period:', currentPeriod);
            const response = await window.BadgeApiClient.getLeaderboard(currentPeriod);

            if (response.success && response.data) {
                const leaderboardData = response.data.leaderboard || response.data;
                allLeaderboardData = Array.isArray(leaderboardData) ? leaderboardData : [];

                console.log('Leaderboard data loaded:', allLeaderboardData);

                // Calculate pagination
                totalPages = Math.ceil(allLeaderboardData.length / itemsPerPage);
                currentPage = 1;

                // Display top 3 winners
                displayTopWinners();

                // Display full leaderboard table
                displayLeaderboard();
            } else {
                console.error('Failed to load leaderboard:', response.message);
                showError('Failed to load leaderboard data');
            }
        } catch (error) {
            console.error('Error loading leaderboard:', error);
            showError('Error loading leaderboard. Please try again.');
        }
    }

    /**
     * Display top 3 winners
     */
    function displayTopWinners() {
        const topWinners = allLeaderboardData.slice(0, 3);

        // Second place (index 1)
        if (topWinners[1]) {
            updateWinnerDisplay(1, topWinners[1]);
        }

        // First place (index 0)
        if (topWinners[0]) {
            updateWinnerDisplay(0, topWinners[0]);
        }

        // Third place (index 2)
        if (topWinners[2]) {
            updateWinnerDisplay(2, topWinners[2]);
        }
    }

    /**
     * Update winner display
     */
    function updateWinnerDisplay(position, winner) {
        const positions = {
            0: { containerClass: 'leaderboard-first-platform', nameSelector: 'leaderboard-winner-name' },
            1: { containerClass: 'leaderboard-second-platform', nameSelector: 'leaderboard-winner-name' },
            2: { containerClass: 'leaderboard-third-platform', nameSelector: 'leaderboard-winner-name' }
        };

        const posConfig = positions[position];
        if (!posConfig) return;

        // Find the winner container for this position
        const containers = document.querySelectorAll('.leaderboard-stats-container > div');
        if (containers[position]) {
            const nameElement = containers[position].querySelector('.leaderboard-winner-name');
            const imgElement = containers[position].querySelector('.leaderboard-winner-img');

            if (nameElement) {
                nameElement.textContent = `${winner.first_name} ${winner.last_name}`;
            }

            if (imgElement && winner.profile_photo) {
                imgElement.src = winner.profile_photo;
                imgElement.onerror = function() {
                    this.src = './images/little-winner.jpg';
                };
            }
        }
    }

    /**
     * Display leaderboard table
     */
    function displayLeaderboard() {
        const tableBody = document.getElementById('leaderboardTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        if (allLeaderboardData.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No leaderboard data available
                    </td>
                </tr>
            `;
            return;
        }

        // Get items for current page
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageItems = allLeaderboardData.slice(startIndex, endIndex);

        pageItems.forEach((user, index) => {
            const rank = startIndex + index + 1;
            const row = document.createElement('tr');

            const profilePhoto = user.profile_photo || './images/winner.png';
            const firstName = user.first_name || 'Unknown';
            const lastName = user.last_name || 'User';
            const points = user.total_points || 0;
            const badgesCount = user.badges_count || 0;

            row.innerHTML = `
                <td scope="row" class="align-middle rank-text">${String(rank).padStart(2, '0')}</td>
                <td class="d-flex gap-2 align-items-center">
                    <img src="${profilePhoto}" alt="${firstName}" class="avatar" onerror="this.src='./images/winner.png'">
                    <span class="table-data-text">${firstName} ${lastName}</span>
                </td>
                <td class="align-middle table-data-text">${badgesCount} Badge${badgesCount !== 1 ? 's' : ''}</td>
                <td class="align-middle">
                    <i class="fa-solid fa-star" style="color: #FDAF22;"></i>
                    <span class="table-data-text">${points}</span>
                </td>
                <td class="align-middle table-data-text">
                    ${getLevelBadge(points)}
                </td>
                <td class="align-middle">
                    <img src="./images/badge-icon.png" alt="badge" class="img-badge" title="${badgesCount} badges earned">
                </td>
            `;

            tableBody.appendChild(row);
        });

        updatePaginationInfo();
    }

    /**
     * Get level badge based on points
     */
    function getLevelBadge(points) {
        if (points >= 1000) return 'Expert';
        if (points >= 500) return 'Advanced';
        if (points >= 100) return 'Intermediate';
        return 'Amateur';
    }

    /**
     * Filter leaderboard by search term
     */
    function filterLeaderboard(searchTerm) {
        if (!searchTerm.trim()) {
            displayLeaderboard();
            return;
        }

        const filtered = allLeaderboardData.filter(user => {
            const fullName = `${user.first_name} ${user.last_name}`.toLowerCase();
            return fullName.includes(searchTerm.toLowerCase());
        });

        // Display filtered results
        const tableBody = document.getElementById('leaderboardTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        if (filtered.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No results found for "${searchTerm}"
                    </td>
                </tr>
            `;
            return;
        }

        filtered.forEach((user, index) => {
            const rank = allLeaderboardData.indexOf(user) + 1;
            const row = document.createElement('tr');

            const profilePhoto = user.profile_photo || './images/winner.png';
            const firstName = user.first_name || 'Unknown';
            const lastName = user.last_name || 'User';
            const points = user.total_points || 0;
            const badgesCount = user.badges_count || 0;

            row.innerHTML = `
                <td scope="row" class="align-middle rank-text">${String(rank).padStart(2, '0')}</td>
                <td class="d-flex gap-2 align-items-center">
                    <img src="${profilePhoto}" alt="${firstName}" class="avatar" onerror="this.src='./images/winner.png'">
                    <span class="table-data-text">${firstName} ${lastName}</span>
                </td>
                <td class="align-middle table-data-text">${badgesCount} Badge${badgesCount !== 1 ? 's' : ''}</td>
                <td class="align-middle">
                    <i class="fa-solid fa-star" style="color: #FDAF22;"></i>
                    <span class="table-data-text">${points}</span>
                </td>
                <td class="align-middle table-data-text">
                    ${getLevelBadge(points)}
                </td>
                <td class="align-middle">
                    <img src="./images/badge-icon.png" alt="badge" class="img-badge" title="${badgesCount} badges earned">
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    /**
     * Update pagination info
     */
    function updatePaginationInfo() {
        const pageCountElement = document.getElementById('pageCount');
        if (pageCountElement) {
            pageCountElement.textContent = `Page ${currentPage} of ${totalPages}`;
        }

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (prevBtn) {
            prevBtn.disabled = currentPage === 1;
        }
        if (nextBtn) {
            nextBtn.disabled = currentPage === totalPages;
        }
    }

    /**
     * Show error message
     */
    function showError(message) {
        console.error(message);
        // You can add toast notification here if available
    }

    // Expose functions to global scope if needed
    window.loadLeaderboard = loadLeaderboard;
    window.filterLeaderboard = filterLeaderboard;
</script>

<style>
        .leaderboard-title {
            color: #004A53;
            font-size: 32px;
            font-weight: 600;
            font-family: "Fredoka", sans-serif;
        }

        .leaderboard-img {
            width: 40px;
            height: 40px;
        }

        .select {
            background-color: #F5F4F9;
            border-radius: 6px;
            border: none;
            width: 107px;
            height: 27px;
            font-size: 12px;
            color: #777777;
            padding-inline: 4px;
        }

        .filter-btn {
            width: 24px;
            height: 24px;
            border: none;
            outline: none;
            background-color: transparent;
        }

        .search-container {
            background-color: #F5F4F9;
            border-radius: 42px;
            max-width: 238px;
            height: 30px;
            gap: 4px;
            padding-inline: 5px;
        }

        .search-container input {
            border: none;
            outline: none;
            font-size: 12px;
            color: #8E8D93;
            background-color: transparent;
        }

        .leaderboard-container {
            background-color: #CCDBDD;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            height: 343px;
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
            padding-inline: 10px;
        }

        .leaderboard-stats-container {
            max-width: 960px;
            height: 317px;
            margin-top: auto;
            width: 100%;
        }

        .leaderboard-items-container {
            gap: 14px;
        }

        .leaderboard-winner-container {
            gap: 8px;
        }

        .leaderboard-winner-img {
            width: 50px;
            height: 50px;
            border: 1px solid #000000;
            border-radius: 50%;
            object-position: top;
            object-fit: cover;
        }

        .leaderboard-winner-name {
            font-size: 12px;
            color: #004A53;
        }

        .leaderboard-winner-track {
            height: 24px;
            width: 100%;
            border: 1px solid #000000;
            border-radius: 20px;
        }



        .leaderboard-second-platform {
            background-color: #F56824;
            height: 152px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-second-img {
            width: 70px;
            height: 70px;
        }

        .leaderboard-first-platform {
            background-color: #004A53;
            height: 199px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-first-img {
            width: 100px;
            height: 100px;
        }

        .leaderboard-third-platform {
            background-color: #FDAF22;
            height: 122px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .leaderboard-third-img {
            width: 50px;
            height: 50px;
        }

        @media screen and (max-width:768px){
            .leaderboard-second-img {
            width: 40px;
            height: 40px;
        }
            .leaderboard-first-img {
            width: 60px;
            height: 60px;
        }
            .leaderboard-third-img {
            width: 30px;
            height: 30px;
        }
        .leaderboard-winner-track{
            height: 14px;
        }
        }

        .table-footer-container {
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0, .25);
            border-bottom-right-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .leaderboard-table-container {
            margin: 30px 0px 24px;
        }

        table img.avatar {
            width: 41px;
            height: 41px;
            object-fit: fill;
            border-radius: 50%;
            object-position: center;
        }

        table img.img-badge {
            width: 30px;
            height: 30px;
        }

        table thead th {
            color: #000000;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .table-data-text {
            color: #004A53;
            font-size: 14px;
        }

        table.leaderboard-table-container td {
            color: #004A53;
            font-size: 14px;
            white-space: nowrap;
        }

        table.leaderboard-table-container td.rank-text {
            font-size: 14px;
            color: #A0A0A0;
            white-space: nowrap;
        }

        .footer-btn {
            border: 1px solid #DEDEDE;
            border-radius: 6px;
            width: 62px;
            height: 21px;
            font-size: 10px;
            color: #404040;
            font-weight: 500;
            background-color: transparent;
        }

        .footer-pagecount {
            color: #CBCCCD;
            font-size: 12.9px;
        }

        @media (max-width: 576px) {
    .table-scroll::after {
        content: "← Scroll →";
        display: block;
        text-align: center;
        font-size: 12px;
        color: #888;
        margin-top: 6px;
    }
}

    </style>
    <main>
    <section class="container-fluid py-4 px-3 px-lg-5">
        <section class="d-flex flex-column gap-4">
            <header class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start gap-3">
                <div class="d-flex gap-1 align-items-center">
                    <img src="./images/leaderboard-icon.png" alt="" class="leaderboard-img">
                    <h2 class="leaderboard-title">Leaderboard</h2>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="d-flex align-items-center search-container shadow-sm">
                            <i class="fa-solid fa-magnifying-glass fa-2xs" style="color: #8E8D93;"></i>
                            <input type="search" id="searchInput" placeholder="Search by name">
                        </div>
                    </div>
                    <select id="periodSelect" class="select shadow-sm">
                        <option value="all_time">All Time</option>
                        <option value="this_month">This Month</option>
                        <option value="this_year">This Year</option>
                    </select>
                </div>
            </header>
            <section class="d-flex justify-content-center leaderboard-container">
                <article class="d-flex align-items-end leaderboard-stats-container gap-2">
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-second-platform">
                            <img src="./images/medal-second-place.png" alt="" class="leaderboard-second-img">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-first-platform">
                            <img src="./images/medal-first-place.png" alt="" class="leaderboard-first-img">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 w-100">
                        <div class="leaderboard-winner-container">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <img src="./images/little-winner.jpg" alt="" class="leaderboard-winner-img">
                                <h4 class="leaderboard-winner-name">Winner Effiong</h4>
                            </div>
                            <div class="leaderboard-winner-track"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center leaderboard-third-platform">
                            <img src="./images/medal-third-place.png" alt="" class="leaderboard-third-img">
                        </div>
                    </div>
                </article>

            </section>
        </section>
        <section class="table-footer-container">
            <div class="table-responsive-sm">
            <table class="table leaderboard-table-container">
                <thead>
                    <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Badges</th>
                        <th scope="col">Points</th>
                        <th scope="col">Level</th>
                        <th scope="col">Badge</th>
                    </tr>
                </thead>
                <tbody id="leaderboardTableBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-spinner fa-spin me-2"></i>Loading leaderboard...
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="d-flex gap-3 align-items-center justify-content-between">
                <button id="prevBtn" class="footer-btn">Previous</button>
                <p id="pageCount" class="footer-pagecount">Page 1 of 1</p>
                <button id="nextBtn" class="footer-btn">Next</button>
            </div>

        </section>

    </section>
    </main>
@endsection
