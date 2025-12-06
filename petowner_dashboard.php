<?php
session_start();
include 'db_connect.php';

// Prevent caching to ensure logout security
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// -----------------------------
// Session & User
// -----------------------------
if (isset($_SESSION['petowner_logged_in']) && $_SESSION['petowner_logged_in'] === true) {
    if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
        die("Error: Pet owner information missing. Please log in again.");
    }
    $user_name = $_SESSION['user_name'];
    $display_name = $_SESSION['display_name'] ?? $user_name;
} else {
    header("Location: pet-login.php");
    exit();
}

// -----------------------------
// Handle pet deletion
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pet_id'])) {
    $pet_id_to_delete = intval($_POST['delete_pet_id']);

    if (tableExists($conn, 'pets') && tableExists($conn, 'symptoms')) {
        // Verify the pet belongs to the current user
        $result = executeQuery($conn, "SELECT user_name FROM pets WHERE pet_id = ?", [$pet_id_to_delete], 'i');

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['user_name'] === $user_name) {
                // Delete symptoms first (foreign key constraint)
                executeQuery($conn, "DELETE FROM symptoms WHERE pet_id = ?", [$pet_id_to_delete], 'i');

                // Delete the pet
                executeQuery($conn, "DELETE FROM pets WHERE pet_id = ?", [$pet_id_to_delete], 'i');

                header("Location: petowner_dashboard.php");
                exit();
            }
        }
    } else {
        error_log("Required tables do not exist for pet deletion");
    }
}

// -----------------------------
// Handle symptom deletion
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_symptom_id'])) {
    $symptom_id_to_delete = intval($_POST['delete_symptom_id']);

    if (tableExists($conn, 'symptoms') && tableExists($conn, 'pets')) {
        // Verify the symptom belongs to a pet owned by the current user
        $result = executeQuery($conn, "SELECT p.user_name FROM symptoms s JOIN pets p ON s.pet_id = p.pet_id WHERE s.id = ?", [$symptom_id_to_delete], 'i');

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['user_name'] === $user_name) {
                // Delete the symptom
                executeQuery($conn, "DELETE FROM symptoms WHERE id = ?", [$symptom_id_to_delete], 'i');

                header("Location: petowner_dashboard.php");
                exit();
            }
        }
    } else {
        error_log("Required tables do not exist for symptom deletion");
    }
}

// -----------------------------
// Fetch pets
// -----------------------------
$pets = [];
if (tableExists($conn, 'pets')) {
    $query = "SELECT
        pet_id AS id,
        pet_name AS name,
        pet_gender AS gender,
        pet_weight AS weight,
        pet_breed AS breed,
        pet_age AS age,
        avatar
    FROM pets WHERE user_name=? ORDER BY pet_id DESC";

    $result = executeQuery($conn, $query, [$user_name], 's');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $pets[$row['id']] = $row;
        }
    } else {
        error_log("Failed to fetch pets for user: $user_name");
    }
} else {
    error_log("Pets table does not exist");
}



