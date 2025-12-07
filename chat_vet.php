<?php
session_start();
include 'db_connect.php';

// 1. Authorization Check: Ensure the user is logged in as a Vet
if (!isset($_SESSION['vet_id'])) {
    // Send 403 Forbidden status code and exit
    http_response_code(403);
    die("Access denied. Not logged in as a Veterinarian.");
}

$vet_id = $_SESSION['vet_id'];

// Get the petowner ID from the URL and cast it to an integer
$petowner_id = isset($_GET['petowner_id']) ? intval($_GET['petowner_id']) : null;
$petowner_name = null; // Variable to store the name of the selected pet owner

// 2. Access Control: Fetch ONLY pet owners associated with the current vet (IDOR fix)
$petowners = [];
if ($conn) {
    // FIX: Join with the new vet_petowner_association table to restrict results
    $sql = "
        SELECT 
            p.owner_id AS id, 
            p.Name AS name, 
            p.Email AS email 
        FROM petowners p
        JOIN vet_petowner_association vpa ON p.owner_id = vpa.petowner_id
        WHERE vpa.vet_id = ? 
        ORDER BY p.Name ASC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vet_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $petowners[] = $row;

            // Check if the pet owner from the URL is authorized/exists
            if ($petowner_id !== null && $row['id'] === $petowner_id) {
                $petowner_name = $row['name'];
            }
        }
    }
}

// 3. Validation Check: If an ID was passed in the URL, but the vet isn't authorized to view it
if ($petowner_id !== null && $petowner_name === null) {
    // This handles cases where an attacker tries to view a chat with an unauthorized petowner_id
    http_response_code(403);
    die("Access denied. You are not authorized to chat with this Pet Owner.");
}

// If no pet owner is selected and the list is not empty, ensure the modal is shown
$show_modal = ($petowner_id === null) ? 'flex' : 'none';

?>

