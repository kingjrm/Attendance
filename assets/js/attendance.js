// assets/js/attendance.js
let scanner = null;

function onScanSuccess(decodedText, decodedResult) {
    processAttendance(decodedText, 'QR');
    scanner.clear(); // Stop scanning after success
}

function onScanFailure(error) {
    console.warn(`Code scan error = ${error}`);
}

document.getElementById('start-scan').addEventListener('click', function() {
    if (scanner) {
        scanner.clear();
        scanner = null;
        this.textContent = 'Start QR Scan';
        return;
    }

    scanner = new Html5QrcodeScanner('preview', { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess, onScanFailure);
    this.textContent = 'Stop Scan';
});

document.getElementById('submit-manual').addEventListener('click', function() {
    const studentId = document.getElementById('student-id').value.trim();
    if (studentId) {
        processAttendance(studentId, 'Manual');
    }
});

function processAttendance(identifier, method) {
    fetch('includes/process_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ identifier: identifier, method: method })
    })
    .then(response => response.json())
    .then(data => {
        const feedback = document.getElementById('feedback');
        feedback.style.display = 'block';
        feedback.className = 'feedback ' + (data.success ? 'success' : 'error');
        feedback.textContent = data.message;
        if (data.success) {
            document.getElementById('student-id').value = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}