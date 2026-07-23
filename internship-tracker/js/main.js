/**
 * Digital Internship Tracking System - Frontend Interactivity
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Hide Preloader
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', function () {
            preloader.classList.add('fade-out');
        });
        // Safety timeout
        setTimeout(function () {
            preloader.classList.add('fade-out');
        }, 1500);
    }

    // 2. Bootstrap Form Validation
    const validationForms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(validationForms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // 3. Search and Filter Internships (Student Dashboard / List)
    const searchInput = document.getElementById('searchInternship');
    const statusFilter = document.getElementById('filterStatus');
    const internshipItems = document.querySelectorAll('.internship-row'); // rows or cards with this class

    function filterInternships() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const statusVal = statusFilter ? statusFilter.value : '';

        internshipItems.forEach(function (item) {
            const company = item.querySelector('.company-name') ? item.querySelector('.company-name').textContent.toLowerCase() : '';
            const role = item.querySelector('.internship-role') ? item.querySelector('.internship-role').textContent.toLowerCase() : '';
            const status = item.dataset.status || '';

            const matchesSearch = company.includes(query) || role.includes(query);
            const matchesStatus = statusVal === '' || status === statusVal;

            if (matchesSearch && matchesStatus) {
                item.style.setProperty('display', '', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterInternships);
    if (statusFilter) statusFilter.addEventListener('change', filterInternships);


    // 4. Search and Filter Students (Admin Dashboard)
    const searchStudentInput = document.getElementById('searchStudent');
    const deptFilter = document.getElementById('filterDept');
    const studentRows = document.querySelectorAll('.student-row');

    function filterStudents() {
        const query = searchStudentInput ? searchStudentInput.value.toLowerCase().trim() : '';
        const deptVal = deptFilter ? deptFilter.value : '';

        studentRows.forEach(function (row) {
            const name = row.querySelector('.student-name') ? row.querySelector('.student-name').textContent.toLowerCase() : '';
            const roll = row.querySelector('.student-roll') ? row.querySelector('.student-roll').textContent.toLowerCase() : '';
            const dept = row.dataset.dept || '';

            const matchesSearch = name.includes(query) || roll.includes(query);
            const matchesDept = deptVal === '' || dept === deptVal;

            if (matchesSearch && matchesDept) {
                row.style.setProperty('display', '', 'important');
            } else {
                row.style.setProperty('display', 'none', 'important');
            }
        });
    }

    if (searchStudentInput) searchStudentInput.addEventListener('input', filterStudents);
    if (deptFilter) deptFilter.addEventListener('change', filterStudents);


    // 5. Drag and Drop File Upload
    const dropzone = document.getElementById('uploadDropzone');
    const fileInput = document.getElementById('certificateFile');
    const previewContainer = document.getElementById('previewContainer');
    const previewText = document.getElementById('previewText');

    if (dropzone && fileInput) {
        // Trigger click on file input
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag events
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            }, false);
        });

        // Drop file
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                handleFilePreview(files[0]);
            }
        }, false);

        // Selected file change
        fileInput.addEventListener('change', function () {
            if (this.files.length) {
                handleFilePreview(this.files[0]);
            }
        });
    }

    function handleFilePreview(file) {
        if (!previewContainer) return;

        // Show loading status
        previewContainer.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
        if (previewText) {
            previewText.textContent = `Selected: ${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
        }

        const reader = new FileReader();

        if (file.type.startsWith('image/')) {
            reader.onload = function (e) {
                previewContainer.innerHTML = `<img src="${e.target.result}" alt="Certificate Preview">`;
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            const blobUrl = URL.createObjectURL(file);
            previewContainer.innerHTML = `<iframe src="${blobUrl}#toolbar=0" width="100%" height="100%"></iframe>`;
        } else {
            previewContainer.innerHTML = `
                <div class="text-center p-4">
                    <i class="fa-regular fa-file-excel text-muted display-4 mb-2"></i>
                    <p class="text-muted mb-0">Preview not available for ${file.type || 'this file type'}.</p>
                </div>`;
        }
    }
});

/**
 * 6. Initialize Chart.js Doughnut for Internship Statuses
 */
function initStatusChart(canvasId, stats) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    // Check if stats are empty
    const total = Object.values(stats).reduce((a, b) => a + b, 0);
    if (total === 0) {
        ctx.parentElement.innerHTML = `
            <div class="h-100 d-flex flex-column align-items-center justify-content-center py-5">
                <i class="fa-solid fa-chart-pie text-muted display-4 mb-2"></i>
                <p class="text-muted">No data available to display chart.</p>
            </div>`;
        return;
    }

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Applied', 'Shortlisted', 'Interview', 'Selected', 'Ongoing', 'Completed'],
            datasets: [{
                data: [
                    stats.applied || 0,
                    stats.shortlisted || 0,
                    stats.interview || 0,
                    stats.selected || 0,
                    stats.ongoing || 0,
                    stats.completed || 0
                ],
                backgroundColor: [
                    '#64748b', // Applied (slate)
                    '#06b6d4', // Shortlisted (cyan)
                    '#f59e0b', // Interview (amber)
                    '#3b82f6', // Selected (blue)
                    '#10b981', // Ongoing (emerald)
                    '#6366f1'  // Completed (indigo)
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: 'Inter',
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return ` ${context.label}: ${context.raw} (${((context.raw / total) * 100).toFixed(0)}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
}

/**
 * 7. Export Table to CSV
 */
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        // Skip hidden rows (filtered out items)
        if (rows[i].style.display === 'none') continue;
        
        let row = [];
        // Get td and th elements, excluding last action column if applicable
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Clean content: remove whitespace, escape double quotes
            let data = cols[j].textContent.trim().replace(/"/g, '""');
            row.push(`"${data}"`);
        }
        
        csv.push(row.join(','));
    }

    // Create CSV file link and download
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