<!DOCTYPE html>
<html>
<head>
<title>Chat with Pet Owner</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
/* ... (CSS is unchanged) ... */

 body {
        font-family: Arial, sans-serif;
        background: #f3f6fb;
        margin: 0;
        padding: 0;
    }

    .chat-container {
        width: 100%;
        max-width: 550px;
        margin: 30px auto;
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .chat-header {
        background: #28a745;
        color: white;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    #chat-box {
        height: 420px;
        overflow-y: auto;
        padding: 15px;
        background: #eef2f7;
    }

    #chat-box::-webkit-scrollbar {
        width: 8px;
    }
    #chat-box::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .message {
        max-width: 70%;
        padding: 10px 14px;
        margin: 8px 0;
        border-radius: 13px;
        font-size: 15px;
        line-height: 1.3em;
        position: relative;
        word-wrap: break-word;
    }

    .vet {
        background: #ffffff;
        border: 1px solid #ddd;
        text-align: left;
    }

    .user {
        background: #d4ffd8;
        text-align: right;
        margin-left: auto;
        border: 1px solid #b9f5bf;
    }

    small {
        display: block;
        font-size: 11px;
        margin-top: 5px;
        opacity: 0.6;
    }

    .input-area {
        padding: 10px;
        display: flex;
        gap: 10px;
        background: white;
        border-top: 1px solid #ddd;
    }

    #message {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #ccc;
        resize: none;
        height: 45px;
        font-size: 15px;
    }

    #send {
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        font-size: 15px;
    }

    #send:hover {
        background: #1e8a37;
    }

    /* Pet Owner Selection Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.show {
        display: flex;
        opacity: 1;
    }

    .modal .modal-content {
        background: #fff;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        text-align: left;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal.show .modal-content {
        transform: translateY(0);
    }

    .modal .modal-content h2 {
        margin-top: 0;
        color: #28a745;
        font-size: 24px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .modal .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #eee;
        border: none;
        font-size: 22px;
        font-weight: bold;
        cursor: pointer;
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s ease;
    }

    .modal .close-btn:hover {
        background: #28a745;
        color: #fff;
    }

    .petowner-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: flex-start;
    }

    .petowner-card {
        width: 250px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .petowner-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .petowner-placeholder-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100px;
        background: #f0f0f0;
        color: #666;
        font-size: 50px;
    }

    .petowner-content {
        padding: 15px 20px;
        flex-grow: 1;
    }

    .petowner-name {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #333;
    }

    .petowner-subtext {
        font-size: 13px;
        color: #555;
        margin-top: 4px;
        line-height: 1.4;
    }

    .petowner-subtext strong {
        font-weight: 600;
        color: #000;
        margin-right: 4px;
    }

    .petowner-stats {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-top: 1px solid #eee;
    }

    .petowner-btn {
        background: #28a745;
        color: white;
        padding: 10px 18px;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
        font-weight: 600;
    }

    .petowner-btn:hover {
        background: #1e8a37;
        transform: translateY(-2px);
    }

    .select-petowner-btn {
        background: #007bff;
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        margin: 20px auto;
        display: block;
        transition: 0.3s;
    }

    .select-petowner-btn:hover {
        background: #0056b3;
    }
</style>
</head>
<body>

<div class="chat-container" id="chat-container" style="display: <?= $petowner_id ? 'block' : 'none' ?>;">
    <div class="chat-header"><?= $petowner_name ? 'Chat with ' . htmlspecialchars($petowner_name) : 'Pet Owner Chat' ?></div>

    <div id="chat-box"></div>

    <div class="input-area">
        <textarea id="message" placeholder="Type your message..."></textarea>
        <button id="send">Send</button>
    </div>
</div>

<div id="petowner-modal" class="modal" style="display: <?= $show_modal ?>;">
    <div class="modal-content">
        <button class="close-btn" onclick="closePetOwnerModal()">×</button>
        <h2>Select a Pet Owner</h2>
        <div class="petowner-cards" id="petowner-cards-container">
            </div>
    </div>
</div>

<script>
// PHP-side JSON encoding is secure when used as a JS variable assignment
const petownersList = <?= json_encode($petowners); ?>;

let selectedPetOwnerId = <?= $petowner_id ?: 'null' ?>;

// Utility function to safely HTML escape strings (XSS defense)
function htmlEscape(str) {
    if (typeof str === 'undefined' || str === null) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function openPetOwnerModal() {
    const modal = document.getElementById('petowner-modal');
    modal.classList.add('show');
    populatePetOwnerCards();
}

function closePetOwnerModal() {
    const modal = document.getElementById('petowner-modal');
    modal.classList.remove('show');
}

function populatePetOwnerCards() {
    const container = document.getElementById('petowner-cards-container');
    container.innerHTML = '';

    if (petownersList.length > 0) {
        petownersList.forEach(petowner => {
            const card = document.createElement('div');
            card.className = 'petowner-card';

            // FIX: Use htmlEscape for all dynamic data going into HTML content or attributes
            const safeName = htmlEscape(petowner.name);
            const safeEmail = htmlEscape(petowner.email);
            const safeIdForJS = htmlEscape(petowner.id);
            const safeNameForJS = htmlEscape(petowner.name);
            
            card.innerHTML = `
                <div class="petowner-placeholder-icon">👤</div>
                <div class="petowner-content">
                    <div class="petowner-name">${safeName}</div>
                    <div class="petowner-subtext"><strong>Email:</strong> ${safeEmail}</div>
                </div>
                <div class="petowner-stats">
                    <button class="petowner-btn" onclick="selectPetOwner('${safeIdForJS}', '${safeNameForJS}')">Chat with Owner 💬</button>
                </div>
            `;

            container.appendChild(card);
        });
    } else {
        container.innerHTML = '<p>No pet owners available for chat.</p>';
    }
}

function selectPetOwner(petownerId, petownerName) {
    // This function can be simplified to just navigate to the new URL
    window.location.href = `chat_vet.php?petowner_id=${petownerId}`;
}

// Function to load messages from the backend
function loadMessages() {
    if (!selectedPetOwnerId) return;

    // The vet_id is retrieved from a secure PHP session variable.
    // The selectedPetOwnerId has been pre-authorized.
    $("#chat-box").load(
        "load_messages.php?petowner_id=" + selectedPetOwnerId + "&vet_id=<?= $vet_id ?>",
        function() {
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        }
    );
}

// Automatic message reloading
if (selectedPetOwnerId) {
    // Show chat container and set header text immediately
    document.getElementById('chat-container').style.display = 'block';
    document.querySelector('.chat-header').innerText = `Chat with <?= $petowner_name ? htmlspecialchars($petowner_name) : 'Pet Owner' ?>`;
    
    // Load and refresh messages
    loadMessages();
    setInterval(loadMessages, 1000);
} else {
    // If no pet owner is selected, ensure the modal is populated and visible
    openPetOwnerModal();
}

$("#send").click(function() {
    if (!selectedPetOwnerId) {
        alert('Please select a pet owner first.');
        return;
    }

    var msg = $("#message").val().trim();
    if (msg.length === 0) return;

    $.post("send_messages.php", {
        message: msg,
        receiver_type: "petowner",
        receiver_id: selectedPetOwnerId
    }, function(response) {
        $("#message").val("");
        loadMessages();
    }).fail(function(xhr, status, error) {
        alert('Error sending message: ' + xhr.responseText);
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('petowner-modal');
    if (event.target === modal) {
        closePetOwnerModal();
    }
};

</script>

</body>
</html>