// -----------------------------
// Fetch symptoms and prescription per pet
// -----------------------------
$symptoms_by_pet = [];
$symptoms = [];
if (tableExists($conn, 'pets') && tableExists($conn, 'symptoms')) {
    foreach ($pets as $pet_id => $pet) {
        // Fetch prescription, medication, history from pets table
        $result_pet = executeQuery($conn, "SELECT prescription, medication, history FROM pets WHERE pet_id=? AND user_name=?", [$pet_id, $user_name], 'is');
        $prescription = '';
        $medication = '';
        $history = '';
        if ($result_pet && $result_pet->num_rows > 0) {
            $row_pet = $result_pet->fetch_assoc();
            $prescription = $row_pet['prescription'] ?? '';
            $medication = $row_pet['medication'] ?? '';
            $history = $row_pet['history'] ?? '';
        }

        // Fetch symptoms from symptoms table
        $result_symptoms = executeQuery($conn, "SELECT symptom FROM symptoms WHERE pet_id=? AND user_name=?", [$pet_id, $user_name], 'is');
        $symptoms_list = [];
        if ($result_symptoms && $result_symptoms->num_rows > 0) {
            while ($row_symptom = $result_symptoms->fetch_assoc()) {
                $symptoms_list[] = $row_symptom['symptom'];
            }
        }
        $symptoms_string = implode(', ', $symptoms_list);

        // Fetch documents from documents table
        $documents_list = [];
        if (tableExists($conn, 'documents')) {
            $result_documents = executeQuery($conn, "SELECT document_name, document_path FROM documents WHERE pet_id=? AND user_name=?", [$pet_id, $user_name], 'is');
            if ($result_documents && $result_documents->num_rows > 0) {
                while ($row_document = $result_documents->fetch_assoc()) {
                    $documents_list[] = $row_document;
                }
            }
        }

        $symptoms_by_pet[$pet_id] = [
            'pet_name' => $pet['name'],
            'symptoms' => $symptoms_string ?: 'None',
            'prescription' => $prescription ?: 'None',
            'medication' => $medication ?: 'None',
            'documents' => $documents_list,
            'history' => $history ?: 'None'
        ];
    }
} else {
    error_log("Required tables do not exist");
}

// -----------------------------
// Fetch appointments for the pet owner
// -----------------------------
$events = [];
if (tableExists($conn, 'appointments') && tableExists($conn, 'pets')) {
    $sql_events = "SELECT a.consultation_id, a.`Consultations Date`, a.`Symptoms Discussed`, a.Remarks, a.`Level of Threats`, p.pet_name AS pet FROM appointments a JOIN pets p ON a.`Pet` = p.pet_id WHERE a.`Pet Owner` = ? ORDER BY a.`Consultations Date` ASC";
    $result_events = executeQuery($conn, $sql_events, [$user_name], 's');
    if ($result_events) {
        while ($row_event = $result_events->fetch_assoc()) {
            $events[] = [
                'date' => $row_event['Consultations Date'],
                'level' => $row_event['Level of Threats'],
                'pet' => $row_event['pet']
            ];
        }
    }
}

