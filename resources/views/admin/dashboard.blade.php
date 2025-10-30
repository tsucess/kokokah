@extends('layouts.dashboardtemp')

  @section('content')
  <!-- Main -->
  <main>
    <div class="container">
     <div class = "row">
      <div class="d-flex justify-content-between">

        <div>
          <h4 class ="fw-bold">Welcome back, Samuel (Admin)</h4>
          <p class = "text-muted">Here overview of your </p>
        </div>


         <div class = "d-flex ms-auto">
          <button class="btn btn-nav-secondary me-3"><i class="fa-solid fa-plus me-2"></i> Add New Course</button>
          <button class="btn btn-nav-primary"><i class="fa-solid fa-plus me-2"></i> Create New User</button>
        </div>

      </div>
    </div>

      <!-- Stats -->
      <div class="stats-row">
        <div class="stat-card">
            <img src = "images/abc.png" class = "img-fluid" />
          {{-- <div class="stat-orb orb-users"><i class="fa-solid fa-users"></i></div> --}}
          <div class="stat-meta">
            <div class="label mt-2">Total Users</div>
            <div class = "mt-2">
            <p>
             <i class="fa-solid fa-square text-success"></i> science (50%)
             <i class="fa-solid fa-square text-warning"></i> Arts (50%)
            </p>
            </div>
            <div class="value">50</div>
          </div>
        </div>

        <div class="stat-card">
            <img src = "images/students.png" class = "img-fluid" />
        {{-- <div class="stat-orb orb-students"><i class="fa-solid fa-user-graduate"></i></div> --}}
                <div class="stat-meta">
            <div class="label mt-2">Students</div>
            <div class = "mt-2">
            <p>
             <i class="fa-solid fa-square text-success"></i> MALE (61%)
             <i class="fa-solid fa-square text-warning"></i> FEMALE (39%)
            </p>
            </div>
            <div class="value">308</div>
          </div>
        </div>

        <div class="stat-card">
            {{-- <div class="stat-orb orb-instructors"><i class="fa-solid fa-chalkboard-user"></i></div> --}}
            <img src = "images/instructor.png" class = "img-fluid" />
            <div class="stat-meta">
            <div class="label">Instructors</div>
            <div class = "mt-2">
            <p>
             <i class="fa-solid fa-square text-success"></i> MALE (55%)
             <i class="fa-solid fa-square text-warning"></i> FEMALE (45%)
            </p>
            </div>
            <div class="value">100</div>
          </div>
        </div>

        <div class="stat-card">
        <img src = "images/abc.png" class = "img-fluid" />
        {{-- <div class="stat-orb orb-courses"><i class="fa-solid fa-book-open"></i></div> --}}
          <div class="stat-meta">
            <div class="label">Active Courses</div>
            <div class = "mt-2">
            <p>
             <i class="fa-solid fa-square text-success"></i> science (50%)
             <i class="fa-solid fa-square text-warning"></i> Arts (50%)
            </p>
            </div>
            <div class="value">50</div>
          </div>
        </div>
      </div>


      <!-- Chart -->
      <div class = "container">
      <div class="chart-card">
        <div class="chart-header">
          <div class = "information1">
            <h6 class="fw-bold m-0">Income & Expense</h6>
            <p class="small text-muted">Performance overview</p>
          </div>
          <div class="d-flex align-items-center gap-3 information2">
            <div class="legend-dot"><span class="dot" style="background:var(--brand-green)"></span> Income</div>
            <div class="legend-dot"><span class="dot" style="background:var(--brand-yellow)"></span> Profit</div>
            <select class="form-select form-select-sm w-auto ms-2" style="border-radius:12px;">
              <option selected>Yearly</option>
              <option>Monthly</option>
            </select>
          </div>
        </div>

        <div style="height:320px;">
          <canvas id="ieChart"></canvas>
        </div>

      </div>

    </div>

      <!-- Recently Registered Users table (restored) -->
      <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            {{-- <div style="font-weight:700;">Recently Registered Users</div> --}}
            <h6 class = "registeredusers">Recently Registered Users</h6>
            <p class="small text-muted registeredusers" style="line-height: 1px;">Your awesome text goes here.</p>
          </div>
          <a href="#" class="small text-dark fw-semibold text-decoration-none">View all users</a>
        </div>

        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Role</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Winner Effiong Duff</td>
                <td>KOKOKAH-0001</td>
                <td>Teacher</td>
                <td>Male</td>
                <td>majorsignature@gmail.com</td>
                <td><span class="badge text-success" style = "background: #DCFCE7;"><i class="fa fa-circle  p-1 text-success" style = "font-size:10px;"></i>Active</span></td>
                {{-- <td><button class="btn border rounded-5 p-2  text-success" type = "button" style = "background: #DCFCE7;"><i class="fa fa-circle ps-2 pe-2 text-success" style = "font-size:10px;"></i>Active</span></td> --}}
              </tr>
              <tr>
                <td>Jane Doe</td>
                <td>KOKOKAH-0002</td>
                <td>Student</td>
                <td>Female</td>
                <td>jane@example.com</td>
                <td><span class="badge bg-warning text-dark"><i class="fa fa-circle  p-1 text-white" style = "font-size:10px;"></i>Pending</span></td>
              </tr>
              <tr>
                <td>John Smith</td>
                <td>KOKOKAH-0003</td>
                <td>Student</td>
                <td>Male</td>
                <td>john@example.com</td>
                <td><span class="badge bg-danger text-white"><i class="fa fa-circle  p-1 text-white" style = "font-size:10px;"></i>Inactive</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>



    </div>

  <!-- Chart.js (keep after body) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

  <script>
    // Chart (with callout bubble plugin)
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const incomeData = [68, 64, 80, 86, 92, 88, 70, 78, 82, 90, 84, 72];
    const profitData = [32, 40, 34, 30, 44, 52, 60, 58, 62, 60, 66, 22];
    const julyIndex = months.indexOf('Jul');

    const calloutPlugin = {
      id: 'callout',
      afterDatasetsDraw(chart) {
        const {ctx, chartArea:{top, left, right}} = chart;
        const meta = chart.getDatasetMeta(0);
        const point = meta.data[julyIndex];
        if(!point) return;

        const x = point.x;
        const y = point.y - 24;
        const text1 = '$77,000';
        const text2 = '09 Projects';

        ctx.save();
        ctx.font = '600 12px Inter, sans-serif';
        const w = Math.max(ctx.measureText(text1).width, ctx.measureText(text2).width) + 16;
        const h = 38;
        const r = 8;

        const bx = Math.min(Math.max(x - w/2, left + 6), right - w - 6);
        const by = Math.max(y - h, top + 6);

        ctx.fillStyle = '#FFFFFF';
        ctx.strokeStyle = '#E5EAF0';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(bx + r, by);
        ctx.arcTo(bx + w, by, bx + w, by + r, r);
        ctx.arcTo(bx + w, by + h, bx + w - r, by + h, r);
        ctx.arcTo(bx, by + h, bx, by + h - r, r);
        ctx.arcTo(bx, by, bx + r, by, r);
        ctx.closePath();
        ctx.fill(); ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(x - 6, by + h);
        ctx.lineTo(x, by + h + 8);
        ctx.lineTo(x + 6, by + h);
        ctx.closePath();
        ctx.fill(); ctx.stroke();

        ctx.fillStyle = '#0F1C24';
        ctx.fillText(text1, bx + 8, by + 16);
        ctx.fillStyle = '#6B737A';
        ctx.fillText(text2, bx + 8, by + 30);
        ctx.restore();
      }
    };

    const ctx = document.getElementById('ieChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [
          {
            label: 'Income',
            data: incomeData,
            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-green').trim(),
            pointRadius: 3,
            pointHoverRadius: 5,
            fill: false,
            tension: 0.45
          },
          {
            label: 'Profit',
            data: profitData,
            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-yellow').trim(),
            pointRadius: 3,
            pointHoverRadius: 5,
            fill: false,
            tension: 0.45
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { intersect: false, mode: 'index' }
        },
        scales: {
          y: {
            min: 0, max: 100, ticks: { stepSize: 10, callback: v => v + '%' },
            grid: { color: '#EFF3F6' }
          },
          x: { grid: { display: false } }
        },
        elements: { line: { borderWidth: 2 } }
      },
      plugins: [calloutPlugin]
    });
  </script>

</main>
  @endsection
