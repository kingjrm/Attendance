// assets/js/attendance.js
let scanner = null;

// Initialize scanner on page load
document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});

function startScanner() {
    if (scanner) {
        scanner.clear();
    }

    scanner = new Html5QrcodeScanner('preview', {
        fps: 10,
        qrbox: 250,
        supportedScanTypes: [Html5QrcodeSupportedFormats.QR_CODE]
    });
    scanner.render(onScanSuccess, onScanFailure);
}

function onScanSuccess(decodedText, decodedResult) {
    processAttendance(decodedText, 'QR');
    // Keep scanner running for continuous scanning
}

function onScanFailure(error) {
    console.warn(`Code scan error = ${error}`);
}

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