// -----------------------------
// Empty placeholders
// -----------------------------
$prescriptions_by_pet = [];
$meds = [];
$appointment = [];
$prescriptions = [];
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DIAGNOPET - Pet Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#f3f6ff; --card:#ffffff; --muted:#8b8fb1; --accent:#5c4fff; --glass: rgba(255,255,255,0.7);
      --shadow: 0 6px 18px rgba(36,41,90,0.08);
    }
    *{box-sizing:border-box}
    body{font-family:Inter,system-ui,Arial; margin:0; background: linear-gradient(180deg,#eef2ff 0%, #f7f9ff 100%); color:#222}
    .wrap{max-width:1200px; margin:36px auto; padding:26px; background:linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95)); border-radius:14px; box-shadow: 0 8px 30px rgba(39,45,90,0.06); display:grid; grid-template-columns:84px 1fr; gap:18px}
    /* Sidebar */
    .sidebar{padding:18px; display:flex; flex-direction:column; gap:18px; align-items:center}
    .logo{width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7b6bff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;box-shadow:var(--shadow)}
    .nav{display:flex;flex-direction:column;gap:16px;margin-top:6px}
    .nav button{width:48px;height:48px;border-radius:12px;border:none;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer}
    .nav button.active{background:rgba(92,79,255,0.08)}
    .logout{margin-top:auto;opacity:0.7}

    /* Main */
    .main{padding:8px}
    .header{display:flex;align-items:center;justify-content:space-between;padding:8px 10px}
    .search{display:flex;gap:12px;align-items:center}
    .avatar{display:flex;gap:10px;align-items:center}
    .avatar img{width:36px;height:36px;border-radius:50%}
    .btn-add{background:var(--accent); color:#fff;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 8px 20px rgba(92,79,255,0.18)}
    .btn-logout{background:#ff4d4d; color:#fff;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 8px 20px rgba(255,77,77,0.18)}

    /* content grid */
    .grid{display:grid;grid-template-columns:repeat(12,1fr);gap:16px}
    .card{background:var(--card); border-radius:12px; padding:18px; box-shadow:var(--shadow)}
    .card.col-span-4{grid-column:span 4}
    .card.col-span-5{grid-column:span 5}
    .card.col-span-3{grid-column:span 3}
    .small{font-size:13px;color:var(--muted)}

    /* pet card */
    .pet-card{display:flex;align-items:center;gap:12px}
    .pet-avatar{width:72px;height:72px;border-radius:14px;background:#f1f3ff;display:flex;align-items:center;justify-content:center;font-weight:600}
    .pet-meta{line-height:1}
    .pet-meta h3{margin:0}
    .meta-muted{font-size:13px;color:#6b6f88}

    /* prescriptions list */
    .prescription{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3fb}
    .prescription:last-child{border-bottom:none}

    /* weight sparkline */
    .spark{height:90px}
    .weight-value{background:#fff;padding:6px 10px;border-radius:14px;display:inline-block;margin-top:6px}

    /* medications */
    .med{padding:8px 0;border-bottom:1px dashed #f1f3fb}
    .med:last-child{border-bottom:none}

    /* coming events */
    .event{padding:10px;border-radius:10px;background:linear-gradient(180deg,#fff,#fcfcff);margin-bottom:10px}

    /* tabs */
    .tabs{display:flex;border-bottom:1px solid #f1f3fb;margin-bottom:16px}
    .tab-button{padding:8px 16px;border:none;background:none;color:#8b8fb1;cursor:pointer;border-bottom:2px solid transparent}
    .tab-button.active{color:#5c4fff;border-bottom-color:#5c4fff;font-weight:600}
    .tab-content{display:none}
    .tab-content.active{display:block}

    /* responsive */
    @media(max-width:980px){.wrap{grid-template-columns:64px 1fr;padding:12px}.grid{grid-template-columns:repeat(6,1fr)}.card.col-span-4{grid-column:span 6}.card.col-span-5{grid-column:span 6}}
    @media(max-width:768px){.wrap{grid-template-columns:56px 1fr;padding:10px}.grid{grid-template-columns:repeat(4,1fr);gap:12px}.card.col-span-4{grid-column:span 4}.card.col-span-5{grid-column:span 4}.card.col-span-3{grid-column:span 4}.sidebar .nav button{width:40px;height:40px}.logo{width:40px;height:40px}.btn-add{padding:8px 12px;font-size:14px}.header{font-size:14px}.pet-avatar{width:60px;height:60px}.spark{height:70px}}
    @media(max-width:480px){.wrap{grid-template-columns:1fr;padding:8px}.sidebar{display:none}.grid{grid-template-columns:1fr;gap:10px}.card{padding:12px}.header{flex-direction:column;gap:8px;text-align:center}.btn-add{width:100%;margin-top:8px}.pet-card{flex-direction:column;text-align:center}.pet-avatar{margin:0 auto}.spark{height:60px}.weight-value{margin-top:4px}.small{font-size:12px}h2{font-size:18px}h3{font-size:16px}h4{font-size:14px}}

    /* Chat Widget Styles */
    #artemis-bubble {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      background: var(--accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: var(--shadow);
      z-index: 1000;
      font-size: 24px;
    }
    #artemis-chatbox {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 300px;
      height: 400px;
      background: var(--card);
      border-radius: 12px;
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      z-index: 1000;
    }
    #artemis-chatbox.hidden {
      display: none;
    }
    .chat-header {
      padding: 10px;
      background: var(--accent);
      color: white;
      border-radius: 12px 12px 0 0;
      font-weight: 600;
    }
    .chat-body {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }
    .chat-message {
      margin-bottom: 10px;
      padding: 8px 12px;
      border-radius: 8px;
      max-width: 80%;
    }
    .chat-message.user {
      background: var(--accent);
      color: white;
      align-self: flex-end;
      margin-left: auto;
    }
    .chat-message.bot {
      background: #f1f3fb;
      color: #222;
    }
    .chat-input {
      display: flex;
      padding: 10px;
      border-top: 1px solid #f1f3fb;
    }
    .chat-input input {
      flex: 1;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-right: 8px;
    }
    .chat-input button {
      padding: 8px 12px;
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

  .event-card {
  padding: 14px 16px;
  border-radius: 12px;
  background: #ffffff; /* White box */
  box-shadow: 0 6px 18px rgba(36,41,90,0.08); /* subtle shadow */
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column;
  gap: 6px;
  cursor: pointer;
}
.event-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(36,41,90,0.12);
}
.event-date {
  font-size: 12px;
  font-weight: 600;
  color: #5c4fff; /* accent color for date */
}
.event-title {
  font-size: 14px;
  font-weight: 600;
  color: #222; /* black for title */
}
.event-time,
.event-vet {
  font-size: 12px;
  color: #6b6f88;
}

  </style>
</head>
<body>
  <div class="wrap">
    <aside class="sidebar">
      <div class="logo">DP</div>
      <nav class="nav">
        <button class="active" title="Pets" onclick="navigate('Pets')">🐾</button>
        <button title="Vets" onclick="navigate('Vets')">🏥</button>
        <button title="Profile" onclick="navigate('Profile')">👤</button>
        <button title="Settings" onclick="navigate('Settings')">⚙️</button>
      </nav>
    </aside>

    <main class="main">
      <div class="header">
        <div style="display:flex;align-items:center;gap:18px">
          <h2 style="margin:0;color:var(--accent)">DIAGNOPET</h2>
          <div class="small">Manage your pet's health</div>
        </div>
          <div style="display:flex;gap:12px;align-items:center">
          <a class="btn-add" href="pet-add.php" role="button" style="text-decoration:none;display:inline-block;padding:10px 14px;border-radius:12px;color:#fff;background:var(--accent);box-shadow:0 8px 20px rgba(92,79,255,0.18);">Add Pets</a>
          <button type="button" class="btn-logout" onclick="location.href='logoutpet.php'">Logout</button>
          <div class="avatar"><a href="profilepets.php" style="font-size:14px;text-decoration:none;color:inherit;"><?php echo htmlspecialchars($display_name); ?></a></div>
        </div>
      </div>

      <section class="grid" style="margin-top:16px">
        <!-- left column: pets list -->
        <div class="card col-span-4">
          <h4 style="margin:0 0 12px 0">Your Pets</h4>
          <?php if (empty($pets)): ?>
            <div class="pet-card" onclick="window.location.href='pet-add.php'" style="cursor:pointer;">
              <div class="pet-avatar">➕</div>
              <div class="pet-meta">
                <h3 style="color:#8b8fb1">Add your first pet</h3>
                <div class="meta-muted">Click to add name, breed, age, and more</div>
              </div>
            </div>
          <?php else: ?>
            <?php foreach($pets as $pet): ?>
              <div class="pet-card">
                <div class="pet-avatar">
                  <?php if (!empty($pet['avatar'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($pet['avatar']); ?>" alt="Pet Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                  <?php else: ?>

                  <?php endif; ?>
                </div>
                <div class="pet-meta">
                  <h3><?php echo htmlspecialchars($pet['name']); ?> <small style="color:#5c6aa6">♂</small></h3>
                  <div class="meta-muted"><?php echo htmlspecialchars($pet['breed'] . ' | ' . $pet['age'] . ' | ' . $pet['weight'] . 'kg'); ?></div>
                  <div class="small" style="margin-top:8px;color:#a1a6c2">ID: <?php echo htmlspecialchars($pet['id']); ?></div>
                  <button onclick="window.location.href='symptoms-adding.php?pet_id=<?php echo $pet['id']; ?>'" style="background:#28a745;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:12px;margin-top:4px;margin-right:4px;cursor:pointer;">Add Symptoms</button>
                  <form method="POST" style="display:inline;" id="delete-form-<?php echo $pet['id']; ?>">
                    <input type="hidden" name="delete_pet_id" value="<?php echo $pet['id']; ?>">
                    <button type="button" onclick="confirmDeletePet(<?php echo $pet['id']; ?>)" style="background:#ff4d4d;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:12px;margin-top:4px;cursor:pointer;">Delete</button>
                  </form>
                </div>
              </div>
              <hr style="margin:10px 0;border:none;border-top:1px solid #f1f3fb">
            <?php endforeach; ?>
            <button class="btn-add" onclick="window.location.href='pet-add.php'" style="width:100%;margin-top:10px;">Add More Pet</button>
          <?php endif; ?>

          <hr style="margin:14px 0;border:none;border-top:1px solid #f1f3fb">

          <div>
            <h4 style="margin:0 0 8px 0"></h4>
            <?php if (empty($prescriptions)): ?>
              <div class="small" style="color:#8b8fb1;padding:8px 0"></div>
            <?php else: ?>
              <?php foreach($prescriptions as $p): ?>
                <div class="prescription">
                  <div>
                    <div style="font-weight:600"><?php echo htmlspecialchars($p['name']); ?></div>
                    <div class="small"><?php echo htmlspecialchars($p['date']); ?></div>
                  </div>
                  <div style="font-size:18px;opacity:0.6">💊</div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- middle column -->
        <div class="card col-span-4">
          <?php if (empty($symptoms_by_pet)): ?>
            <div style="display:flex;align-items:center;justify-content:center;color:#8b8fb1;font-size:14px;padding:20px;">
              No symptoms added yet. Add symptoms to your pets to get started!
            </div>
          <?php else: ?>
            <div class="tabs">
              <?php $first = true; ?>
              <?php foreach($symptoms_by_pet as $pet_id => $data): ?>
                <button class="tab-button <?php echo $first ? 'active' : ''; ?>" onclick="openTab(<?php echo $pet_id; ?>, this)"><?php echo htmlspecialchars($data['pet_name']); ?></button>
                <?php $first = false; ?>
              <?php endforeach; ?>
            </div>
            <?php $first = true; ?>
            <?php foreach($symptoms_by_pet as $pet_id => $data): ?>
              <div id="tab-<?php echo $pet_id; ?>" class="tab-content <?php echo $first ? 'active' : ''; ?>">
                <div class="med" style="padding:12px 0;border-bottom:1px solid #f1f3fb;">
                  <div>
                    <div style="font-weight:600">Symptoms:</div>
                    <div class="small"><?php echo htmlspecialchars($data['symptoms'] ?: 'None'); ?></div>
                  </div>
                </div>
                <div class="med" style="padding:12px 0;border-bottom:1px solid #f1f3fb;">
                  <div>
                    <div style="font-weight:600">Prescription:</div>
                    <div class="small"><?php echo htmlspecialchars($data['prescription'] ?: 'None'); ?></div>
                  </div>
                </div>
                <div class="med" style="padding:12px 0;border-bottom:1px solid #f1f3fb;">
                  <div>
                    <div style="font-weight:600">Medication:</div>
                    <div class="small"><?php echo htmlspecialchars($data['medication'] ?: 'None'); ?></div>
                  </div>
                </div>
                <div class="med" style="padding:12px 0;border-bottom:1px solid #f1f3fb;">
                  <div>
                    <div style="font-weight:600">History:</div>
                    <div class="small"><?php echo htmlspecialchars($data['history'] ?: 'None'); ?></div>
                  </div>
                </div>
                <button onclick="window.location.href='casebox_ai.php?pet_id=<?php echo $pet_id; ?>'" style="background:var(--accent); color:#fff; padding:10px 14px; border-radius:12px; border:none; cursor:pointer; box-shadow:0 8px 20px rgba(92,79,255,0.18); margin-top:12px;">Add Case To Artemis</button>
              </div>
              <?php $first = false; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <hr style="margin:14px 0;border:none;border-top:1px solid #f1f3fb">

          <h3 style="margin:0 0 8px 0"></h3>
          <?php if (empty($meds)): ?>
            <div class="small" style="color:#8b8fb1;padding:8px 0"></div>
          <?php else: ?>
            <?php foreach($meds as $m): ?>
              <div class="med">
                <div style="display:flex;justify-content:space-between;align-items:center">
                  <div>
                    <div style="font-weight:600"><?php echo htmlspecialchars($m['name']); ?></div>
                    <div class="small"><?php echo htmlspecialchars($m['note']); ?></div>
                  </div>
                  <div style="opacity:0.6">⋮</div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- right column -->
        <!--<div class="card col-span-3">
          <h3 style="margin:0 0 8px 0">Appointment</h3>
          <?php if (empty($appointment['date'])): ?>
            <div class="small" style="color:#8b8fb1">No upcoming appointment. Schedule one with your vet!</div>
          <?php else: ?>
            <div class="small">Date: <?php echo htmlspecialchars($appointment['date']); ?> | <?php echo htmlspecialchars($appointment['time']); ?></div>
            <div class="small">Vet: <?php echo htmlspecialchars($appointment['vet']); ?></div>
            <div class="small">Place: <?php echo htmlspecialchars($appointment['place']); ?></div>
          <?php endif; ?> -->

          <hr style="margin:12px 0;border:none;border-top:1px solid #f1f3fb">

<div class="card col-span-3">
  <h4 style="margin:0 0 12px 0; color:#000000">Cases</h4>
  
  <?php if (empty($events)): ?>
    <div class="small" style="color:#8b8fb1;padding:12px 0; text-align:center;">
      No upcoming events. Add reminders for vaccinations, appointments, or grooming!
    </div>
  <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:12px;">
      <?php foreach($events as $e): ?>
        <div class="event-card">
          <div class="event-date"><?php echo htmlspecialchars(date('M d, Y', strtotime($e['date']))); ?></div>
          <div class="event-level">Level of Threats: <?php echo htmlspecialchars($e['level']); ?> - Pet: <?php echo htmlspecialchars($e['pet']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>




        </div>
      </section>

    </main>
  </div>

  <!-- Artemis Chat Widget -->
  <div id="artemis-bubble">🐾</div>
  <div id="artemis-chatbox" class="hidden">
    <div class="chat-header">Artemis 🐾</div>
    <div class="chat-body"></div>
    <div class="chat-input">
      <input type="text" placeholder="Type your message...">
      <button>Send</button>
    </div>
  </div>

  <script src="chat-widget.js"></script>
  <script>
    // Button functionalities
    function navigate(section) {
  switch(section) {
    case 'Pets':
      break;
    case 'Vets':
      window.location.href = 'vets.php';
      break;
    case 'Profile':
      window.location.href = 'profilepets.php';
      break;
    case 'Settings':
      window.location.href = 'help_support.php';
      break;
    case 'Support':
      alert('Support section - Functionality to be implemented.');
      break;
  }
}


    function goBack() {
      window.location.href = 'role-select.php';
    }

    function confirmDeletePet(petId) {
      if (confirm('Are you sure you want to delete this pet? This action cannot be undone.')) {
        document.getElementById('delete-form-' + petId).submit();
      }
    }

    function confirmDeleteSymptom(symptomId) {
      if (confirm('Are you sure you want to delete this symptom? This action cannot be undone.')) {
        document.getElementById('delete-symptom-form-' + symptomId).submit();
      }
    }

    function openTab(petId, button) {
      // Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => content.classList.remove('active'));

      // Remove active class from all tab buttons
      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => button.classList.remove('active'));

      // Show the selected tab content
      document.getElementById('tab-' + petId).classList.add('active');

      // Add active class to the clicked button
      button.classList.add('active');
    }

  </script>
</body>
</html>